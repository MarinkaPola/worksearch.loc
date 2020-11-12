<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class VacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     */
    public function run()
    {
        $vacancies = Vacancy::factory(100)
            ->create();
        foreach ($vacancies as $vacancy) {
            $vacancy->vacancyUsers()->attach(User::where('role', User::ROLE_WORKER)->inRandomOrder()->take(5)->get());

        }
    }

}
