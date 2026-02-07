<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use App\Models\User;
use App\Models\Division;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $divisionId = $user->division_id;

        // Jika tidak ada divisi, return 0 untuk semua statistik
        if (!$divisionId) {
            return view('admin.dashboard', [
                'totalSurat' => 0,
                'suratMasuk' => 0,
                'suratMasukPending' => 0,
                'suratMasukDiterima' => 0,
                'suratMasukDitolak' => 0,
                'suratKeluar' => 0,
                'suratKeluarPending' => 0,
                'suratKeluarDiterima' => 0,
                'suratKeluarDitolak' => 0,
                'suratPertemuanIndividu' => 0,
                'suratRapatKantor' => 0,
                'suratHariIni' => 0,
                'suratBulanIni' => 0,
                'totalUsers' => 0,
                'pendingUsers' => 0,
                'totalAdmin' => 0,
                'totalPetugas' => 0,
                'totalUserRole' => 0,
                'totalDivisions' => 0,
                'monthlyMasukData' => [],
                'monthlyKeluarData' => [],
                'monthlyLabels' => [],
            ]);
        }

        // Statistik Surat Masuk (yang diterima divisi)
        $suratMasuk = Letter::where('penerima_division_id', $divisionId)->count();
            
        $suratMasukPending = Letter::where('penerima_division_id', $divisionId)
            ->where('status', 'pending')
            ->count();
            
        $suratMasukDiterima = Letter::where('penerima_division_id', $divisionId)
            ->where('status', 'diterima')
            ->count();
            
        $suratMasukDitolak = Letter::where('penerima_division_id', $divisionId)
            ->where('status', 'ditolak')
            ->count();

        // Statistik Surat Keluar (yang dikirim oleh user dari divisi)
        $suratKeluar = Letter::whereHas('pengirim', function($query) use ($divisionId) {
            $query->where('division_id', $divisionId);
        })->count();

        $suratKeluarPending = Letter::whereHas('pengirim', function($query) use ($divisionId) {
            $query->where('division_id', $divisionId);
        })->where('status', 'pending')->count();

        $suratKeluarDiterima = Letter::whereHas('pengirim', function($query) use ($divisionId) {
            $query->where('division_id', $divisionId);
        })->where('status', 'diterima')->count();

        $suratKeluarDitolak = Letter::whereHas('pengirim', function($query) use ($divisionId) {
            $query->where('division_id', $divisionId);
        })->where('status', 'ditolak')->count();

        // Total Surat (Masuk + Keluar)
        $totalSurat = $suratMasuk + $suratKeluar;
            
        // Statistik Berdasarkan Jenis Surat
        $suratPertemuanIndividu = Letter::whereHas('pengirim', function($query) use ($divisionId) {
            $query->where('division_id', $divisionId);
        })->where('jenis', 'pertemuan_individu')->count();

        $suratRapatKantor = Letter::whereHas('pengirim', function($query) use ($divisionId) {
            $query->where('division_id', $divisionId);
        })->where('jenis', 'rapat_kantor')->count();

        // Statistik Waktu
        $suratHariIni = Letter::whereHas('pengirim', function($query) use ($divisionId) {
            $query->where('division_id', $divisionId);
        })->whereDate('created_at', today())->count();

        $suratBulanIni = Letter::whereHas('pengirim', function($query) use ($divisionId) {
            $query->where('division_id', $divisionId);
        })->whereYear('created_at', now()->year)
          ->whereMonth('created_at', now()->month)
          ->count();

        // Hanya users dari divisinya
        $totalUsers = User::where('division_id', $divisionId)->count();
        
        // User pending untuk approval
        $pendingUsers = User::where('division_id', $divisionId)
            ->where('status', 'pending')
            ->count();

        // User berdasarkan role
        $totalAdmin = User::where('division_id', $divisionId)
            ->where('role', 'admin')
            ->count();
        
        $totalPetugas = User::where('division_id', $divisionId)
            ->where('role', 'petugas')
            ->count();

        $totalUserRole = User::where('division_id', $divisionId)
            ->where('role', 'user')
            ->count();
        
        // Divisi sendiri
        $totalDivisions = 1;
        
        // Data untuk chart surat per bulan (6 bulan terakhir) - Surat Masuk
        $monthlyMasukData = [];
        $monthlyKeluarData = [];
        $monthlyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            
            // Surat masuk
            $countMasuk = Letter::where('penerima_division_id', $divisionId)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $monthlyMasukData[] = $countMasuk;

            // Surat keluar
            $countKeluar = Letter::whereHas('pengirim', function($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })->whereYear('created_at', $month->year)
              ->whereMonth('created_at', $month->month)
              ->count();
            $monthlyKeluarData[] = $countKeluar;
            
            $monthlyLabels[] = $month->format('M Y');
        }
        
        return view('admin.dashboard', compact(
            'totalSurat',
            'suratMasuk',
            'suratMasukPending',
            'suratMasukDiterima',
            'suratMasukDitolak',
            'suratKeluar',
            'suratKeluarPending',
            'suratKeluarDiterima',
            'suratKeluarDitolak',
            'suratPertemuanIndividu',
            'suratRapatKantor',
            'suratHariIni',
            'suratBulanIni',
            'totalUsers',
            'pendingUsers',
            'totalAdmin',
            'totalPetugas',
            'totalUserRole',
            'totalDivisions',
            'monthlyMasukData',
            'monthlyKeluarData',
            'monthlyLabels'
        ));
    }
}

