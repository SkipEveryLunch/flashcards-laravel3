<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Question;
use App\Models\Section;
use App\Models\User;
use Laravel\Sanctum\Sanctum;



class QuestionControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    private $title = "Test Section";

    private $headers  = [
        "Accept"=>"application/json"
    ];

    public function test_no_question_before_store()
    {
        $questions = Question::get();
        $this->assertEmpty($questions);
    }
    public function test_question_index_returns_200()
    {
      $user = User::factory()->create();
      Sanctum::actingAs($user);
      $section = Section::factory()->create();
      $questions = Question::factory(3)->create([
        "section_id"=>$section->id
      ]);
      $res = $this->get('/api/questions',$this->headers);
      $res->assertStatus(200);
    }
}