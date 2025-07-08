<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $admin = User::create([
            'name' => 'Core Admin',
            'email' => 'admin@core.id',
            'phone' => '087787156564564',
            'email_verified_at' => \Carbon\Carbon::now(),
            'password' => Hash::make('secret'), // password
            'remember_token' => ''
        ]);
    }
}
