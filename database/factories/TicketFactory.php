<?php
namespace Database\Factories;
use App\Models\Ticket;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition()
    {
        $statuses = ['open','in_progress','closed'];
        $priorities = ['low','medium','high'];
        $status = $this->faker->randomElement($statuses);

        return [
            'client_id' => Client::factory(),
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(),
            'status' => $status,
            'priority' => $this->faker->randomElement($priorities),
            'opened_at' => $this->faker->dateTimeBetween('-30 days','now'),
            'closed_at' => $status === 'closed' ? $this->faker->dateTimeBetween('-15 days','now') : null,
        ];
    }
}