<?php
/** var Factory $factory */
namespace Database\Factories;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Organization::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->company,
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'employer_id' => User::where('role', User::ROLE_EMPLOYER)->inRandomOrder()->first()->id
        ];
    }
}
