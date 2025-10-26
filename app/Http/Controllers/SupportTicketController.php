<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupportTicketController extends Controller
{
    /**
     * Get all tickets for the authenticated student
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            $tickets = SupportTicket::where('student_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($ticket) {
                    return [
                        'id' => $ticket->id,
                        'student_name' => $ticket->student_name,
                        'type' => $ticket->issue_type,
                        'details' => $ticket->details,
                        'status' => $ticket->status,
                        'date' => $ticket->created_at->format('Y-m-d'),
                        'submitted_at' => $ticket->created_at->format('M d, Y g:i A'),
                        'updated_at' => $ticket->updated_at->format('M d, Y g:i A'),
                    ];
                });

            return response()->json([
                'success' => true,
                'tickets' => $tickets,
                'remaining_tickets' => SupportTicket::getRemainingTickets($user->id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch tickets: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new support ticket
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            // Check if student can submit more tickets today
            if (!SupportTicket::canSubmitTicket($user->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have reached the daily limit of 2 tickets. Please try again tomorrow.'
                ], 429);
            }

            $validator = Validator::make($request->all(), [
                'issue_type' => 'required|string|max:255',
                'details' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $ticket = SupportTicket::create([
                'student_id' => $user->id,
                'student_name' => $user->name,
                'issue_type' => $request->issue_type,
                'details' => $request->details,
                'status' => 'Pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ticket submitted successfully!',
                'ticket' => [
                    'id' => $ticket->id,
                    'student_name' => $ticket->student_name,
                    'type' => $ticket->issue_type,
                    'details' => $ticket->details,
                    'status' => $ticket->status,
                    'date' => $ticket->created_at->format('Y-m-d'),
                    'submitted_at' => $ticket->created_at->format('M d, Y g:i A'),
                    'updated_at' => $ticket->updated_at->format('M d, Y g:i A'),
                ],
                'remaining_tickets' => SupportTicket::getRemainingTickets($user->id)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit ticket: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific ticket's details
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            
            $ticket = SupportTicket::where('id', $id)
                ->where('student_id', $user->id)
                ->first();

            if (!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ticket not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'ticket' => [
                    'id' => $ticket->id,
                    'student_name' => $ticket->student_name,
                    'type' => $ticket->issue_type,
                    'details' => $ticket->details,
                    'status' => $ticket->status,
                    'date' => $ticket->created_at->format('Y-m-d'),
                    'submitted_at' => $ticket->created_at->format('M d, Y g:i A'),
                    'updated_at' => $ticket->updated_at->format('M d, Y g:i A'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch ticket details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check remaining ticket limit for today
     */
    public function checkLimit()
    {
        try {
            $user = Auth::user();
            $remaining = SupportTicket::getRemainingTickets($user->id);
            $canSubmit = SupportTicket::canSubmitTicket($user->id);

            return response()->json([
                'success' => true,
                'can_submit' => $canSubmit,
                'remaining_tickets' => $remaining,
                'message' => $canSubmit 
                    ? "You can submit $remaining more ticket(s) today." 
                    : 'You have reached the daily limit of 2 tickets.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check ticket limit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a pending ticket
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            
            $ticket = SupportTicket::where('id', $id)
                ->where('student_id', $user->id)
                ->first();

            if (!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ticket not found'
                ], 404);
            }

            // Only allow deleting pending tickets
            if ($ticket->status !== 'Pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending tickets can be deleted'
                ], 400);
            }

            $ticket->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ticket deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete ticket: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark a resolved ticket as done (deletes it)
     */
    public function markAsDone($id)
    {
        try {
            $user = Auth::user();
            
            $ticket = SupportTicket::where('id', $id)
                ->where('student_id', $user->id)
                ->first();

            if (!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ticket not found'
                ], 404);
            }

            // Only allow marking resolved tickets as done
            if ($ticket->status !== 'Resolved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only resolved tickets can be marked as done'
                ], 400);
            }

            $ticket->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ticket marked as done and removed'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark ticket as done: ' . $e->getMessage()
            ], 500);
        }
    }
}
