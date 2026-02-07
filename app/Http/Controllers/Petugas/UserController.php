<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function pendingUsers()
    {
        $user = auth()->user();
        $divisionId = $user->division_id;

        // Jika tidak ada divisi, return empty
        if (!$divisionId) {
            return view('petugas.users.pending', ['pendingUsers' => collect()]);
        }

        // Hanya user pending dari divisinya sendiri
        $pendingUsers = User::with('division')
            ->where('division_id', $divisionId)
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('petugas.users.pending', compact('pendingUsers'));
    }

    public function approve(User $user)
    {
        // Pastikan hanya bisa diakses via POST
        if (!request()->isMethod('post')) {
            abort(405, 'Method tidak diizinkan.');
        }

        $userAuth = auth()->user();
        $divisionId = $userAuth->division_id;
        
        // Pastikan user yang di-approve adalah dari divisi petugas
        if (!$divisionId || $user->division_id !== $divisionId) {
            abort(403, 'Anda tidak memiliki akses untuk menyetujui user ini.');
        }

        $user->update(['status' => 'approved']);

        return redirect()->route('petugas.users.pending')
            ->with('success', "User {$user->name} berhasil disetujui.");
    }

    public function reject(Request $request, User $user)
    {
        // Pastikan hanya bisa diakses via POST
        if (!request()->isMethod('post')) {
            abort(405, 'Method tidak diizinkan.');
        }

        $userAuth = auth()->user();
        $divisionId = $userAuth->division_id;
        
        // Pastikan user yang di-reject adalah dari divisi petugas
        if (!$divisionId || $user->division_id !== $divisionId) {
            abort(403, 'Anda tidak memiliki akses untuk menolak user ini.');
        }

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $user->update(['status' => 'rejected']);

        return redirect()->route('petugas.users.pending')
            ->with('success', "User {$user->name} telah ditolak.");
    }
}

