<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
       User::create([
        'name' => 'Admin',
        'email' => 'fazle.bcws@gmail.com',
        'password' => 'fazle123',
        'mobile' => '01700000000'
    ]);
    }
}
