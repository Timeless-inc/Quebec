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
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

// Rota pública para verificação de atualizações 


//Geral - Rotas Públicas
Route::get('/', function () {
    return view('welcome');
});

// Verifica se o usuário é Cradt e redireciona
Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    $user = $request->user();

    // Verifica se o usuário tem a role "Cradt"
    if ($user->role === 'Cradt' ||$user->role === 'Manager' ) {
        // Redireciona usando o nome da rota
        return redirect()->route('cradt');
    }

    // Caso não seja "Cradt", chama o controller do Dashboard
    return (new DashboardController())->index();
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rotas comuns a todos os usuários autenticados
Route::middleware('auth')->group(function () {
    // Rotas para gerenciamento de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rotas para gerenciamento de notificações
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/{id}/delete', [NotificationController::class, 'deleteNotification']);
});

// Rotas específicas para Alunos e CRADT (compartilhadas)
Route::middleware(['auth', 'verified', 'role:Aluno,Cradt,Manager'])->group(function () {
    Route::get('/requerimentos', [ApplicationController::class, 'index'])->name('application.index');
    Route::post('/requerimentos/store', [ApplicationController::class, 'store'])->name('application.store');
    Route::get('/requerimentos/success', [ApplicationController::class, 'success'])->name('application.success');
    Route::get('/requerimentos/{id}', [ApplicationController::class, 'show'])->name('application.show');
    Route::get('/requerimento/{id}/pdf', [PDFController::class, 'gerarPDF'])->name('requerimento.pdf');
});

// Rotas específicas para Alunos
Route::middleware(['auth', 'verified', 'role:Aluno'])->group(function () {
    Route::get('/aluno/dashboard', [DashboardController::class, 'index'])->name('aluno.dashboard');
    Route::get('/aluno/novo-requerimento', [ApplicationController::class, 'index'])->name('application');
    Route::get('/application/{id}/edit', [ApplicationController::class, 'edit'])->name('application.edit');
    Route::put('/application/{id}', [ApplicationController::class, 'update'])->name('application.update');
    Route::delete('/requerimentos/{id}', [ApplicationController::class, 'destroy'])->name('application.destroy');
});

// Rotas específicas para CRADT e manager
Route::middleware(['auth', 'verified', 'role:Cradt,Manager'])->group(function () {
    // Dashboard CRADT
    Route::get('/cradt/dashboard', [CradtController::class, 'index'])->name('cradt');
    Route::get('/cradt', [CradtController::class, 'index'])->name('cradt.index');
    
    // Relatórios
    Route::get('/cradt/report/chart-data', [CradtReportController::class, 'getChartData'])->name('cradt.chart-data');
    Route::get('/cradt/report', [CradtReportController::class, 'index'])->name('cradt-report');
    Route::get('/getFilteredData', [CradtReportController::class, 'getFilteredData']);
    Route::get('/getCrossReport', [CradtReportController::class, 'getCrossReport'])->name('reports.getCrossReport');
    
    // Gerenciamento de usuários
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    
    // Cadastro de CRADT
    Route::get('/cradt/register', [CradtController::class, 'showRegistrationForm'])->name('cradt.register');
    Route::post('/cradt/register', [CradtController::class, 'register']);
    
    // Justificativas e atualização de status
    Route::get('/justificativas', [JustificativaController::class, 'index'])->name('justificativas.index');
    Route::post('/justificativa/update-status/{id}', [JustificativaController::class, 'updateStatus'])->name('justificativa.updateStatus');
    Route::patch('/requerimentos/{id}/status', [ApplicationController::class, 'updateStatus'])->name('application.updateStatus');
    
    // Eventos
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::get('/cleanup-events', [EventController::class, 'cleanupExpiredEvents']);
    Route::post('/events/store-exception', [EventController::class, 'storeException'])->name('events.store-exception');
    Route::get('/api/users/search-by-cpf/{cpf}', [EventController::class, 'searchByCpf']);
    Route::post('/events/configure-required-types', [EventController::class, 'configureRequiredTypes'])->name('events.configure-required-types');
    Route::get('/api/requerimentos/check-updates', function () {
        $lastUpdate = \App\Models\ApplicationRequest::latest('updated_at')->value('updated_at');
        
        \Illuminate\Support\Facades\Log::info('Requisição de verificação recebida', [
            'last_update' => $lastUpdate,
            'request_ip' => request()->ip()
        ]);
        
        return response()->json([
            'lastUpdate' => $lastUpdate,
            'serverTime' => now()->toIso8601String()
        ]);
    });
});

// Rota compartilhada para visualização de justificativa de aluno
Route::get('/justificativa-aluno/{cpf}', [JustificativaAlunoController::class, 'show'])->name('justificativa-aluno.show');

require __DIR__ . '/auth.php';