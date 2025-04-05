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
            ->with('event') // Carrega o evento quando existe
            ->orderBy('created_at', 'desc')
            ->get();


        // Formatar as notificações para incluir a data do evento (quando existir)
        $formattedNotifications = $notifications->map(function ($notification) {
            $data = $notification->toArray();
            
            if ($notification->event) {
                $data['event_date'] = $notification->event->date;
            } else {
                $data['event_date'] = null;
            }
            
            return $data;
        });

        return response()->json($formattedNotifications);
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