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
use App\Http\Controllers\ForwardingController;
use App\Http\Controllers\DiretorGeralController;
use App\Http\Controllers\RoleController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

//Geral - Rotas Públicas
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (Request $request) {
    // Keep flashed notifications during redirect
    session()->reflash();

    $user = $request->user();

    if (in_array($user->role, ['Cradt', 'Manager'])) {
        return redirect()->route('cradt');
    }

    if ($user->isDiretorGeral()) {
        return redirect()->route('diretor-geral.dashboard');
    }

    if ($user->role === 'Aluno') {
        return (new DashboardController())->index();
    }

    $allDynamicRoles = Role::pluck('label')->toArray();
    if (in_array($user->role, $allDynamicRoles)) {
        return redirect()->route('painel.dashboard', [
            'cargoSlug' => $user->getRouteSlug(),
        ]);
    }

    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login')->withErrors([
        'email' => 'Seu cargo ("' . $user->role . '") não tem acesso ao sistema. Contacte o administrador.',
    ]);
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// ─────────────────────────────────────────────
//  Rotas comuns a todos os usuários autenticados
// ─────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/profile/request-update', [ProfileController::class, 'requestUpdate'])->name('profile.request-update');

    Route::get('/profile/check-duplicate', function (Request $request) {
        $field  = $request->input('field');
        $value  = $request->input('value');
        $userId = Auth::id();

        if (!in_array($field, ['cpf', 'rg', 'matricula'])) {
            return response()->json(['error' => 'Campo inválido'], 400);
        }

        $exists = \App\Models\User::where($field, $value)
            ->where('id', '!=', $userId)
            ->exists();

        return response()->json(['exists' => $exists]);
    })->middleware('auth')->name('profile.check-duplicate');

    Route::get('/notifications/count', [NotificationController::class, 'unreadCount'])->name('notifications.count');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/{id}/delete', [NotificationController::class, 'deleteNotification']);
});

// ─────────────────────────────────────────────
//  Rotas compartilhadas: Aluno, Cradt, Diretor Geral + cargos dinâmicos
// ─────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:Aluno,Cradt,Diretor Geral'])->group(function () {
    Route::get('/requerimentos', [ApplicationController::class, 'index'])->name('application.index');
    Route::post('/requerimentos/store', [ApplicationController::class, 'store'])->name('application.store');
    Route::get('/requerimentos/success', [ApplicationController::class, 'success'])->name('application.success');
    Route::get('/requerimentos/{id}', [ApplicationController::class, 'show'])->name('application.show');
    Route::get('/requerimento/{id}/pdf', [PDFController::class, 'gerarPDF'])->name('requerimento.pdf');
});

// Rotas específicas para Alunos
Route::middleware(['auth', 'verified', 'role:Aluno'])->group(function () {
    Route::get('/aluno/dashboard', [DashboardController::class, 'index'])->name('aluno.dashboard');
    Route::get('/aluno/novo-requerimento', [ApplicationController::class, 'create'])->name('application');
    Route::get('/application/{id}/edit', [ApplicationController::class, 'edit'])->name('application.edit');
    Route::put('/application/{id}', [ApplicationController::class, 'update'])->name('application.update');
    Route::delete('/requerimentos/{id}', [ApplicationController::class, 'destroy'])->name('application.destroy');
});

// Justificativas e status — Cradt, Diretor Geral (+ cargos dinâmicos via super-acesso do middleware)
Route::middleware(['auth', 'verified', 'role:Cradt,Diretor Geral'])->group(function () {
    Route::get('/justificativas', [JustificativaController::class, 'index'])->name('justificativas.index');
    Route::post('/justificativa/update-status/{id}', [JustificativaController::class, 'updateStatus'])->name('justificativa.updateStatus');
    Route::patch('/requerimentos/{id}/status', [ApplicationController::class, 'updateStatus'])->name('application.updateStatus');
    Route::post('/requerimento/{id}/marcar-como-visto', [ApplicationController::class, 'marcarComoVisto'])->name('requerimento.marcarComoVisto');
});

// ─────────────────────────────────────────────
//  Rotas específicas para CRADT e Manager
// ─────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:Cradt'])->group(function () {
    Route::get('/cradt/dashboard', [CradtController::class, 'index'])->name('cradt');
    Route::get('/cradt', [CradtController::class, 'index'])->name('cradt.index');

    Route::get('/cradt/report/chart-data', [CradtReportController::class, 'getChartData'])->name('cradt.chart-data');
    Route::get('/cradt/report', [CradtReportController::class, 'index'])->name('cradt-report');
    Route::get('/getFilteredData', [CradtReportController::class, 'getFilteredData']);
    Route::get('/getCrossReport', [CradtReportController::class, 'getCrossReport'])->name('reports.getCrossReport');
    Route::get('/cradt/reports/user-pdf', [CradtReportController::class, 'generateUserPdf'])->name('cradt-report.user-pdf');

    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    Route::get('/cradt/register', [CradtController::class, 'showRegistrationForm'])->name('cradt.register');
    Route::post('/cradt/register', [CradtController::class, 'register']);

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
            'request_ip'  => request()->ip(),
        ]);
        return response()->json([
            'lastUpdate' => $lastUpdate,
            'serverTime' => now()->toIso8601String(),
        ]);
    });

    Route::get('/document/{id}', function ($id) {
        $request = App\Models\ProfileChangeRequest::findOrFail($id);
        return Storage::response($request->document_path);
    })->name('document.view')->middleware('auth');
});

// ─────────────────────────────────────────────
//  Rotas para Diretor Geral
// ─────────────────────────────────────────────
Route::middleware(['auth', 'role:Diretor Geral'])->prefix('diretor-geral')->group(function () {
    Route::get('/dashboard', [DiretorGeralController::class, 'dashboard'])->name('diretor-geral.dashboard');
    Route::get('/reports', [DiretorGeralController::class, 'reports'])->name('diretor-geral.reports');
    Route::get('/reports/processed', [DiretorGeralController::class, 'generateProcessedReport'])->name('diretor-geral.reports.processed');
    Route::get('/reports/period', [DiretorGeralController::class, 'generatePeriodReport'])->name('diretor-geral.reports.period');
    Route::get('/reports/statistics', [DiretorGeralController::class, 'getStatistics'])->name('diretor-geral.reports.statistics');
    Route::post('/process/{forwarding}', [DiretorGeralController::class, 'processRequest'])->name('diretor-geral.process');
    Route::post('/return/{forwarding}', [DiretorGeralController::class, 'returnRequest'])->name('diretor-geral.return');

    // Gerenciamento de usuários
    Route::get('/users', [UserManagementController::class, 'index'])->name('diretor-geral.users.index');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('diretor-geral.users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('diretor-geral.users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('diretor-geral.users.destroy');
});

// ─────────────────────────────
//  Rotas dinâmicas: /painel/{cargoSlug}/ para cargos da tabela roles
// ─────────────────────────────
Route::middleware(['auth', 'role:Diretor Geral'])
    ->prefix('painel/{cargoSlug}')
    ->name('painel.')
    ->group(function () {
        Route::get('/dashboard', [DiretorGeralController::class, 'dynamicDashboard'])->name('dashboard');
        Route::get('/reports', [DiretorGeralController::class, 'dynamicReports'])->name('reports');
        Route::get('/reports/processed', [DiretorGeralController::class, 'generateProcessedReport'])->name('reports.processed');
        Route::get('/reports/period', [DiretorGeralController::class, 'generatePeriodReport'])->name('reports.period');
        Route::get('/reports/statistics', [DiretorGeralController::class, 'getStatistics'])->name('reports.statistics');
        Route::post('/process/{forwarding}', [DiretorGeralController::class, 'processRequest'])->name('process');
        Route::post('/return/{forwarding}', [DiretorGeralController::class, 'returnRequest'])->name('return');
    });

// ─────────────────────────────────────────────
//  Gerenciamento de Cargos (somente Diretor Geral)
// ─────────────────────────────────────────────
Route::middleware(['auth', 'role:Diretor Geral'])->prefix('cargos')->name('cargos.')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::get('/create', [RoleController::class, 'create'])->name('create');
    Route::post('/', [RoleController::class, 'store'])->name('store');
    Route::get('/{cargo}/edit', [RoleController::class, 'edit'])->name('edit');
    Route::put('/{cargo}', [RoleController::class, 'update'])->name('update');
    Route::delete('/{cargo}', [RoleController::class, 'destroy'])->name('destroy');
});

// ─────────────────────────────────────────────
//  Encaminhamentos — reencaminhar (Diretor Geral + cargos dinâmicos)
// ─────────────────────────────────────────────
Route::middleware(['auth', 'role:Diretor Geral'])->prefix('encaminhamentos')->group(function () {
    Route::post('/reencaminhar/{forwarding}', [ForwardingController::class, 'forwardFromCoordinatorProfessor'])->name('forwardings.reforward.store');
});

// Processar / devolver via ForwardingController (genérico para Diretor Geral + cargos com acesso)
Route::middleware(['auth', 'role:Diretor Geral'])->group(function () {
    Route::post('/requerimentos/process/{forwarding}', [ForwardingController::class, 'processRequest'])->name('requerimentos.process');
    Route::post('/requerimentos/return/{forwarding}', [ForwardingController::class, 'returnRequest'])->name('requerimentos.return');
});

// ─────────────────────────────────────────────
//  Encaminhamentos — CRADT envia (criar/listar)
// ─────────────────────────────────────────────
Route::middleware(['auth', 'role:Cradt'])->prefix('encaminhamentos')->group(function () {
    Route::get('/create/{requerimento}', [ForwardingController::class, 'showForwardForm'])->name('forwardings.create');
    Route::post('/store/{requerimento}', [ForwardingController::class, 'forward'])->name('forwardings.store');
    Route::get('/', [ForwardingController::class, 'viewForwarded'])->name('forwardings.index');
});

// ─────────────────────────────────────────────
//  Solicitações de alteração de perfil
// ─────────────────────────────────────────────
Route::middleware(['auth'])->prefix('profile-requests')->name('profile-requests.')->group(function () {
    Route::post('/{profileRequest}/approve', [ProfileChangeRequestController::class, 'approve'])->name('approve');
    Route::post('/{profileRequest}/reject', [ProfileChangeRequestController::class, 'reject'])->name('reject');
    Route::post('/group/{groupId}/approve', [ProfileChangeRequestController::class, 'approveGroup'])->name('approve-group');
    Route::post('/group/{groupId}/reject', [ProfileChangeRequestController::class, 'rejectGroup'])->name('reject-group');
});

// Rota compartilhada para visualização de justificativa de aluno
Route::get('/justificativa-aluno/{cpf}', [JustificativaAlunoController::class, 'show'])->name('justificativa-aluno.show');

require __DIR__ . '/auth.php';
