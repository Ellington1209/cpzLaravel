<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::insert([
            [
                'name' => 'Ellington Machado',
                'email' => 'ellington1209@gmail.com',
                'name_user' => 'ellington1209',
                'password' => Hash::make('123456'),
                'telefone_celular' => '62991487677',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}