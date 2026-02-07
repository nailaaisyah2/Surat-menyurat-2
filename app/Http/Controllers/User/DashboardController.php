<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Letter;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $suratSaya = Letter::where('pengirim_id', $user->id)->count();
        $suratPending = Letter::where('pengirim_id', $user->id)
            ->where('status', 'pending')
            ->count();
        $suratDiterima = Letter::where('pengirim_id', $user->id)
            ->where('status', 'diterima')
            ->count();
        $suratDitolak = Letter::where('pengirim_id', $user->id)
            ->where('status', 'ditolak')
            ->count();

        // Statistik Berdasarkan Jenis Surat
        $suratPertemuanIndividu = Letter::where('pengirim_id', $user->id)
            ->where('jenis', 'pertemuan_individu')
            ->count();

        $suratRapatKantor = Letter::where('pengirim_id', $user->id)
            ->where('jenis', 'rapat_kantor')
            ->count();

        // Statistik Waktu
        $suratHariIni = Letter::where('pengirim_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        $suratBulanIni = Letter::where('pengirim_id', $user->id)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        // Data untuk chart surat per bulan (6 bulan terakhir)
        $monthlyData = [];
        $monthlyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Letter::where('pengirim_id', $user->id)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $monthlyData[] = $count;
            $monthlyLabels[] = $month->format('M Y');
        }
        
        return view('user.dashboard', compact(
            'suratSaya', 
            'suratPending', 
            'suratDiterima', 
            'suratDitolak',
            'suratPertemuanIndividu',
            'suratRapatKantor',
            'suratHariIni',
            'suratBulanIni',
            'monthlyData',
            'monthlyLabels'
        ));
    }
}

