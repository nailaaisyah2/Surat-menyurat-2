<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Division;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Hanya tolak query yang tidak diperbolehkan.
        // Sebelumnya kode men-`abort(404)` jika ada query apapun,
        // yang menyebabkan akses seperti `/users?role_filter=admin` menghasilkan 404.
        // Sekarang izinkan query untuk `role_filter` dan `search` dan simpan ke session.
        $allowedQuery = ['role_filter', 'search'];
        if ($request->query->except($allowedQuery)->count() > 0) {
            abort(404);
        }

        // Jika ada filter melalui GET, simpan ke session sehingga mekanisme filter lama tetap bekerja.
        if ($request->query->hasAny($allowedQuery)) {
            $role = $request->query('role_filter');
            $search = $request->query('search');
            $data = [];

            if ($role !== null && in_array($role, ['admin', 'petugas', 'user'])) {
                $data['role_filter'] = $role;
            }

            if ($search !== null) {
                $data['search'] = mb_substr($search, 0, 255);
            }

            if (!empty($data)) {
                session(['admin_users_filters' => $data]);
            }
        }

        $user = auth()->user();
        $divisionId = $user->division_id;

        $filters = session('admin_users_filters', []);

        $query = User::with('division');

        $roleFilter = $filters['role_filter'] ?? null;
        $search = $filters['search'] ?? null;

        // Selalu filter berdasarkan divisi admin yang sedang login (tidak peduli role filter)
        // Admin hanya bisa melihat user dari divisinya sendiri
        if ($divisionId) {
            $query->where('division_id', $divisionId);
        } else {
            // Jika admin tidak punya divisi, tampilkan user yang juga tidak punya divisi
            $query->whereNull('division_id');
        }

        // Filter by role
        if (!empty($roleFilter)) {
            $query->where('role', $roleFilter);
        }

        // Search by name or email
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->get();
        
        return view('admin.users.index', compact('users', 'filters', 'divisionId'));
    }

    public function applyFilters(Request $request)
    {
        // Pastikan hanya bisa diakses via POST
        if (!request()->isMethod('post')) {
            abort(405, 'Method tidak diizinkan.');
        }

        $data = $request->validate([
            'role_filter' => 'nullable|in:admin,petugas,user',
            'search' => 'nullable|string|max:255',
        ]);

        session(['admin_users_filters' => $data]);

        return redirect()->route('users.index');
    }

    public function resetFilters()
    {
        // Pastikan hanya bisa diakses via POST
        if (!request()->isMethod('post')) {
            abort(405, 'Method tidak diizinkan.');
        }

        session()->forget('admin_users_filters');
        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        $userAuth = auth()->user();
        $divisionId = $userAuth->division_id;
        
        // Pastikan user yang dilihat adalah dari divisi admin
        if (!$divisionId || $user->division_id !== $divisionId) {
            abort(403, 'Anda tidak memiliki akses untuk melihat user ini.');
        }

        $user->load(['division', 'lettersSent', 'lettersResponded', 'divisionsCreated']);
        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        // Admin bisa membuat user untuk divisi manapun (termasuk divisinya sendiri atau divisi lain)
        $divisions = Division::all()->sortBy('name');
        return view('admin.users.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        // Pastikan hanya bisa diakses via POST
        if (!request()->isMethod('post')) {
            abort(405, 'Method tidak diizinkan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,petugas,user',
            'division_id' => 'required|exists:divisions,id',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ], [
            'email.unique' => 'Email sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
            'name.required' => 'Nama wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
            'division_id.required' => 'Divisi wajib dipilih.',
            'profile_image.image' => 'File harus berupa gambar.',
            'profile_image.mimes' => 'Format gambar harus: JPEG, JPG, PNG, atau GIF.',
            'profile_image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->role === 'admin') {
            $existingAdmin = User::where('division_id', $request->division_id)
                ->where('role', 'admin')
                ->exists();

            if ($existingAdmin) {
                return back()
                    ->withErrors(['role' => 'Slot admin untuk divisi ini sudah terisi.'])
                    ->withInput($request->except('password', 'password_confirmation'));
            }
        }

        // Prepare data for user creation
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'division_id' => $request->division_id, // Bisa pilih divisi manapun
            'status' => 'approved', // Langsung approved karena dibuat admin
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = 'profile_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('profile_images', $imageName, 'public');
            $data['profile_image'] = 'storage/' . $imagePath;
        }

        // Admin bisa membuat user untuk divisi manapun (termasuk divisi lain untuk membuat admin divisi lain)
        // Admin langsung approve karena dibuat oleh admin
        User::create($data);

        $divisionName = Division::find($request->division_id)->name;
        return redirect()->route('users.index')
            ->with('success', "User berhasil dibuat untuk divisi {$divisionName}.");
    }

    public function edit(User $user)
    {
        $userAuth = auth()->user();
        $divisionId = $userAuth->division_id;
        
        // Pastikan user yang diedit adalah dari divisi admin
        if (!$divisionId || $user->division_id !== $divisionId) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit user ini.');
        }

        // Admin hanya bisa edit user dari divisinya
        $divisions = Division::where('id', $divisionId)->get();
        return view('admin.users.edit', compact('user', 'divisions'));
    }

    public function update(Request $request, User $user)
    {
        // Pastikan hanya bisa diakses via PUT/PATCH
        if (!in_array(request()->method(), ['PUT', 'PATCH', 'POST'])) {
            abort(405, 'Method tidak diizinkan.');
        }

        $userAuth = auth()->user();
        $divisionId = $userAuth->division_id;
        
        // Pastikan user yang diupdate adalah dari divisi admin
        if (!$divisionId || $user->division_id !== $divisionId) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate user ini.');
        }

        // Cegah perubahan role jika user yang diedit adalah admin
        if ($user->role === 'admin' && $request->role !== 'admin') {
            return back()
                ->with('error', 'Status role admin sudah default dan tidak bisa diubah.')
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Cegah petugas atau user menjadi admin (karena sudah ada admin di divisi)
        if ($user->role !== 'admin' && $request->role === 'admin') {
            return back()
                ->with('error', 'Tidak dapat mengubah role menjadi admin. Admin sudah ada di divisi ini.')
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,petugas,user',
        ], [
            'email.unique' => 'Email sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
            'name.required' => 'Nama wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'division_id' => $divisionId, // Tetap divisi admin, tidak bisa diubah
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Pastikan hanya bisa diakses via DELETE
        if (!in_array(request()->method(), ['DELETE', 'POST'])) {
            abort(405, 'Method tidak diizinkan.');
        }

        $userAuth = auth()->user();
        $divisionId = $userAuth->division_id;
        
        // Pastikan user yang dihapus adalah dari divisi admin
        if (!$divisionId || $user->division_id !== $divisionId) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus user ini.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    public function pendingUsers()
    {
        $user = auth()->user();
        $divisionId = $user->division_id;

        // Jika tidak ada divisi, return empty
        if (!$divisionId) {
            return view('admin.users.pending', ['pendingUsers' => collect()]);
        }

        // Hanya user pending dari divisinya sendiri
        $pendingUsers = User::with('division')
            ->where('division_id', $divisionId)
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.users.pending', compact('pendingUsers'));
    }

    public function approve(User $user)
    {
        // Pastikan hanya bisa diakses via POST
        if (!request()->isMethod('post')) {
            abort(405, 'Method tidak diizinkan.');
        }

        $userAuth = auth()->user();
        $divisionId = $userAuth->division_id;
        
        // Pastikan user yang di-approve adalah dari divisi admin
        if (!$divisionId || $user->division_id !== $divisionId) {
            abort(403, 'Anda tidak memiliki akses untuk menyetujui user ini.');
        }

        $user->update(['status' => 'approved']);

        return redirect()->route('users.pending')
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
        
        // Pastikan user yang di-reject adalah dari divisi admin
        if (!$divisionId || $user->division_id !== $divisionId) {
            abort(403, 'Anda tidak memiliki akses untuk menolak user ini.');
        }

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $user->update(['status' => 'rejected']);

        return redirect()->route('users.pending')
            ->with('success', "User {$user->name} telah ditolak.");
    }
}

