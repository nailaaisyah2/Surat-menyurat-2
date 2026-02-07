<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Normalisasi role untuk menghindari masalah case sensitivity
        $role = strtolower(trim($user->role ?? ''));
        
        if ($role === 'admin') {
            $controller = new AdminDashboardController();
            return $controller->index();
        } elseif ($role === 'petugas') {
            $controller = new PetugasDashboardController();
            return $controller->index();
        } else {
            $controller = new UserDashboardController();
            return $controller->index();
        }
    }
}

