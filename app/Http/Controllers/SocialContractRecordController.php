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
        $contractId = $request->query('contract_id');
        $contract = $contractId ? $user->socialContracts()->whereKey($contractId)->firstOrFail() : $user->currentSocialContract();

        $records = $contract->records()->latest('date')->get();

        return response()->json([
            'contract' => [
                'id' => $contract->id,
                'status' => $contract->status,
                'submission_date' => optional($contract->submission_date)->toISOString(),
            ],
            'records' => $records,
        ]);
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
