<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\SuratController as AdminSuratController;
use App\Http\Controllers\Petugas\SuratController as PetugasSuratController;
use App\Http\Controllers\User\SuratController as UserSuratController;
use App\Models\Letter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $role = strtolower(trim($user->role ?? ''));
        
        if ($role === 'admin') {
            return (new AdminSuratController())->index($request);
        } elseif ($role === 'petugas') {
            return (new PetugasSuratController())->index($request);
        } else {
            return (new UserSuratController())->index($request);
        }
    }

    public function create()
    {
        $user = auth()->user();
        $role = strtolower(trim($user->role ?? ''));
        
        if ($role === 'admin') {
            return (new AdminSuratController())->create();
        } elseif ($role === 'petugas') {
            return (new PetugasSuratController())->create();
        } else {
            return (new UserSuratController())->create();
        }
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $role = strtolower(trim($user->role ?? ''));
        
        if ($role === 'admin') {
            return (new AdminSuratController())->store($request);
        } elseif ($role === 'petugas') {
            return (new PetugasSuratController())->store($request);
        } else {
            return (new UserSuratController())->store($request);
        }
    }

    public function show(Letter $letter)
    {
        $user = auth()->user();
        $role = strtolower(trim($user->role ?? ''));
        
        if ($role === 'admin') {
            return (new AdminSuratController())->show($letter);
        } elseif ($role === 'petugas') {
            return (new PetugasSuratController())->show($letter);
        } else {
            return (new UserSuratController())->show($letter);
        }
    }

    public function respond(Request $request, Letter $letter)
    {
        $user = auth()->user();
        $role = strtolower(trim($user->role ?? ''));
        
        if ($role === 'admin') {
            return (new AdminSuratController())->respond($request, $letter);
        } elseif ($role === 'petugas') {
            return (new PetugasSuratController())->respond($request, $letter);
        } else {
            return (new UserSuratController())->respond($request, $letter);
        }
    }

    public function downloadLampiran(Letter $letter)
    {
        $user = auth()->user();
        $role = strtolower(trim($user->role ?? ''));
        
        if ($role === 'admin') {
            return (new AdminSuratController())->downloadLampiran($letter);
        } elseif ($role === 'petugas') {
            return (new PetugasSuratController())->downloadLampiran($letter);
        } else {
            return (new UserSuratController())->downloadLampiran($letter);
        }
    }
}
