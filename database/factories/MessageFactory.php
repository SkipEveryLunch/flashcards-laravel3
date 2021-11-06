<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use App\Models\Comment;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $comment = Comment::inRandomOrder()->first();;
        $question = Question::find($comment->question_id);

        return [
            "title"=>$this->faker->word(2,true),
            "body"=>$this->faker->paragraph(2,true),
            "user_id"=>$question->posted_by,
            "link_type"=>"comment",
            "link_data"=>json_encode([
                "section_id"=>$question->section_id,
                "question_id"=>$question->id,
            ])
        ];
    }
}
