<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;

class DivisiController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user->division_id) {
            return view('user.divisi.index', ['divisions' => collect()]);
        }
        $divisions = Division::with('creator')->where('id', $user->division_id)->latest()->get();
        return view('user.divisi.index', compact('divisions'));
    }

    public function create()
    {
        return view('user.divisi.create');
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
        $currentDivisionId = auth()->user()->division_id;

        if ($currentDivisionId !== $division->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat divisi ini.');
        }

        $division->load(['creator', 'users', 'letters']);
        return view('user.divisi.show', compact('division'));
    }
}

