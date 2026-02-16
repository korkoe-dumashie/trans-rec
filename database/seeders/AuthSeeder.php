<?php

namespace Database\Seeders;

use App\Models\{User, Auth};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Auth::create([
                'user_id' => $user->id,
                'staff_id' => $user->staff_id,
                'password' => Hash::make('admin'),
                'reset_password' => true, // Force password reset on first login
            ]);
        }
    }
}
