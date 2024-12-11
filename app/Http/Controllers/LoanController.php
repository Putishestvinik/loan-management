<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoanController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Loan::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        // validate input before creating
        // check if lender and borrower exists in users table
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'duration' => 'required|integer|min:1',
            'lender_id' => 'required|exists:users,id',
            'borrower_id' => 'required|exists:users,id',
        ]);

        try {
            $loan = Loan::create($validated);
        } catch (Exception $e) {
            Log::error('Error creating Loan: '.$e->getMessage(), ['exception' => $e]);
            
            return response()->json(['message' => 'Unexpected error encountered while creating your Loan'], 500);
        }

        return response()->json([
            'message' => 'Loan was created',
            'data' => $loan
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan) {
        return $loan;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loan $loan) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan) {
        if ($loan->lender_id !== Auth::id()) {
            return response()->json(['message' => 'You are not authorized to modify this loan'], 403);
        }
    
        // validate input before updating
        $validated = $request->validate([
            'amount' => 'sometimes|numeric|min:0',
            'interest_rate' => 'sometimes|numeric|min:0|max:100',
            'duration' => 'sometimes|integer|min:1',
            'lender_id' => 'sometimes|exists:users,id',
            'borrower_id' => 'sometimes|exists:users,id',
        ]);

        try {
            $loan->update($validated);
        } catch (Exception $e) {
            Log::error('Error updating Loan: '.$e->getMessage(), ['exception' => $e]);
            
            return response()->json(['message' => 'Unexpected error encountered while updating your Loan'], 500);
        }

        return response()->json([
            'message' => 'Loan was updated',
            'updated_data' => $loan
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan) {
        if ($loan->lender_id !== Auth::id()) {
            return response()->json(['message' => 'You are not authorized to delete this loan'], 403);
        }

        try {
            $loan->delete();
        } catch (Exception $e) {
            Log::error('Error deleting Loan: '.$e->getMessage(), ['exception' => $e]);

            return response()->json(['message' => 'Unexpected error encountered while deleting your Loan'], 500);
        }

        return response()->json(['message' => 'Loan was deleted successfully'], 204);
    }
}
