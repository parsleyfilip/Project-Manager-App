<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here is where you can register the tenant routes for your application.
| These routes are loaded by the TenancyServiceProvider within a group which
| contains the "tenant" middleware group. Now create something great!
|
*/

// Tenant registration and selection routes (accessible without tenant)
Route::middleware('web')->group(function () {
    Route::get('/register', [TenantController::class, 'showRegistrationForm'])
        ->name('tenant.register');
    Route::post('/register', [TenantController::class, 'register']);
    
    Route::get('/select', [TenantController::class, 'showSelectForm'])
        ->name('tenant.select');
    Route::post('/select', [TenantController::class, 'select']);
});

// Tenant routes (require tenant context)
Route::middleware(['tenant', 'auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Projects
    Route::resource('projects', ProjectController::class);
    
    // Tasks
    Route::resource('tasks', TaskController::class);
    
    // Teams
    Route::resource('teams', TeamController::class);
    Route::post('/teams/{team}/members', [TeamController::class, 'addMember'])
        ->name('teams.members.add');
    Route::delete('/teams/{team}/members/{user}', [TeamController::class, 'removeMember'])
        ->name('teams.members.remove');
});
