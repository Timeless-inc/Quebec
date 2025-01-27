<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CradtController;
use App\Http\Controllers\JustificativaController;
use Illuminate\Support\Facades\Route;

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
        return redirect()->route('cradt');
    }

    // Caso não seja "Cradt", chama o controller do Dashboard
    return (new DashboardController())->index();  // Chama o método index do DashboardController
})
->middleware(['auth', 'verified'])
->name('dashboard');
    
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Alunos
Route::get('/aluno/novo-requerimento', [ApplicationController::class, 'index'])->name('application');
//Cradt
Route::get('/cradt/dashboard', [CradtController::class, 'index'])->middleware(['auth', 'verified'])->name('cradt');
//Justificativa
Route::get('/justificativas', [JustificativaController::class, 'index'])->name('justificativas.index');
Route::post('/justificativa/update-status/{id}', [JustificativaController::class, 'updateStatus'])->name('justificativa.updateStatus');



require __DIR__.'/auth.php';
