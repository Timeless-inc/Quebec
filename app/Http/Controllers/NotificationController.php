<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $formattedNotifications = $notifications->map(function ($notification) {
            $data = $notification->toArray();
            
            $data['event_type_label'] = $this->getEventTypeLabel($notification->event_type);
            
            return $data;
        });

        return response()->json($formattedNotifications);
    }

    /**
     * Retorna label legível para o tipo de evento.
     */
    private function getEventTypeLabel($eventType)
    {
        $labels = [
            'request_created' => 'Novo Requerimento',
            'status_changed' => 'Mudança de Status',
            'event_created' => 'Novo Evento',
            'event_expiring' => 'Evento Expirando',
            'user_registered' => 'Bem-vindo',
        ];
        
        return $labels[$eventType] ?? 'Notificação';
    }

    /**
 * Retorna a contagem de notificações não lidas para o usuário autenticado.
 *
 * @return \Illuminate\Http\JsonResponse
 */
public function unreadCount()
{
    $count = Notification::where('user_id', Auth::id())
        ->where('is_read', false)
        ->count();

    return response()->json(['count' => $count]);
}

    /**
 * Marca uma notificação como lida.
 *
 * @param int $id O ID da notificação
 * @return \Illuminate\Http\JsonResponse
 */
public function markAsRead($id)
{
    $notification = Notification::where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    $notification->update(['is_read' => true]);

    return response()->json(['message' => 'Notificação marcada como lida.']);
}

/**
 * Exclui uma notificação permanentemente.
 *
 * @param int $id O ID da notificação
 * @return \Illuminate\Http\JsonResponse
 */
public function deleteNotification($id)
{
    $notification = Notification::where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    $notification->delete();

    return response()->json(['message' => 'Notificação removida com sucesso.']);
}
}