<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Letter;
use App\Traits\HandlesLampiranUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    use HandlesLampiranUpload;
    public function index(Request $request)
    {
        $user = auth()->user();
        $divisionId = $user->division_id;
        
        // Baca tab dari request atau session, default 'keluar'
        $tab = $request->input('tab') ?? session('user_letters_tab', 'keluar');
        session(['user_letters_tab' => $tab]);
        
        // Surat yang dikirim oleh user (surat keluar)
        $suratKeluarQuery = Letter::where('pengirim_id', $user->id)
            ->with(['penerimaDivision', 'pengirim', 'penerimaUser']);

        // Surat masuk: surat yang ditujukan ke divisi user ATAU surat pertemuan individu yang ditujukan ke user
        // (bukan yang dikirim oleh divisi user sendiri atau user sendiri)
        $suratMasukQuery = null;
        if ($divisionId) {
            $suratMasukQuery = Letter::where(function($query) use ($divisionId, $user) {
                // Surat yang masuk ke divisi user (bukan yang dikirim oleh divisi yang sama)
                $query->where(function($q) use ($divisionId) {
                    $q->where('penerima_division_id', $divisionId)
                      ->whereHas('pengirim', function($subQuery) use ($divisionId) {
                          $subQuery->where(function($subQ) use ($divisionId) {
                              $subQ->where('division_id', '!=', $divisionId)
                                   ->orWhereNull('division_id');
                          });
                      });
                })
                // ATAU surat pertemuan individu yang ditujukan ke user ini (tidak peduli divisi)
                ->orWhere(function($q) use ($user) {
                    $q->where('jenis', 'pertemuan_individu')
                      ->where('penerima_user_id', $user->id);
                });
            })
            // Pastikan surat bukan yang dikirim oleh user sendiri
            ->where('pengirim_id', '!=', $user->id)
            ->with(['penerimaDivision', 'pengirim.division', 'penerimaUser', 'responder.division']);
        } else {
            // Jika user tidak punya divisi, hanya surat pertemuan individu yang ditujukan ke user
            $suratMasukQuery = Letter::where('jenis', 'pertemuan_individu')
                ->where('penerima_user_id', $user->id)
                ->where('pengirim_id', '!=', $user->id)
                ->with(['penerimaDivision', 'pengirim.division', 'penerimaUser', 'responder.division']);
        }

        // Apply search filters
        $query = $tab === 'masuk' ? $suratMasukQuery : $suratKeluarQuery;

        // Filter by search (judul)
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // Filter by jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by tanggal pertemuan
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pertemuan', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pertemuan', '<=', $request->tanggal_sampai);
        }

        $letters = $query->latest()->get();

        // Get all letters for count (without filters)
        $suratKeluar = Letter::where('pengirim_id', $user->id)
            ->with(['penerimaDivision', 'pengirim', 'penerimaUser'])
            ->latest()
            ->get();

        $suratMasuk = collect();
        if ($divisionId) {
            $suratMasuk = Letter::where(function($query) use ($divisionId, $user) {
                $query->where(function($q) use ($divisionId) {
                    $q->where('penerima_division_id', $divisionId)
                      ->whereHas('pengirim', function($subQuery) use ($divisionId) {
                          $subQuery->where(function($subQ) use ($divisionId) {
                              $subQ->where('division_id', '!=', $divisionId)
                                   ->orWhereNull('division_id');
                          });
                      });
                })
                ->orWhere(function($q) use ($user) {
                    $q->where('jenis', 'pertemuan_individu')
                      ->where('penerima_user_id', $user->id);
                });
            })
            ->where('pengirim_id', '!=', $user->id)
            ->with(['penerimaDivision', 'pengirim.division', 'penerimaUser', 'responder.division'])
            ->latest()
            ->get();
        } else {
            $suratMasuk = Letter::where('jenis', 'pertemuan_individu')
                ->where('penerima_user_id', $user->id)
                ->where('pengirim_id', '!=', $user->id)
                ->with(['penerimaDivision', 'pengirim.division', 'penerimaUser', 'responder.division'])
                ->latest()
                ->get();
        }

        return view('user.letters.index', compact('letters', 'suratMasuk', 'suratKeluar', 'tab'));
    }

    public function setTab(Request $request)
    {
        // Pastikan hanya bisa diakses via POST
        if (!request()->isMethod('post')) {
            abort(405, 'Method tidak diizinkan.');
        }

        $request->validate([
            'tab' => 'required|in:masuk,keluar',
        ]);

        session(['user_letters_tab' => $request->tab]);

        return redirect()->route('surat_masuk.index');
    }

    public function create()
    {
        // User bisa membuat surat untuk divisi lain (tidak termasuk divisinya sendiri)
        $user = auth()->user();
        $query = Division::orderBy('name');
        
        // Jika user punya divisi, exclude divisinya sendiri
        if ($user->division_id) {
            $query->where('id', '!=', $user->division_id);
        }
        
        $divisions = $query->get();
        return view('user.letters.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        // Pastikan hanya bisa diakses via POST
        if (!request()->isMethod('post')) {
            abort(405, 'Method tidak diizinkan.');
        }

        // User hanya bisa membuat surat jenis pertemuan_individu
        $request->merge(['jenis' => 'pertemuan_individu']);
        
        $request->validate([
            'penerima_division_id' => 'required|exists:divisions,id',
            'jenis' => 'required|in:pertemuan_individu',
            'penerima_user_id' => 'required|exists:users,id',
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tanggal_pertemuan' => 'required|date|after_or_equal:today',
            'jam_pertemuan' => 'required',
            'lampiran' => 'nullable|array',
            'lampiran.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ], [
            'jenis.in' => 'User hanya dapat membuat surat jenis Pertemuan Individu.',
            'penerima_user_id.required' => 'Pilih nama penerima wajib diisi.',
            'penerima_user_id.exists' => 'Penerima yang dipilih tidak valid.',
            'lampiran.*.mimes' => 'Lampiran harus berupa file: PDF, DOC, DOCX, JPG, JPEG, atau PNG.',
            'lampiran.*.max' => 'Ukuran setiap lampiran maksimal 5MB.',
        ]);

        $data = [
            'pengirim_id' => auth()->id(),
            'penerima_division_id' => $request->penerima_division_id,
            'jenis' => 'pertemuan_individu',
            'judul' => $request->judul,
            'isi' => $request->isi,
            'tanggal_pertemuan' => $request->tanggal_pertemuan,
            'jam_pertemuan' => $request->jam_pertemuan,
            'penerima_user_id' => $request->penerima_user_id,
        ];

        $lampiranFiles = $this->prepareUploadedFiles($request, 'lampiran');
        $this->ensureTotalAttachmentsSize($lampiranFiles, 'lampiran');

        if (!empty($lampiranFiles)) {
            $data['lampiran'] = $this->storeAttachments($lampiranFiles, 'lampiran');
        }

        Letter::create($data);

        session(['user_letters_tab' => 'keluar']);
        return redirect()->route('surat_masuk.index')->with('success', 'Surat berhasil dibuat.');
    }

    public function show(Letter $letter)
    {
        $user = auth()->user();
        $divisionId = $user->division_id;
        
        // User bisa melihat:
        // 1. Surat yang mereka kirim (pengirim_id === user->id)
        // 2. Surat yang masuk ke divisi mereka (penerima_division_id === division_id)
        // 3. Surat pertemuan individu yang ditujukan ke mereka (penerima_user_id === user->id)
        $isSuratKeluar = $letter->pengirim_id === $user->id;
        $isSuratMasuk = $divisionId && $letter->penerima_division_id === $divisionId;
        $isSuratIndividu = $letter->penerima_user_id === $user->id;
        
        if (!$isSuratKeluar && !$isSuratMasuk && !$isSuratIndividu) {
            abort(403, 'Anda tidak memiliki akses untuk melihat surat ini.');
        }
        
        $letter->load(['pengirim.division', 'penerimaDivision', 'penerimaUser', 'responder.division']);
        return view('user.letters.show', compact('letter'));
    }

    public function respond(Request $request, Letter $letter)
    {
        // Pastikan hanya bisa diakses via POST
        if (!request()->isMethod('post')) {
            abort(405, 'Method tidak diizinkan.');
        }

        $user = auth()->user();
        $divisionId = $user->division_id;
        
        // User bisa menanggapi surat yang masuk ke divisinya ATAU surat pertemuan individu yang ditujukan ke mereka
        $isSuratMasuk = $divisionId && $letter->penerima_division_id === $divisionId;
        $isSuratIndividu = $letter->penerima_user_id === $user->id;
        
        if (!$isSuratMasuk && !$isSuratIndividu) {
            abort(403, 'Anda tidak memiliki akses untuk menanggapi surat ini.');
        }
        
        // Pastikan surat masih pending
        if ($letter->status !== 'pending') {
            return back()->withErrors(['status' => 'Surat ini sudah ditanggapi.'])->withInput();
        }

        $request->validate([
            'status' => 'required|in:diterima,ditolak',
            'catatan_petugas' => 'required|string',
            'lampiran_response' => 'nullable|array',
            'lampiran_response.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ], [
            'lampiran_response.*.mimes' => 'Lampiran harus berupa file: PDF, DOC, DOCX, JPG, JPEG, atau PNG',
            'lampiran_response.*.max' => 'Ukuran setiap lampiran maksimal 5MB',
        ]);

        $data = [
            'status' => $request->status,
            'catatan_petugas' => $request->catatan_petugas,
            'responded_by' => auth()->id(),
            'responded_at' => now(),
        ];

        $lampiranResponseFiles = $this->prepareUploadedFiles($request, 'lampiran_response');
        $this->ensureTotalAttachmentsSize($lampiranResponseFiles, 'lampiran_response');

        if (!empty($lampiranResponseFiles)) {
            $data['lampiran_response'] = $this->storeAttachments($lampiranResponseFiles, 'lampiran_response');
        }

        $letter->update($data);

        // Log aktivitas respond
        \App\Traits\LogsActivity::logCustomActivity(
            'respond',
            "Menanggapi surat: {$letter->judul} dengan status " . ($request->status === 'diterima' ? 'Diterima' : 'Ditolak'),
            get_class($letter),
            $letter->id
        );

        return redirect()->route('surat_masuk.index')->with('success', 'Surat berhasil ditanggapi.');
    }

    public function downloadLampiran(Letter $letter)
    {
        $user = auth()->user();
        $divisionId = $user->division_id;
        
        // User bisa download:
        // 1. Surat yang mereka kirim (pengirim_id === user->id)
        // 2. Surat yang masuk ke divisi mereka (penerima_division_id === division_id)
        // 3. Surat pertemuan individu yang ditujukan ke mereka (penerima_user_id === user->id)
        $isSuratKeluar = $letter->pengirim_id === $user->id;
        $isSuratMasuk = $divisionId && $letter->penerima_division_id === $divisionId;
        $isSuratIndividu = $letter->penerima_user_id === $user->id;
        
        if (!$isSuratKeluar && !$isSuratMasuk && !$isSuratIndividu) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh lampiran ini.');
        }

        $attachments = $letter->lampiran ?? [];

        if (empty($attachments)) {
            abort(404, 'Lampiran tidak ditemukan.');
        }

        $index = (int) request('file', 0);

        if (!array_key_exists($index, $attachments)) {
            abort(404, 'Lampiran tidak ditemukan.');
        }

        return Storage::disk('public')->download($attachments[$index]);
    }
}

