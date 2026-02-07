<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Division;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $divisions = Division::all();
        return view('profile.show', compact('user', 'divisions'));
    }

    public function update(Request $request)
    {
        // Pastikan hanya bisa diakses via PUT/PATCH
        if (!in_array(request()->method(), ['PUT', 'PATCH', 'POST'])) {
            abort(405, 'Method tidak diizinkan.');
        }

        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'division_id' => 'nullable|exists:divisions,id',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ], [
            'email.unique' => 'Email sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
            'name.required' => 'Nama wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'profile_image.image' => 'File harus berupa gambar.',
            'profile_image.mimes' => 'Format gambar harus: JPEG, JPG, PNG, atau GIF.',
            'profile_image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'division_id' => $request->division_id ?: null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Hapus foto profil lama jika ada
            if ($user->profile_image) {
                // Hapus prefix 'storage/' jika ada
                $oldImagePath = str_replace('storage/', '', $user->profile_image);
                if (Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            }

            // Simpan foto profil baru
            $image = $request->file('profile_image');
            $imageName = 'profile_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('profile_images', $imageName, 'public');
            // Simpan path relatif untuk digunakan dengan asset()
            $data['profile_image'] = 'storage/' . $imagePath;
        }

        $user->update($data);
        auth()->setUser($user->fresh());

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}

