<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// Route untuk setup database di hosting (jalankan sekali lalu hapus)
Route::get('/setup-database', function() {
    try {
        Artisan::call('migrate:fresh', ['--seed' => true]);
        
        return "
            <h1>✅ Database Setup Success!</h1>
            <p>Semua akun default telah dibuat. Silakan hapus kembali route ini di <code>routes/web.php</code> demi keamanan.</p>
            <table border='1' cellpadding='10' style='border-collapse: collapse;'>
                <thead>
                    <tr style='background: #f4f4f4;'>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Superadmin</td><td>superadmin@siakad.test</td><td>password</td></tr>
                    <tr><td>Admin Fakultas</td><td>admin.ftik@siakad.test</td><td>password</td></tr>
                    <tr><td>Dosen</td><td>dosen@siakad.test</td><td>password</td></tr>
                    <tr><td>Mahasiswa</td><td>mahasiswa@siakad.test</td><td>password</td></tr>
                </tbody>
            </table>
            <br>
            <a href='/login' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Kembali ke Login</a>
        ";
    } catch (\Exception $e) {
        return "Error saat setup database: " . $e->getMessage();
    }
});

// Redirect root to login page
Route::get('/', fn() => redirect()->route('login'));

// Redirect generic dashboard to role-specific dashboard
Route::get('/dashboard', function () {
    $user = auth()->user();
    $routes = [
        'superadmin'     => 'admin.dashboard',
        'admin_fakultas' => 'admin.dashboard',
        'dosen'          => 'dosen.dashboard',
        'mahasiswa'      => 'mahasiswa.dashboard',
    ];
    $route = $routes[$user->role ?? ''] ?? null;

    return $route ? redirect()->route($route) : redirect()->route('login');
})->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__.'/auth.php';
require __DIR__.'/health.php';
require __DIR__.'/notification.php';
require __DIR__.'/profile.php';
require __DIR__.'/admin/index.php';
require __DIR__.'/dosen/index.php';
require __DIR__.'/mahasiswa/index.php';
