<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            "first_name"=>"John",
            "last_name"=>"Smith",
            "email"=>"js@test.io",
            "role_id"=>1
        ]);
        User::factory(4)->create([
            "role_id"=>2
        ]);
    }
}