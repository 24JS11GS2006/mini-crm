<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Ticket;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 20 clientes con tickets
        Client::factory(20)->create()->each(function($client){
            // entre 0 y 5 tickets por cliente
            Ticket::factory(rand(1,4))->for($client)->create();
        });
    }
}