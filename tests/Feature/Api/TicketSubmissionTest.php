<?php

namespace Tests\Feature\Api;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TicketSubmissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_submit_a_new_ticket()
    {
        $data = [
            'customer_name' => 'Test User',
            'customer_email' => 'test@user.com',
            'customer_phone' => '+19998887766',
            'subject' => 'Test Subject',
            'body' => 'Test body message',
            'files' => [
                UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
            ],
        ];

        $response = $this->postJson('/api/tickets', $data);

        $response->assertStatus(201)
            ->assertJsonPath('data.subject', 'Test Subject');

        $this->assertDatabaseHas('customers', ['email' => 'test@user.com']);
        $this->assertDatabaseHas('tickets', ['subject' => 'Test Subject']);

        $ticket = Ticket::first();
        $this->assertCount(1, $ticket->getMedia('attachments'));
    }

    /** @test */
    public function ticket_submission_fails_with_invalid_data()
    {
        $response = $this->postJson('/api/tickets', [
            'customer_name' => 'Test',
            'customer_phone' => '12345',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['customer_email', 'customer_phone', 'subject']);
    }

    /** @test */
    public function user_cannot_submit_more_than_one_ticket_per_day()
    {
        $customer = Customer::factory()->create([
            'email' => 'ratelimit@test.com',
            'phone' => '+11112223344',
        ]);
        Ticket::factory()->create([
            'customer_id' => $customer->id,
            'created_at' => now()->subHours(2), // 2 часа назад
        ]);

        $data = [
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => $customer->phone,
            'subject' => 'Second Ticket',
            'body' => 'This should fail',
        ];

        $response = $this->postJson('/api/tickets', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('customer_email'); // Наше правило сработало
    }
}
