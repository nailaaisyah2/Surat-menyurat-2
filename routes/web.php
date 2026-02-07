<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutentikasiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DivisiController as AdminDivisiController;
use App\Http\Controllers\Admin\SuratController as AdminSuratController;
use App\Http\Controllers\Petugas\SuratController as PetugasSuratController;
use App\Http\Controllers\Petugas\UserController as PetugasUserController;
use App\Http\Controllers\User\SuratController as UserSuratController;
use App\Http\Controllers\User\DivisiController as UserDivisiController;

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AutentikasiController::class, 'showLogin'])->name('login');
    Route::post('/login', [AutentikasiController::class, 'login']);
    Route::get('/register', [AutentikasiController::class, 'showRegister'])->name('register');
    Route::post('/register', [AutentikasiController::class, 'register']);
    Route::get('/register/pending', [AutentikasiController::class, 'showPending'])->name('register.pending');
});

Route::post('/logout', [AutentikasiController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Profile Routes (semua role bisa akses)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Activity Logs Routes (hanya admin yang bisa akses, hanya melihat aktivitas di divisinya)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity_logs.index');
});

// Surat Masuk Routes - Semua role bisa akses
Route::middleware('auth')->group(function () {
    // Route utama - menggunakan controller yang route berdasarkan role
    Route::get('/surat_masuk', [SuratMasukController::class, 'index'])->name('surat_masuk.index');
    Route::get('/surat_masuk/create', [SuratMasukController::class, 'create'])->name('surat_masuk.create');
    Route::post('/surat_masuk', [SuratMasukController::class, 'store'])->name('surat_masuk.store');
    Route::get('/surat_masuk/{letter}', [SuratMasukController::class, 'show'])->name('surat_masuk.show');
    Route::post('/surat_masuk/{letter}/respond', [SuratMasukController::class, 'respond'])->name('surat_masuk.respond');
    Route::get('/surat_masuk/{letter}/download', [SuratMasukController::class, 'downloadLampiran'])->name('surat_masuk.download');
    
    // Tab routes untuk setiap role
    Route::middleware('role:user')->group(function () {
        Route::post('/surat_masuk/tab/user', [UserSuratController::class, 'setTab'])->name('user.surat_masuk.tab');
    });
    
    Route::middleware('role:petugas')->group(function () {
        Route::post('/surat_masuk/tab/petugas', [PetugasSuratController::class, 'setTab'])->name('petugas.surat_masuk.tab');
    });
    
    Route::middleware('role:admin')->group(function () {
        Route::post('/surat_masuk/tab/admin', [AdminSuratController::class, 'setTab'])->name('admin.surat_masuk.tab');
    });
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Users CRUD
    Route::post('/users/filter', [AdminUserController::class, 'applyFilters'])->name('users.filter.apply');
    Route::post('/users/filter/reset', [AdminUserController::class, 'resetFilters'])->name('users.filter.reset');
    Route::resource('users', AdminUserController::class);
    Route::get('/users/pending/approval', [AdminUserController::class, 'pendingUsers'])->name('users.pending');
    Route::post('/users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/reject', [AdminUserController::class, 'reject'])->name('users.reject');
    
    // Divisions CRUD (Admin bisa create, edit, dan manage divisi)
    Route::resource('divisions', AdminDivisiController::class);
});

// Divisions Routes - User (bisa lihat dan buat)
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/divisions', [UserDivisiController::class, 'index'])->name('divisions.index');
    Route::get('/divisions/{division}', [UserDivisiController::class, 'show'])->name('divisions.show');
    Route::get('/divisions/create', [UserDivisiController::class, 'create'])->name('divisions.create');
    Route::post('/divisions', [UserDivisiController::class, 'store'])->name('divisions.store');
});

// Petugas Routes - User Approval
Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/petugas/users/pending', [PetugasUserController::class, 'pendingUsers'])->name('petugas.users.pending');
    Route::post('/petugas/users/{user}/approve', [PetugasUserController::class, 'approve'])->name('petugas.users.approve');
    Route::post('/petugas/users/{user}/reject', [PetugasUserController::class, 'reject'])->name('petugas.users.reject');
});

// API Route untuk mendapatkan users berdasarkan division_id
Route::middleware('auth')->get('/api/users-by-division/{divisionId}', function ($divisionId) {
    try {
        // Tampilkan user yang bisa digunakan untuk pertemuan individu:
        // 1. User dengan status 'approved', ATAU
        // 2. Admin dan petugas (tidak perlu status karena mereka bisa login tanpa approval)
        $users = \App\Models\User::where('division_id', $divisionId)
            ->where(function($query) {
                $query->where(function($q) {
                    // User dengan status approved
                    $q->where('status', 'approved');
                })->orWhere(function($q) {
                    // Admin dan petugas (tidak peduli status karena mereka tidak perlu approval untuk login)
                    $q->whereIn('role', ['admin', 'petugas']);
                });
            })
            ->select('id', 'name', 'email', 'role', 'status')
            ->orderBy('name')
            ->get();
        
        return response()->json($users);
    } catch (\Exception $e) {
        \Log::error('Error loading users by division: ' . $e->getMessage());
        return response()->json(['error' => 'Gagal memuat data user: ' . $e->getMessage()], 500);
    }
})->name('api.users-by-division');

// Welcome page
Route::get('/', function () {
    return view('welcome');
});
