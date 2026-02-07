<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Division;
use App\Traits\LogsActivity;

class AutentikasiController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Pastikan hanya bisa diakses via POST
        if (!request()->isMethod('post')) {
            abort(405, 'Method tidak diizinkan.');
        }

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Cek status user (hanya untuk role user, admin dan petugas tidak perlu cek)
            if ($user->role === 'user') {
                if ($user->status === 'pending') {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Akun Anda masih menunggu persetujuan dari admin/petugas divisi Anda.',
                    ])->withInput($request->only('email'));
                }
                
                if ($user->status === 'rejected') {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Akun Anda telah ditolak. Silakan hubungi admin divisi Anda.',
                    ])->withInput($request->only('email'));
                }
            }
            
            // Log aktivitas login
            \App\Traits\LogsActivity::logCustomActivity('login', 'Login ke sistem');
            
            // Jika status null atau approved, atau role admin/petugas, bisa login
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    public function showRegister()
    {
        $divisions = Division::all();
        return view('auth.register', compact('divisions'));
    }

    public function register(Request $request)
    {
        // Pastikan hanya bisa diakses via POST
        if (!request()->isMethod('post')) {
            abort(405, 'Method tidak diizinkan.');
        }

        // Cek apakah email sudah pernah digunakan (untuk mencegah lebih dari 1 akun)
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return back()
                ->withErrors(['email' => 'Email sudah digunakan. Anda tidak dapat membuat lebih dari 1 akun.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'division_id' => 'nullable|exists:divisions,id',
            'new_division' => 'nullable|string|max:255|required_without:division_id',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ], [
            'email.unique' => 'Email sudah digunakan. Anda tidak dapat membuat lebih dari 1 akun.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
            'name.required' => 'Nama wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'profile_image.image' => 'File harus berupa gambar.',
            'profile_image.mimes' => 'Format gambar harus: JPEG, JPG, PNG, atau GIF.',
            'profile_image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Jika membuat divisi baru
        $divisionId = $request->division_id;
        if ($request->filled('new_division')) {
            $division = Division::create([
                'name' => $request->new_division,
                'created_by' => null, // akan diisi setelah user dibuat
            ]);
            $divisionId = $division->id;
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'division_id' => $divisionId,
            'status' => 'pending', // Status pending menunggu approval
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = 'profile_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('profile_images', $imageName, 'public');
            $data['profile_image'] = 'storage/' . $imagePath;
        }

        $user = User::create($data);

        // Update created_by jika membuat divisi baru
        if ($request->filled('new_division')) {
            $division->update(['created_by' => $user->id]);
        }

        // Tidak langsung login, harus menunggu approval dulu
        return redirect()->route('register.pending')->with('success', 'Registrasi berhasil! Akun Anda sedang menunggu persetujuan dari admin/petugas divisi Anda.');
    }

    public function logout(Request $request)
    {
        // Pastikan hanya bisa diakses via POST
        if (!request()->isMethod('post')) {
            abort(405, 'Method tidak diizinkan.');
        }

        // Log aktivitas logout sebelum logout
        if (Auth::check()) {
            \App\Traits\LogsActivity::logCustomActivity('logout', 'Logout dari sistem');
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showPending()
    {
        return view('auth.pending');
    }
}

