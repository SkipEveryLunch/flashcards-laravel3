<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\CommentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $comment_types = CommentType::all();
        return [
            "comment_type_id"=>$this->faker->randomElement($comment_types)->id,
            "comment_detail"=>$this->faker->paragraph(2,true),
        ];
    }
}
