<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            "name" => "Andi Wahyu",
            "email" => "a.wahyukhusnulmalik@gmail.com",
            "password" => Hash::make("1234567890")
        ]);
    }
}
