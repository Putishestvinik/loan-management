<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoanTest extends TestCase {
    // Reset DB before testing
    use RefreshDatabase;

    /**
     * Test fetching all loans
     */
    public function test_user_can_fetch_all_loans() {
        // Create 10 loans
        Loan::factory(10)->create();

        $response = $this->getJson('/api/loans');

        $response->assertStatus(200);
        // Verify the response contains 10 loans
        $response->assertJsonCount(10);
    }

    /**
     * Test user can create a loan
     */
    public function test_user_can_create_loan() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/loans', [
            'amount' => 50000,
            'interest_rate' => 5.5,
            'duration' => 3,
            'lender_id' => $user->id,
            'borrower_id' => User::factory()->create()->id,
        ]);

        // Ensure the response status is 201
        $response->assertStatus(201);
        // Verify the loan exists in the database
        $this->assertDatabaseHas('loans', ['amount' => 50000]);
    }

    /**
     * Test only lender can update a loan
     */
    public function test_only_lender_can_update_a_loan() {
        $lender = User::factory()->create();
        $borrower = User::factory()->create();

        $loan = Loan::factory()->create([
            'lender_id' => $lender->id,
            'borrower_id' => $borrower->id,
        ]);

        $this->actingAs($lender, 'api'); // Authenticate as the lender

        $response = $this->putJson("/api/loans/{$loan->id}", [
            'amount' => 60000,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('loans', ['amount' => 60000]);
    }

    /**
     * Test that only original lender can delete a loan
     */
    public function test_only_lender_can_delete_a_loan() {
        $lender = User::factory()->create();
        $loan = Loan::factory()->create(['lender_id' => $lender->id]);

        $this->actingAs($lender, 'api');

        $response = $this->deleteJson("/api/loans/{$loan->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('loans', ['id' => $loan->id]);
    }

    /**
     * Test if loans are deleted in case the original lender is deleted
     */
    public function test_user_and_loans_are_deleted() {
        // Create user and loans
        $user = User::factory()->create();
        $loan = Loan::factory()->create([
            'lender_id' => $user->id,
            'borrower_id' => User::factory()->create()->id,
        ]);

        // Ensure the loan exists
        $this->assertDatabaseHas('loans', ['id' => $loan->id]);

        // Delete the user
        $user->delete();

        // Assert that the loan is deleted
        $this->assertDatabaseMissing('loans', ['id' => $loan->id]);
    }
}
