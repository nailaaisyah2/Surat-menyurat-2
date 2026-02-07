<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Division;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat divisi default
        $division1 = Division::create([
            'name' => 'Divisi TIK',
            'created_by' => null,
        ]);

        $division2 = Division::create([
            'name' => 'Divisi Radio',
            'created_by' => null,
        ]);

        $division3 = Division::create([
            'name' => 'Divisi Keuangan',
            'created_by' => null,
        ]);

        // Buat akun admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345'),
            'role' => 'admin',
            'division_id' => $division1->id,
            'status' => 'approved', // Default user dari seeder langsung approved
        ]);

        // Update created_by divisi yang dibuat admin
        $division1->update(['created_by' => $admin->id]);

        // Buat akun petugas
        $petugas = User::create([
            'name' => 'Petugas',
            'email' => 'petugas@gmail.com',
            'password' => Hash::make('12345'),
            'role' => 'petugas',
            'division_id' => $division2->id,
            'status' => 'approved', // Default user dari seeder langsung approved
        ]);

        $division2->update(['created_by' => $petugas->id]);

        // Buat akun user
        $user = User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('12345'),
            'role' => 'user',
            'division_id' => $division3->id,
            'status' => 'approved', // Default user dari seeder langsung approved
        ]);

        $division3->update(['created_by' => $user->id]);
    }
}
