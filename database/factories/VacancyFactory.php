<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

class VacancyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vacancy::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $organization = Organization::inRandomOrder()->first();
        return [
            'vacancy_name' => $this->faker->jobTitle ,
            'workers_amount' => $this->faker->numberBetween($min = 1, $max = 10),
            'salary' => $this->faker->numberBetween($min = 5000, $max = 19000),
            'employer_id' => $organization->employer->id,
            'organization_id' => $organization->id
        ];
    }
}
