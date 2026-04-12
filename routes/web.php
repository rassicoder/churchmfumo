<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
Route::get('/church/login', [AuthController::class, 'showChurchLogin'])->name('church.login');
Route::post('/church/login', [AuthController::class, 'churchLogin'])->name('church.login.submit');

Route::get('/health', function () {
    return response()->json([
        'name' => config('app.name'),
        'status' => 'ok',
        'mode' => 'api-first'
    ]);
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/dashboard');

    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
    Route::view('/church-dashboard', 'admin.church-dashboard')->name('church-dashboard');
    Route::view('/church/leaders', 'admin.church.leaders')->name('church.leaders');
    Route::view('/church/leaders/create', 'admin.church.leaders-create')->name('church.leaders.create');
    Route::view('/church/meetings', 'admin.church.meetings')->name('church.meetings');
    Route::view('/church/meetings/create', 'admin.church.meetings-create')->name('church.meetings.create');
    Route::view('/church/projects', 'admin.church.projects')->name('church.projects');
    Route::view('/church/projects/create', 'admin.church.projects-create')->name('church.projects.create');
    Route::view('/church/reports', 'admin.church.reports')->name('church.reports');
    Route::view('/church/settings', 'admin.church.settings')->name('church.settings');
    Route::view('/churches', 'admin.churches.index')->name('churches');
    Route::view('/churches/create', 'admin.churches.create')->name('churches.create');
    Route::view('/churches/{id}/edit', 'admin.churches.edit')->name('churches.edit');
    Route::view('/leaders', 'admin.leaders.index')->name('leaders');
    Route::view('/leaders/create', 'admin.leaders.create')->name('leaders.create');
    Route::view('/leaders/{id}/edit', 'admin.leaders.edit')->name('leaders.edit');
    Route::view('/departments', 'admin.departments.index')->name('departments');
    Route::view('/departments/create', 'admin.departments.create')->name('departments.create');
    Route::view('/departments/{id}/edit', 'admin.departments.edit')->name('departments.edit');
    Route::view('/meetings', 'admin.meetings.index')->name('meetings');
    Route::view('/meetings/create', 'admin.meetings.create')->name('meetings.create');
    Route::view('/meetings/{id}/edit', 'admin.meetings.edit')->name('meetings.edit');
    Route::view('/projects', 'admin.projects.index')->name('projects');
    Route::view('/projects/create', 'admin.projects.create')->name('projects.create');
    Route::view('/projects/{id}/edit', 'admin.projects.edit')->name('projects.edit');
    Route::view('/finance', 'admin.finance.index')->name('finance');
    Route::view('/reports', 'admin.pages.reports')->name('reports');
    Route::view('/settings', 'admin.settings.index')->name('settings');
});
