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

        return response()->json($notifications);
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