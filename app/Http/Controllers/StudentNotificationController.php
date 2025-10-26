<?php

namespace App\Http\Controllers;

use App\Models\StudentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentNotificationController extends Controller
{
    /**
     * Get recent notifications (max 3) for dropdown
     */
    public function getRecent()
    {
        try {
            $notifications = StudentNotification::where('user_id', Auth::id())
                ->with('socialContractRecord')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get()
                ->map(function ($notification) {
                    $record = $notification->socialContractRecord;
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'type' => $notification->type,
                        'message' => $notification->message,
                        'rejection_reason' => $notification->rejection_reason,
                        'is_read' => $notification->is_read,
                        'created_at' => $notification->created_at->diffForHumans(),
                        'social_contract_record_id' => $notification->social_contract_record_id,
                        'event_name' => $record ? $record->event_name : null,
                        'venue' => $record ? $record->venue : null,
                    ];
                });

            $unreadCount = StudentNotification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all notifications
     */
    public function getAll()
    {
        try {
            $notifications = StudentNotification::where('user_id', Auth::id())
                ->with('socialContractRecord')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($notification) {
                    $record = $notification->socialContractRecord;
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'type' => $notification->type,
                        'message' => $notification->message,
                        'rejection_reason' => $notification->rejection_reason,
                        'is_read' => $notification->is_read,
                        'created_at' => $notification->created_at->diffForHumans(),
                        'created_at_full' => $notification->created_at->format('F j, Y g:i A'),
                        'social_contract_record_id' => $notification->social_contract_record_id,
                        'event_name' => $record ? $record->event_name : null,
                        'venue' => $record ? $record->venue : null,
                    ];
                });

            return response()->json([
                'success' => true,
                'notifications' => $notifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch all notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        try {
            $notification = StudentNotification::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $notification->is_read = true;
            $notification->save();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a notification
     */
    public function delete($id)
    {
        try {
            $notification = StudentNotification::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        try {
            StudentNotification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all as read: ' . $e->getMessage()
            ], 500);
        }
    }
}
