<?php

use Inertia\Inertia;
use App\Models\Domain;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\SpoofController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RescanController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
use App\Console\Commands\ScanSpoofingDomains;
use App\Http\Controllers\SpoofViewController;
use App\Http\Controllers\OrganizationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
//    Domain::create([
//        'user_id'=>2,
//        'domain_name'=>'google.com',
//    ]);
//    Artisan::call(ScanSpoofingDomains::class, ['--user_id' => 2]);
//     exit();
    return Inertia::render('Search', [ //fix the Welcome : Search
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'domainList' => Domain::where('user_id',auth()->id())->get()
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/search-domain', [SearchController::class, 'index'])->name('search.domain');
Route::post('/search-domain', [SearchController::class, 'index'])->name('search.domain');
Route::middleware('auth')->group(function () {
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/domain-search', [DomainController::class, 'index'])->name('domain.list');
 Route::get('/rescan-domain/{domainId}', [RescanController::class, 'rescan'])->name('rescan.domain');
 Route::get('/spoof/{domainId}', [SpoofController::class, 'spoof'])->name('spoof.domain');
 Route::get('/spoof/view/{spoofId}', [SpoofViewController::class, 'spoofView'])->name('spoof.view');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile-update', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile-update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile-change-password', [ProfileController::class, 'change_password'])->name('profile.change-password');
    Route::post('/profile-change-password', [ProfileController::class, 'update_password'])->name('profile.change-password');

    Route::get('/organization', [OrganizationController::class, 'index'])->name('organization');
    Route::post('/organization', [OrganizationController::class, 'store'])->name('organization');
    Route::post('/organization/cancel', [OrganizationController::class, 'cancel'])->name('organization.cancel');
    Route::post('/organization/activate', [OrganizationController::class, 'activate'])->name('organization.activate');
    Route::get('/organization/{domain}/{id}', [OrganizationController::class, 'view'])->name('organization.view');
    Route::get('/organization/{domain}/{id}/latest', [OrganizationController::class, 'view_latest'])->name('organization.view.latest');
    Route::post('/organization-search', [OrganizationController::class, 'search'])->name('organization.search');

    Route::get('/domain/{org_id}/{id}/{domain}', [DomainController::class, 'index'])->name('domain');
    Route::get('/domain', [DomainController::class, 'detail'])->name('domain.detail');

    Route::get('/report/{domain}/{id}', [ReportController::class, 'index'])->name('report');

    Route::middleware('is_admin')->group(function () {
        Route::get('/users', [UsersController::class, 'index'])->name('users.list');
        Route::get('/user/{user_id}', [UsersController::class, 'view'])->name('user.view');
        Route::post('/user', [UsersController::class, 'update'])->name('user.update');
        Route::post('/user/approve', [UsersController::class, 'approve'])->name('user.approve');
        Route::post('/user/lock', [UsersController::class, 'lock'])->name('user.lock');
        Route::get('/users/pending', [UsersController::class, 'pending'])->name('users.list.pending');
        Route::get('/user-create', [UsersController::class, 'create'])->name('user.create');
        Route::post('/user-create', [UsersController::class, 'store'])->name('user.create');
    });
});

require __DIR__ . '/auth.php';
