<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(), // Автоматически создаст клиента
            'subject' => fake()->sentence(4),
            'body' => fake()->paragraph(3),
            'status' => fake()->randomElement(['new', 'in_progress', 'processed']),
            'manager_response_at' => fake()->optional(0.5) // 50% шанс, что null
            ->dateTimeThisMonth(),
            'created_at' => fake()->dateTimeThisMonth(),
        ];
    }
}
