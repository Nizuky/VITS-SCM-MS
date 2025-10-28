<?php

namespace App\Http\Controllers;

use App\Models\SocialContractRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialContractRecordController extends Controller
{
    public function index(Request $request)
    {
        try {
            \Illuminate\Support\Facades\Log::debug('SocialContractRecordController@index called', [
                'session_id' => $request->session()->getId(),
                'user_id' => optional($request->user())->getKey(),
                'path' => $request->getPathInfo(),
            ]);
        } catch (\Throwable $_) { }

        // Ensure we're working with the web guard (student)
        $user = Auth::guard('web')->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        $all = (string) $request->query('all', '0') === '1';
        $contractId = $request->query('contract_id');

        if ($all) {
            $records = $user->socialContractRecords()->with('approval')->latest('date')->get()->map(function($record) {
                $data = $record->toArray();
                // Add action dates from approval table if exists - send as ISO timestamps for proper timezone handling
                if ($record->approval) {
                    $data['verified_at'] = $record->approval->verified_at ? $record->approval->verified_at->toISOString() : null;
                    $data['approved_at'] = $record->approval->approved_at ? $record->approval->approved_at->toISOString() : null;
                    $data['rejected_at'] = $record->approval->rejected_at ? $record->approval->rejected_at->toISOString() : null;
                    
                    // Debug log
                    \Log::info('Record timestamps', [
                        'id' => $record->id,
                        'status' => $record->status,
                        'verified_at' => $data['verified_at'],
                        'approved_at' => $data['approved_at'],
                        'rejected_at' => $data['rejected_at'],
                    ]);
                    
                    // Determine action date based on status - also send as ISO timestamp
                    if ($record->status === 'Approved' && $record->approval->approved_at) {
                        $data['action_date'] = $record->approval->approved_at->toISOString();
                    } elseif ($record->status === 'Rejected' && $record->approval->rejected_at) {
                        $data['action_date'] = $record->approval->rejected_at->toISOString();
                    } elseif ($record->status === 'Verified' && $record->approval->verified_at) {
                        $data['action_date'] = $record->approval->verified_at->toISOString();
                    }
                } else {
                    \Log::warning('No approval record found for record ID: ' . $record->id);
                }
                return $data;
            });
            return response()->json([
                'contract' => null,
                'records' => $records,
            ]);
        }

        $contract = $contractId
            ? $user->socialContracts()->whereKey($contractId)->firstOrFail()
            : $user->currentSocialContract();

        $records = $contract->records()->with('approval')->latest('date')->get()->map(function($record) {
            $data = $record->toArray();
            // Add action dates from approval table if exists - send as ISO timestamps for proper timezone handling
            if ($record->approval) {
                $data['verified_at'] = $record->approval->verified_at ? $record->approval->verified_at->toISOString() : null;
                $data['approved_at'] = $record->approval->approved_at ? $record->approval->approved_at->toISOString() : null;
                $data['rejected_at'] = $record->approval->rejected_at ? $record->approval->rejected_at->toISOString() : null;
                
                // Determine action date based on status - also send as ISO timestamp
                if ($record->status === 'Approved' && $record->approval->approved_at) {
                    $data['action_date'] = $record->approval->approved_at->toISOString();
                } elseif ($record->status === 'Rejected' && $record->approval->rejected_at) {
                    $data['action_date'] = $record->approval->rejected_at->toISOString();
                } elseif ($record->status === 'Verified' && $record->approval->verified_at) {
                    $data['action_date'] = $record->approval->verified_at->toISOString();
                }
            }
            return $data;
        });

        return response()->json([
            'contract' => [
                'id' => $contract->id,
                'status' => $contract->status,
                'submission_date' => optional($contract->submission_date)->toISOString(),
            ],
            'records' => $records,
        ])
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
    }

    public function store(Request $request)
    {
        // Ensure we're working with the web guard (student)
        // This prevents any interference with admin/superadmin sessions
        $user = Auth::guard('web')->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        $data = $request->validate([
            'date' => ['required', 'date'],
            'event_name' => ['required', 'string', 'max:255'],
            'venue' => ['required', 'string', 'max:255'],
            'organization' => ['required', 'string', 'max:255'],
            'supervisor_name' => ['nullable', 'string', 'max:255'],
            'hours_rendered' => ['required', 'integer', 'min:0'],
            'contract_id' => ['nullable', 'integer'],
        ]);

        \Illuminate\Support\Facades\Log::debug('SocialContractRecordController@store called', [
            'session_id' => $request->session()->getId(),
            'user_id' => $user->getKey(),
            'payload' => $data,
        ]);

        $contract = isset($data['contract_id']) && $data['contract_id']
            ? $user->socialContracts()->whereKey($data['contract_id'])->firstOrFail()
            : $user->currentSocialContract();

        $record = $contract->records()->create([
            'date' => $data['date'],
            'event_name' => $data['event_name'],
            'venue' => $data['venue'],
            'organization' => $data['organization'],
            'supervisor_name' => $data['supervisor_name'] ?? null,
            'hours_rendered' => $data['hours_rendered'],
            'status' => 'Pending',
        ]);

        // Add cache prevention headers to ensure fresh data
        return response()->json($record, 201)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function destroy(int $id, Request $request)
    {
        // Ensure we're working with the web guard (student)
        $user = Auth::guard('web')->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        $record = \App\Models\SocialContractRecord::query()
            ->whereKey($id)
            ->whereHas('socialContract', fn($q) => $q->where('student_id', $user->getKey()))
            ->firstOrFail();

        if ($record->status !== 'Pending') {
            return response()->json(['message' => 'Only pending records can be deleted.'], 422);
        }

        $record->delete();
        
        return response()->json(['deleted' => true])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
