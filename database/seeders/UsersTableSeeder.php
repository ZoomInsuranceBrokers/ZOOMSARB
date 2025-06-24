<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'employee_code' => '10096',
            'name' => 'Amit Dixit',
            'email' => 'amit.dixit@zoominsurancebrokers.com',
            'password' => Hash::make('password123'),
            'mobile' => '1234567890',
            'profile' => null,
            'is_active' => true,
            'i_delete' => false,
            'role' => 'admin',
            'remember_token' => Str::random(10),
        ]);
    }
}
