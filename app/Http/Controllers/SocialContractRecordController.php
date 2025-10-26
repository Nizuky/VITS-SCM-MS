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
                'cookies' => $request->cookies->all(),
                'path' => $request->getPathInfo(),
            ]);
        } catch (\Throwable $_) { }

        $user = Auth::user();
        $all = (string) $request->query('all', '0') === '1';
        $contractId = $request->query('contract_id');

        if ($all) {
            $records = $user->socialContractRecords()->with('approval')->latest('date')->get()->map(function($record) {
                $data = $record->toArray();
                // Add action dates from approval table if exists
                if ($record->approval) {
                    $data['verified_at'] = $record->approval->verified_at ? $record->approval->verified_at->format('m-d-Y') : null;
                    $data['approved_at'] = $record->approval->approved_at ? $record->approval->approved_at->format('m-d-Y') : null;
                    $data['rejected_at'] = $record->approval->rejected_at ? $record->approval->rejected_at->format('m-d-Y') : null;
                    
                    // Determine action date based on status
                    if ($record->status === 'Approved' && $record->approval->approved_at) {
                        $data['action_date'] = $record->approval->approved_at->format('m-d-Y');
                    } elseif ($record->status === 'Rejected' && $record->approval->rejected_at) {
                        $data['action_date'] = $record->approval->rejected_at->format('m-d-Y');
                    } elseif ($record->status === 'Verified' && $record->approval->verified_at) {
                        $data['action_date'] = $record->approval->verified_at->format('m-d-Y');
                    }
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
            // Add action dates from approval table if exists
            if ($record->approval) {
                $data['verified_at'] = $record->approval->verified_at ? $record->approval->verified_at->format('m-d-Y') : null;
                $data['approved_at'] = $record->approval->approved_at ? $record->approval->approved_at->format('m-d-Y') : null;
                $data['rejected_at'] = $record->approval->rejected_at ? $record->approval->rejected_at->format('m-d-Y') : null;
                
                // Determine action date based on status
                if ($record->status === 'Approved' && $record->approval->approved_at) {
                    $data['action_date'] = $record->approval->approved_at->format('m-d-Y');
                } elseif ($record->status === 'Rejected' && $record->approval->rejected_at) {
                    $data['action_date'] = $record->approval->rejected_at->format('m-d-Y');
                } elseif ($record->status === 'Verified' && $record->approval->verified_at) {
                    $data['action_date'] = $record->approval->verified_at->format('m-d-Y');
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
        $data = $request->validate([
            'date' => ['required', 'date'],
            'event_name' => ['required', 'string', 'max:255'],
            'venue' => ['required', 'string', 'max:255'],
            'organization' => ['required', 'string', 'max:255'],
            'hours_rendered' => ['required', 'integer', 'min:0'],
            'contract_id' => ['nullable', 'integer'],
        ]);

        \Illuminate\Support\Facades\Log::debug('SocialContractRecordController@store called', [
            'session_id' => $request->session()->getId(),
            'user_id' => optional($request->user())->getKey(),
            'payload' => $data,
            'cookies' => $request->cookies->all(),
        ]);

        $user = Auth::user();
        $contract = isset($data['contract_id']) && $data['contract_id']
            ? $user->socialContracts()->whereKey($data['contract_id'])->firstOrFail()
            : $user->currentSocialContract();

        $record = $contract->records()->create([
            'date' => $data['date'],
            'event_name' => $data['event_name'],
            'venue' => $data['venue'],
            'organization' => $data['organization'],
            'hours_rendered' => $data['hours_rendered'],
            'status' => 'Pending',
        ]);

        return response()->json($record, 201);
    }

    public function destroy(int $id)
    {
        $user = Auth::user();
        $record = \App\Models\SocialContractRecord::query()
            ->whereKey($id)
            ->whereHas('socialContract', fn($q) => $q->where('student_id', $user->getKey()))
            ->firstOrFail();

        if ($record->status !== 'Pending') {
            return response()->json(['message' => 'Only pending records can be deleted.'], 422);
        }

        $record->delete();
        return response()->json(['deleted' => true]);
    }
}
