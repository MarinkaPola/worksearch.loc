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
    public function run()
    {
        User::factory()->count(2)->create(['role' => User::ROLE_ADMIN]);
        User::factory()->count(14)->create(['role' => User::ROLE_EMPLOYER]);
        User::factory()->count(85)->create(['role' => User::ROLE_WORKER]);
    }
}
