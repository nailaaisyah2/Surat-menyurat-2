<?php

namespace App\Http\Controllers\Petugas;

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
        
        // Baca tab dari request atau session, default 'masuk'
        $tab = $request->input('tab') ?? session('petugas_letters_tab', 'masuk');
        session(['petugas_letters_tab' => $tab]);
        
        // Jika tidak ada divisi, return empty
        if (!$divisionId) {
            return view('petugas.letters.index', [
                'letters' => collect(),
                'suratMasuk' => collect(),
                'suratKeluar' => collect(),
                'tab' => $tab
            ]);
        }
        
        // Petugas melihat surat masuk ke divisinya (bukan yang dikirim oleh divisinya sendiri)
        // Surat masuk = surat yang ditujukan ke divisi petugas DAN pengirim bukan dari divisi petugas
        $suratMasukQuery = Letter::where('penerima_division_id', $divisionId)
            ->whereHas('pengirim', function($query) use ($divisionId) {
                // Ambil surat yang pengirimnya bukan dari divisi petugas
                // Ini akan mengambil semua surat dari divisi lain atau user tanpa divisi
                $query->where(function($q) use ($divisionId) {
                    $q->where('division_id', '!=', $divisionId)
                      ->orWhereNull('division_id');
                });
            })
            ->with(['penerimaDivision', 'pengirim.division', 'penerimaUser', 'responder.division']);

        // Petugas melihat surat keluar dari divisinya (yang dikirim oleh user dari divisinya)
        // Surat keluar = surat yang dikirim oleh user dari divisi petugas (tidak peduli ditujukan ke mana)
        $suratKeluarQuery = Letter::whereHas('pengirim', function($query) use ($divisionId) {
            $query->where('division_id', $divisionId);
        })
        ->with(['penerimaDivision', 'pengirim.division', 'penerimaUser', 'responder.division']);

        // Apply search filters
        $query = $tab === 'keluar' ? $suratKeluarQuery : $suratMasukQuery;

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
        $suratMasuk = Letter::where('penerima_division_id', $divisionId)
            ->whereHas('pengirim', function($query) use ($divisionId) {
                $query->where(function($q) use ($divisionId) {
                    $q->where('division_id', '!=', $divisionId)
                      ->orWhereNull('division_id');
                });
            })
            ->with(['penerimaDivision', 'pengirim.division', 'penerimaUser', 'responder.division'])
            ->latest()
            ->get();

        $suratKeluar = Letter::whereHas('pengirim', function($query) use ($divisionId) {
            $query->where('division_id', $divisionId);
        })
        ->with(['penerimaDivision', 'pengirim.division', 'penerimaUser', 'responder.division'])
        ->latest()
        ->get();

        return view('petugas.letters.index', compact('letters', 'suratMasuk', 'suratKeluar', 'tab'));
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

        session(['petugas_letters_tab' => $request->tab]);

        return redirect()->route('surat_masuk.index');
    }

    public function create()
    {
        // Petugas bisa membuat surat untuk divisi lain (tidak termasuk divisinya sendiri)
        $user = auth()->user();
        $query = Division::orderBy('name');
        
        // Jika user punya divisi, exclude divisinya sendiri
        if ($user->division_id) {
            $query->where('id', '!=', $user->division_id);
        }
        
        $divisions = $query->get();
        return view('petugas.letters.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        // Pastikan hanya bisa diakses via POST
        if (!request()->isMethod('post')) {
            abort(405, 'Method tidak diizinkan.');
        }

        $request->validate([
            'penerima_division_id' => 'required|exists:divisions,id',
            'jenis' => 'required|in:pertemuan_individu,rapat_kantor',
            'penerima_user_id' => 'required_if:jenis,pertemuan_individu|nullable|exists:users,id',
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tanggal_pertemuan' => 'required|date|after_or_equal:today',
            'jam_pertemuan' => 'required',
            'lampiran' => 'nullable|array',
            'lampiran.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ], [
            'penerima_user_id.required_if' => 'Pilih nama penerima wajib diisi untuk pertemuan individu.',
            'penerima_user_id.exists' => 'Penerima yang dipilih tidak valid.',
            'lampiran.*.mimes' => 'Lampiran harus berupa file: PDF, DOC, DOCX, JPG, JPEG, atau PNG.',
            'lampiran.*.max' => 'Ukuran setiap lampiran maksimal 5MB.',
        ]);

        $data = [
            'pengirim_id' => auth()->id(),
            'penerima_division_id' => $request->penerima_division_id,
            'jenis' => $request->jenis,
            'judul' => $request->judul,
            'isi' => $request->isi,
            'tanggal_pertemuan' => $request->tanggal_pertemuan,
            'jam_pertemuan' => $request->jam_pertemuan,
        ];

        if ($request->jenis === 'pertemuan_individu' && $request->filled('penerima_user_id')) {
            $data['penerima_user_id'] = $request->penerima_user_id;
        }

        $lampiranFiles = $this->prepareUploadedFiles($request, 'lampiran');
        $this->ensureTotalAttachmentsSize($lampiranFiles, 'lampiran');

        if (!empty($lampiranFiles)) {
            $data['lampiran'] = $this->storeAttachments($lampiranFiles, 'lampiran');
        }

        Letter::create($data);

        session(['petugas_letters_tab' => 'keluar']);
        return redirect()->route('surat_masuk.index')->with('success', 'Surat berhasil dibuat.');
    }

    public function show(Letter $letter)
    {
        $user = auth()->user();
        $divisionId = $user->division_id;
        
        // Petugas bisa melihat surat yang masuk ke divisinya atau surat keluar dari divisinya
        if (!$divisionId) {
            abort(403, 'Anda tidak memiliki akses untuk melihat surat ini.');
        }

        // Cek apakah surat masuk ke divisi petugas atau surat keluar dari divisi petugas
        $isSuratMasuk = $letter->penerima_division_id === $divisionId;
        $isSuratKeluar = $letter->pengirim_id && $letter->pengirim && $letter->pengirim->division_id === $divisionId;

        if (!$isSuratMasuk && !$isSuratKeluar) {
            abort(403, 'Anda tidak memiliki akses untuk melihat surat ini.');
        }
        
        $letter->load(['pengirim.division', 'penerimaDivision', 'penerimaUser', 'responder.division']);
        return view('petugas.letters.show', compact('letter'));
    }

    public function respond(Request $request, Letter $letter)
    {
        // Pastikan hanya bisa diakses via POST
        if (!request()->isMethod('post')) {
            abort(405, 'Method tidak diizinkan.');
        }

        $user = auth()->user();
        $divisionId = $user->division_id;
        
        // Pastikan petugas hanya bisa menanggapi surat yang masuk ke divisinya
        if (!$divisionId || $letter->penerima_division_id !== $divisionId) {
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
        
        // Petugas bisa download surat yang masuk ke divisinya ATAU surat yang dia kirim
        $isSuratMasuk = $divisionId && $letter->penerima_division_id === $divisionId;
        $isSuratKeluar = $letter->pengirim_id === $user->id;
        
        if (!$isSuratMasuk && !$isSuratKeluar) {
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

