<?php

namespace Database\Factories;

use App\Models\Section;
use App\Models\User;
use App\Models\Series;
use Illuminate\Database\Eloquent\Factories\Factory;

class SectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Section::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = User::all();
        $series = Series::all();
        return [
            "title"=>$this->faker->word(2,true),
            "description"=>$this->faker->paragraph(2,true),
            "user_id"=>$this->faker->randomElement($users),
            "series_id"=>$this->faker->randomElement($series)
        ];
    }
}