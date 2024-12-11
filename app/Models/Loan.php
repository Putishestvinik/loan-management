<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model {
    
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     **/
    protected $fillable = [
        'amount', 
        'interest_rate', 
        'duration',
        'lender_id',
        'borrower_id',
    ];

    /**
     * Define the relationship between the Loan and the Lender.
     * Loan belongs to a User as 'lender_id'
     */
    public function lender() {
        return $this->belongsTo(User::class, 'lender_id');
    }

    /**
     * Define the relationship between the Loan and the Borrower.
     * Loan belongs to a User as 'borrower_id'
     */
    public function borrower() {
        return $this->belongsTo(User::class, 'borrower_id');
    }
}
