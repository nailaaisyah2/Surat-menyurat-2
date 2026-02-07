<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;

class DivisiController extends Controller
{
    public function index()
    {
        // Admin bisa melihat semua divisi
        $divisions = Division::with('creator')->latest()->get();
        return view('admin.divisi.index', compact('divisions'));
    }

    public function create()
    {
        return view('admin.divisi.create');
    }

    public function store(Request $request)
    {
        // Pastikan hanya bisa diakses via POST
        if (!request()->isMethod('post')) {
            abort(405, 'Method tidak diizinkan.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name',
        ]);

        Division::create([
            'name' => $request->name,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil dibuat.');
    }

    public function show(Division $division)
    {
        // Admin bisa melihat semua divisi
        $division->load(['creator', 'users', 'letters']);
        return view('admin.divisi.show', compact('division'));
    }

    public function edit(Division $division)
    {
        // Admin bisa edit semua divisi
        return view('admin.divisi.edit', compact('division'));
    }

    public function update(Request $request, Division $division)
    {
        // Pastikan hanya bisa diakses via PUT/PATCH
        if (!in_array(request()->method(), ['PUT', 'PATCH', 'POST'])) {
            abort(405, 'Method tidak diizinkan.');
        }

        // Admin bisa update semua divisi
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name,' . $division->id,
        ]);

        $division->update([
            'name' => $request->name,
        ]);

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Division $division)
    {
        // Pastikan hanya bisa diakses via DELETE
        if (!in_array(request()->method(), ['DELETE', 'POST'])) {
            abort(405, 'Method tidak diizinkan.');
        }

        // Admin bisa hapus semua divisi
        // Jangan izinkan hapus divisi jika masih ada users
        if ($division->users()->count() > 0) {
            return redirect()->route('divisions.index')
                ->with('error', 'Divisi tidak dapat dihapus karena masih memiliki anggota.');
        }

        $division->delete();
        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil dihapus.');
    }
}

