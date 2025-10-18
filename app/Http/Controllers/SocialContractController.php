<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialContractController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $contracts = $user->socialContracts()->latest('id')->get(['id', 'status', 'submission_date', 'created_at']);
        return response()->json($contracts);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $contract = $user->socialContracts()->create([
            'status' => 'submitted',
            'submission_date' => now(),
        ]);

        return response()->json($contract, 201);
    }
}
