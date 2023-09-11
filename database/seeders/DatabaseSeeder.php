<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Level;
use App\Models\User;
use App\Types\UserTypes;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'first_name' => 'jawad',
            'last_name' => 'taki aldeen',
            'email' => 'jawaduser@gmail.com',
            'password' => '0948966979',
            'user_type' => UserTypes::USER,
            'birthday' => '2002-02-26',
            'phone' => '0948966979'
        ]);
        User::create([
            'first_name' => 'jawad',
            'last_name' => 'taki aldeen',
            'email' => 'jawadadmin@gmail.com',
            'password' => '0948966979',
            'user_type' => UserTypes::ADMIN,
            'birthday' => '2002-02-26',
            'phone' => '0948966979'
        ]);
        User::create([
            'first_name' => 'jawad',
            'last_name' => 'taki aldeen',
            'email' => 'jawadcasher@gmail.com',
            'password' => '0948966979',
            'user_type' => UserTypes::CASHER,
            'birthday' => '2002-02-26',
            'phone' => '0948966979'
        ]);
    }
}
