<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;

class LoanPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function update(User $user, Loan $loan) {
        // Only the lender can update the loan
        return $user->id === $loan->lender_id;
    }

    public function delete(User $user, Loan $loan) {
        // Only the lender can delete the loan
        return $user->id === $loan->lender_id;
    }
}
