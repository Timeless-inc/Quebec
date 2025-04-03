<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Carbon\Carbon;

class EventController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Tentativa de criação de evento', [
            'request_data' => $request->all(),
            'user_id' => Auth::id()
        ]);

        try {
            $validated = $request->validate([
                'requisition_type_id' => 'required|integer',
                'title' => 'nullable|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'is_active' => 'sometimes|boolean',
            ]);

            Log::info('Dados validados', $validated);

            if (empty($validated['title'])) {
                $applicationController = app('App\Http\Controllers\ApplicationController');
                $tiposRequisicao = $applicationController->getTiposRequisicao();
                $validated['title'] = $tiposRequisicao[$validated['requisition_type_id']] ?? "Evento #" . $validated['requisition_type_id'];
                Log::info('Título gerado automaticamente', ['title' => $validated['title']]);
            }

            $validated['is_active'] = isset($request->is_active) ? true : false;

            $validated['created_by'] = Auth::id();

            Log::info('Antes de criar o evento', $validated);

            $event = Event::create($validated);

            Log::info('Evento criado com sucesso', ['event_id' => $event->id]);

            $this->updateAvailableRequisitionTypes();

            return redirect()->back()->with('success', 'Evento criado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao criar evento', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Erro ao criar o evento: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Event $event)
    {
        Log::info('Tentativa de atualização de evento', [
            'event_id' => $event->id,
            'request_data' => $request->all()
        ]);

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'is_active' => 'sometimes|boolean',
            ]);

            $validated['is_active'] = isset($request->is_active) ? true : false;

            $event->update($validated);

            Log::info('Evento atualizado com sucesso', ['event_id' => $event->id]);

            $this->updateAvailableRequisitionTypes();

            return redirect()->back()->with('success', 'Evento atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar evento', [
                'event_id' => $event->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Erro ao atualizar o evento: ' . $e->getMessage());
        }
    }

    public function destroy(Event $event)
    {
        try {
            $eventId = $event->id;
            $event->delete();

            Log::info('Evento excluído com sucesso', ['event_id' => $eventId]);

            $this->updateAvailableRequisitionTypes();

            return redirect()->back()->with('success', 'Evento excluído com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir evento', [
                'event_id' => $event->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Erro ao excluir o evento: ' . $e->getMessage());
        }
    }

    private function updateAvailableRequisitionTypes()
    {
        try {
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function cleanupExpiredEvents()
    {
        $deletedCount = Event::removeExpiredEvents();

        $this->updateAvailableRequisitionTypes();

        return response()->json([
            'message' => "Removed {$deletedCount} expired events",
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
    }

    public function searchByCpf($cpf)
    {
        $user = User::where('cpf', $cpf)->first();

        if ($user) {
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Usuário não encontrado'
        ]);
    }

    public function storeException(Request $request)
    {
        Log::info('Tentativa de criação de evento de exceção', [
            'request_data' => $request->all(),
            'user_id' => Auth::id()
        ]);

        try {
            $validated = $request->validate([
                'requisition_type_id' => 'required|integer',
                'title' => 'nullable|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'is_active' => 'sometimes|boolean',
                'user_id' => 'required|exists:users,id', 
                'cpf' => 'required|string', 
            ]);

            Log::info('Dados validados para evento de exceção', $validated);

            if (empty($validated['title'])) {
                $applicationController = app('App\Http\Controllers\ApplicationController');
                $tiposRequisicao = $applicationController->getTiposRequisicao();
                $validated['title'] = $tiposRequisicao[$validated['requisition_type_id']] ?? "Evento de Exceção #" . $validated['requisition_type_id'];
                Log::info('Título gerado automaticamente', ['title' => $validated['title']]);
            }

            $validated['is_active'] = isset($request->is_active) ? true : false;

            $validated['created_by'] = Auth::id();

            $validated['is_exception'] = true;

            $validated['exception_user_id'] = $validated['user_id'];

            unset($validated['user_id']);
            unset($validated['cpf']);

            Log::info('Antes de criar o evento de exceção', $validated);

            $event = Event::create($validated);

            Log::info('Evento de exceção criado com sucesso', ['event_id' => $event->id]);

            $this->updateAvailableRequisitionTypes();

            return redirect()->back()->with('success', 'Evento de exceção criado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao criar evento de exceção', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Erro ao criar o evento de exceção: ' . $e->getMessage());
        }
    }

    public function isEventAvailableToday($event)
    {
        if (!is_object($event)) {
            $event = Event::find($event);
        }

        if (!$event) {
            return false;
        }

        $today = Carbon::today();
        $eventStartDate = Carbon::parse($event->start_date)->startOfDay();
        $eventEndDate = Carbon::parse($event->end_date)->endOfDay();

        return $today->betweenIncluded($eventStartDate, $eventEndDate);
    }

    public function getAvailableEventsForToday()
    {
        $today = Carbon::today();
        
        return Event::where('is_active', true)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->get();
    }

    public function scopeActiveForUser($query, $userId, $isCradt = false)
    {
        $today = Carbon::today();
        
        return $query->where('is_active', true)
            ->whereDate('end_date', '>=', now())
            ->where(function ($query) use ($userId, $isCradt, $today) {
                $query->where('is_exception', false)
                    ->whereDate('start_date', '<=', $today); 

                if (!$isCradt) {
                    $query->orWhere(function ($q) use ($userId, $today) {
                        $q->where('is_exception', true)
                            ->where('exception_user_id', $userId)
                            ->whereDate('start_date', '<=', $today); 
                    });
                } else {
                    $query->orWhere(function ($q) use ($today) {
                        $q->where('is_exception', true)
                            ->whereDate('start_date', '<=', $today); 
                    });
                }
            });
    }
}
