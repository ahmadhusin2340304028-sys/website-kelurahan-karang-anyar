<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\{
    HomeController,
    NewsController,
    UmkmController,
    SearchController,
    GalleryController,
    ProfileController,
};
use App\Http\Controllers\Admin\{
    DashboardController,
    PostController,
    CategoryController,
    OfficialController,
    GalleryController as AdminGalleryController,
    AnnouncementController,
    UmkmController as AdminUmkmController,
    OrganizationController,
    SettingController,
};
use App\Http\Controllers\Auth\AdminAuthController;

// ══════════════════════════════════════════════════════════
// PUBLIC ROUTES
// ══════════════════════════════════════════════════════════

Route::get('/', [HomeController::class, 'index'])->name('home');

// Profil & Informasi
Route::get('/profil', [ProfileController::class, 'index'])->name('profile');
Route::get('/profil/sejarah', [ProfileController::class, 'history'])->name('profile.history');
Route::get('/profil/visi-misi', [ProfileController::class, 'visionMission'])->name('profile.vision');
Route::get('/profil/struktur-organisasi', [ProfileController::class, 'organization'])->name('profile.organization');
Route::get('/profil/pejabat', [ProfileController::class, 'officials'])->name('profile.officials');
Route::get('/profil/kontak', [ProfileController::class, 'contact'])->name('profile.contact');

// Berita
Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');

// Galeri
Route::get('/galeri', [\App\Http\Controllers\Public\GalleryController::class, 'index'])->name('gallery.index');

// UMKM
Route::get('/umkm', [UmkmController::class, 'index'])->name('umkm.index');
Route::get('/umkm/daftar', [UmkmController::class, 'create'])->name('umkm.create');
Route::post('/umkm/daftar', [UmkmController::class, 'store'])
    ->name('umkm.store')
    ->middleware('throttle:5,1'); // Rate limit: 5 per minute
Route::get('/umkm/berhasil', [UmkmController::class, 'success'])->name('umkm.success');

// Pencarian
Route::get('/pencarian', [SearchController::class, 'index'])->name('search');

// ══════════════════════════════════════════════════════════
// ADMIN AUTH ROUTES (no guard prefix)
// ══════════════════════════════════════════════════════════

Route::prefix('admin')->name('admin.')->group(function () {
    // Login (guest only)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    });

    // Logout
    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('logout')
        ->middleware('auth');

    // Protected admin routes
    Route::middleware('auth')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Berita
        Route::resource('posts', PostController::class);
        Route::delete('posts/images/{image}', [PostController::class, 'destroyImage'])->name('posts.images.destroy');

        // Kategori
        Route::resource('categories', CategoryController::class);

        // Pejabat
        Route::resource('officials', OfficialController::class);

        // Galeri
        Route::resource('galleries', AdminGalleryController::class);

        // Pengumuman
        Route::resource('announcements', AnnouncementController::class);

        // UMKM Moderasi
        Route::get('umkms', [AdminUmkmController::class, 'index'])->name('umkms.index');
        Route::get('umkms/{umkm}', [AdminUmkmController::class, 'show'])->name('umkms.show');
        Route::post('umkms/{umkm}/approve', [AdminUmkmController::class, 'approve'])->name('umkms.approve');
        Route::post('umkms/{umkm}/reject', [AdminUmkmController::class, 'reject'])->name('umkms.reject');
        Route::delete('umkms/{umkm}', [AdminUmkmController::class, 'destroy'])->name('umkms.destroy');

        // Struktur Organisasi
        Route::resource('organization', OrganizationController::class);

        // Pengaturan
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});

// SEO: sitemap & robots
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index']);
Route::get('/robots.txt', [\App\Http\Controllers\RobotsController::class, 'index']);
