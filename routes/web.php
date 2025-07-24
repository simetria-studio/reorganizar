<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SystemController;

// Página inicial
Route::get('/', function () {
    return view('welcome');
});

// Rotas de autenticação
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rotas para usuários
    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::patch('/users/{user}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');
    Route::patch('/users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions');
    Route::get('/api/users', [UserController::class, 'apiIndex'])->name('users.api');

    // Rotas para sistema
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('/dashboard', [SystemController::class, 'dashboard'])->name('dashboard');
        Route::get('/settings', [SystemController::class, 'settings'])->name('settings');
        Route::get('/logs', [SystemController::class, 'logs'])->name('logs');
        Route::get('/info', [SystemController::class, 'info'])->name('info');
        Route::post('/backup', [SystemController::class, 'backup'])->name('backup');
        Route::get('/backups', [SystemController::class, 'listBackups'])->name('backups');
        Route::get('/backups/{filename}/download', [SystemController::class, 'downloadBackup'])->name('backups.download');
        Route::delete('/backups/{filename}', [SystemController::class, 'deleteBackup'])->name('backups.delete');
        Route::post('/cache/clear', [SystemController::class, 'clearCache'])->name('cache.clear');
        Route::match(['get', 'post'], '/maintenance', [SystemController::class, 'maintenance'])->name('maintenance');
    });

    // Rotas para escolas
    Route::resource('schools', SchoolController::class);
    Route::patch('/schools/{school}/toggle-active', [SchoolController::class, 'toggleActive'])->name('schools.toggle-active');

    // Rotas para alunos
    Route::resource('students', StudentController::class);
    Route::patch('/students/{student}/toggle-status', [StudentController::class, 'toggleStatus'])->name('students.toggle-status');
    Route::get('/api/schools/{school}/students', [StudentController::class, 'getBySchool'])->name('students.by-school');

    // Rotas para certificados
    Route::resource('certificates', CertificateController::class);
    Route::get('/certificates/{certificate}/preview', [CertificateController::class, 'preview'])->name('certificates.preview');
    Route::get('/certificates/view-pdf/{certificate?}', [CertificateController::class, 'viewPdf'])->name('certificates.view-pdf');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'downloadPDF'])->name('certificates.download');
    Route::patch('/certificates/{certificate}/cancel', [CertificateController::class, 'cancel'])->name('certificates.cancel');
    Route::post('/certificates/{certificate}/reissue', [CertificateController::class, 'reissue'])->name('certificates.reissue');
    Route::get('/api/schools/{school}/students-graduated', [CertificateController::class, 'getStudentsBySchool'])->name('certificates.students-by-school');

    // Rotas para notas
    Route::resource('notes', NoteController::class);
    Route::get('/api/students/search', [NoteController::class, 'searchStudents'])->name('notes.search-students');

    // Rotas para histórico escolar
    Route::get('/historical-reports', [NoteController::class, 'historicalIndex'])->name('historical-reports.index');

    // Rotas para relatórios de estudantes
    Route::prefix('students/{student}')->group(function () {
        Route::get('/report', [NoteController::class, 'studentReport'])->name('notes.student-report');
        Route::get('/historical-report', [NoteController::class, 'historicalReport'])->name('notes.historical-report');
        Route::get('/historical-report/pdf', [NoteController::class, 'downloadHistoricalPdf'])->name('notes.historical-report.pdf');
    });

    // Rotas para certificados em formato landscape
    Route::get('/certificates/{id}/landscape', [CertificateController::class, 'showLandscape'])->name('certificates.landscape');
    Route::get('/certificates/{id}/landscape/pdf', [CertificateController::class, 'downloadLandscapePdf'])->name('certificates.landscape.pdf');
    Route::get('/certificates/{id}/landscape/stream', [CertificateController::class, 'streamLandscape'])->name('certificates.landscape.stream');
});

// Rotas públicas para verificação
Route::get('/verificar-certificado', [CertificateController::class, 'verify'])->name('certificates.verify');
Route::post('/verificar-certificado', [CertificateController::class, 'verify']);

// Rota para testar PDF (desenvolvimento)
Route::get('/ver-pdf', [CertificateController::class, 'viewPdf'])->name('certificates.test-pdf');
