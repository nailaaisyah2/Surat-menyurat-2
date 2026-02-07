<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $divisionId = $user->division_id;

        // Jika tidak ada divisi, return 0 untuk semua statistik
        if (!$divisionId) {
            return view('petugas.dashboard', [
                'totalSuratMasuk' => 0,
                'suratMasuk' => 0,
                'suratDiterima' => 0,
                'suratDitolak' => 0,
                'suratPertemuanIndividu' => 0,
                'suratRapatKantor' => 0,
                'suratHariIni' => 0,
                'suratBulanIni' => 0,
                'pendingUsers' => 0,
                'monthlyData' => [],
                'monthlyLabels' => [],
            ]);
        }

        // Statistik Surat Masuk
        $totalSuratMasuk = Letter::where('penerima_division_id', $divisionId)->count();
        
        $suratMasuk = Letter::where('penerima_division_id', $divisionId)
            ->where('status', 'pending')
            ->count();
        $suratDiterima = Letter::where('penerima_division_id', $divisionId)
            ->where('status', 'diterima')
            ->count();
        $suratDitolak = Letter::where('penerima_division_id', $divisionId)
            ->where('status', 'ditolak')
            ->count();

        // Statistik Berdasarkan Jenis Surat (surat masuk)
        $suratPertemuanIndividu = Letter::where('penerima_division_id', $divisionId)
            ->where('jenis', 'pertemuan_individu')
            ->count();

        $suratRapatKantor = Letter::where('penerima_division_id', $divisionId)
            ->where('jenis', 'rapat_kantor')
            ->count();

        // Statistik Waktu
        $suratHariIni = Letter::where('penerima_division_id', $divisionId)
            ->whereDate('created_at', today())
            ->count();

        $suratBulanIni = Letter::where('penerima_division_id', $divisionId)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        
        // User pending untuk approval
        $pendingUsers = User::where('division_id', $divisionId)
            ->where('status', 'pending')
            ->count();
        
        // Data untuk chart surat per bulan (6 bulan terakhir)
        $monthlyData = [];
        $monthlyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Letter::where('penerima_division_id', $divisionId)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $monthlyData[] = $count;
            $monthlyLabels[] = $month->format('M Y');
        }
        
        return view('petugas.dashboard', compact(
            'totalSuratMasuk',
            'suratMasuk', 
            'suratDiterima', 
            'suratDitolak',
            'suratPertemuanIndividu',
            'suratRapatKantor',
            'suratHariIni',
            'suratBulanIni',
            'pendingUsers',
            'monthlyData',
            'monthlyLabels'
        ));
    }
}

