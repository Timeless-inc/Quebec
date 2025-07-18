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
use App\Http\Controllers\ProfileChangeRequestController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\ForwardingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Rota pública para verificação de atualizações 


//Geral - Rotas Públicas
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    $user = $request->user();

    if ($user->role === 'Cradt' || $user->role === 'Manager') {
        // Redireciona usando o nome da rota
        return redirect()->route('cradt');
    } else if ($user->role === 'Coordenador') {
        return redirect()->route('coordinator.dashboard');
    } else if ($user->role === 'Professor') {
        return redirect()->route('professor.dashboard');
    }


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

    // Rota para solicitar atualização de perfil
    Route::post('/profile/request-update', [ProfileController::class, 'requestUpdate'])->name('profile.request-update');

    // Rota para verificar duplicidade de perfil
    Route::get('/profile/check-duplicate', function(Request $request) {
        $field = $request->input('field');
        $value = $request->input('value');
        $userId = Auth::id();
        
        if (!in_array($field, ['cpf', 'rg', 'matricula'])) {
            return response()->json(['error' => 'Campo inválido'], 400);
        }
        
        $exists = \App\Models\User::where($field, $value)
            ->where('id', '!=', $userId)
            ->exists();
        
        return response()->json(['exists' => $exists]);
    })->middleware('auth')->name('profile.check-duplicate');

    // Rotas para gerenciamento de notificações
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/{id}/delete', [NotificationController::class, 'deleteNotification']);
});

// Rotas específicas para Alunos e CRADT (compartilhadas)
Route::middleware(['auth', 'verified', 'role:Aluno,Cradt,Manager,Coordenador,Professor'])->group(function () {
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

Route::middleware(['auth', 'verified', 'role:Cradt,Manager,Coordenador,Professor'])->group(function () {
    // Justificativas e atualização de status
    Route::get('/justificativas', [JustificativaController::class, 'index'])->name('justificativas.index');
    Route::post('/justificativa/update-status/{id}', [JustificativaController::class, 'updateStatus'])->name('justificativa.updateStatus');
    Route::patch('/requerimentos/{id}/status', [ApplicationController::class, 'updateStatus'])->name('application.updateStatus');
    Route::post('/requerimento/{id}/marcar-como-visto', [ApplicationController::class, 'marcarComoVisto'])->name('requerimento.marcarComoVisto');
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
    Route::get('/cradt/reports/user-pdf', [CradtReportController::class, 'generateUserPdf'])->name('cradt-report.user-pdf');

    // Gerenciamento de usuários
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    // Cadastro de CRADT
    Route::get('/cradt/register', [CradtController::class, 'showRegistrationForm'])->name('cradt.register');
    Route::post('/cradt/register', [CradtController::class, 'register']);

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
    Route::get('/document/{id}', function ($id) {
        $request = App\Models\ProfileChangeRequest::findOrFail($id);
            return Storage::response($request->document_path);
    })->name('document.view')->middleware('auth');
});

// Rotas para gerenciamento de solicitações de alteração de perfil
Route::middleware(['auth'])->prefix('profile-requests')->name('profile-requests.')->group(function () {
    Route::post('/{profileRequest}/approve', [ProfileChangeRequestController::class, 'approve'])->name('approve');
    Route::post('/{profileRequest}/reject', [ProfileChangeRequestController::class, 'reject'])->name('reject');
    Route::post('/group/{groupId}/approve', [ProfileChangeRequestController::class, 'approveGroup'])->name('approve-group');
    Route::post('/group/{groupId}/reject', [ProfileChangeRequestController::class, 'rejectGroup'])->name('reject-group');
});


// Rota compartilhada para visualização de justificativa de aluno
Route::get('/justificativa-aluno/{cpf}', [JustificativaAlunoController::class, 'show'])->name('justificativa-aluno.show');

// Rotas para Coordenador
Route::middleware(['auth', 'role:Coordenador'])->prefix('coordenador')->group(function () {
    Route::get('/dashboard', [CoordinatorController::class, 'dashboard'])->name('coordinator.dashboard');
    Route::post('/process/{forwarding}', [CoordinatorController::class, 'processRequest'])->name('coordinator.process');
    Route::post('/return/{forwarding}', [CoordinatorController::class, 'returnRequest'])->name('coordinator.return');
});

// Rotas para Professor
Route::middleware(['auth', 'role:Professor'])->prefix('professor')->group(function () {
    Route::get('/dashboard', [ProfessorController::class, 'dashboard'])->name('professor.dashboard');
    Route::post('/process/{forwarding}', [ProfessorController::class, 'processRequest'])->name('professor.process');
    Route::post('/return/{forwarding}', [ProfessorController::class, 'returnRequest'])->name('professor.return');
});

// Rotas para Encaminhamentos (CRADT)
Route::middleware(['auth', 'role:Cradt,Manager'])->prefix('encaminhamentos')->group(function () {
    Route::get('/create/{requerimento}', [ForwardingController::class, 'showForwardForm'])->name('forwardings.create');
    Route::post('/store/{requerimento}', [ForwardingController::class, 'forward'])->name('forwardings.store');
    Route::get('/', [ForwardingController::class, 'viewForwarded'])->name('forwardings.index');
});

// Rotas para Reencaminhamentos (Coordenador e Professor) - apenas POST
Route::middleware(['auth', 'role:Coordenador,Professor'])->prefix('encaminhamentos')->group(function () {
    Route::post('/reencaminhar/{forwarding}', [ForwardingController::class, 'forwardFromCoordinatorProfessor'])->name('forwardings.reforward.store');
});

Route::middleware(['auth', 'role:Professor,Coordenador'])->group(function () {
    Route::post('/requerimentos/process/{forwarding}', [ForwardingController::class, 'processRequest'])->name('requerimentos.process');
    Route::post('/requerimentos/return/{forwarding}', [ForwardingController::class, 'returnRequest'])->name('requerimentos.return');
});

require __DIR__ . '/auth.php';
