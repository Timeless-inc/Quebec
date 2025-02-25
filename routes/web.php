<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CradtController;
use App\Http\Controllers\CradtReportController;
use App\Http\Controllers\JustificativaAlunoController;
use App\Http\Controllers\JustificativaController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

//Geral
Route::get('/', function () {
    return view('welcome');
});

// Verifica se o usuário é Cradt, se sim, redireciona para a dashboard específica.
Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    $user = $request->user();
    
    // Verifica se o usuário tem a role "Cradt"
    if ($user->role === 'Cradt') {
        // Redireciona para a rota "cradt"
        return redirect()->route('cradt.index');
    }

    // Caso não seja "Cradt", chama o controller do Dashboard
    return (new DashboardController())->index();  // Chama o método index do DashboardController
})
->middleware(['auth', 'verified'])
->name('dashboard');
    
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo'); //**
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Alunos
Route::get('/aluno/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/aluno/novo-requerimento', [ApplicationController::class, 'index'])->name('application');
Route::get('/requerimentos', [ApplicationController::class, 'index'])->name('application.index');

// Route::get('/requerimentos/create', [ApplicationController::class, 'create'])->name('application.create'); --> SEM USO

Route::post('/requerimentos/store', [ApplicationController::class, 'store'])->name('application.store');
Route::get('/requerimentos/success', [ApplicationController::class, 'success'])->name('application.success');
Route::get('/requerimentos/{id}', [ApplicationController::class, 'show'])->name('application.show');
Route::delete('/requerimentos/{id}', [ApplicationController::class, 'destroy'])->name('application.destroy');

//Cradt
Route::get('/cradt/dashboard', [CradtController::class, 'index'])->middleware(['auth', 'verified'])->name('cradt');
Route::get('/cradt', [CradtController::class, 'index'])->name('cradt.index');

//Justificativa
Route::get('/justificativas', [JustificativaController::class, 'index'])->name('justificativas.index');
Route::post('/justificativa/update-status/{id}', [JustificativaController::class, 'updateStatus'])->name('justificativa.updateStatus');
Route::get('/justificativa-aluno/{cpf}', [JustificativaAlunoController::class, 'show'])->name('justificativa-aluno.show');
Route::get('/application/{id}/edit', [ApplicationController::class, 'edit'])->name('application.edit');
Route::put('/application/{id}', [ApplicationController::class, 'update'])->name('application.update');

//PDF
Route::get('/requerimentos1', [ApplicationController::class, 'index']);
Route::get('/requerimento/{id}/pdf', [PDFController::class, 'gerarPDF'])->name('requerimento.pdf');

Route::patch('/requerimentos/{id}/status', [ApplicationController::class, 'updateStatus'])->name('application.updateStatus');

Route::get('/cradt/report', [CradtReportController::class, 'index'])->middleware(['auth', 'verified'])->name('cradt-report');

//Eventos
Route::middleware(['auth'])->group(function () {
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
});
Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');

Route::get('/cradt/register', [CradtController::class, 'showRegistrationForm'])->name('cradt.register');
Route::post('/cradt/register', [CradtController::class, 'register']);
               
require __DIR__.'/auth.php';


Route::get('/team', function () {
    return view('team.team'); 
});