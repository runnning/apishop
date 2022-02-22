<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $user=User::create([
            'name' => 'ywn',
            'email' => '2467634970@qq.com',
            'password' => bcrypt('123456')
        ]);

        $user->assignRole('super-admin');
    }
}
