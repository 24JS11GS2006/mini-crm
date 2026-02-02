<?php
namespace Database\Factories;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'document_number' => $this->faker->unique()->numerify('#########'),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
        ];
    }
}