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
    private $front = "Test Question";
    private $back = "This is a Test Question";

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
    public function test_question_store_returns_code_401_when_not_logged_in()
    {
        $section = Section::factory()->create();
        $res = $this->post('/api/questions',[
            "front" => $this->front,
            "back" => $this->back,
            "section_id" => $section->id
        ],$this->headers);
        $res->assertStatus(401);
    }
    public function test_question_store_returns_code_201_when_logged_in()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $section = Section::factory()->create();
        $res = $this->post('/api/questions',[
            "front" => $this->front,
            "back" => $this->back,
            "section_id" => $section->id
        ],$this->headers);
        $res->assertStatus(201);
    }
    public function test_question_store_returns_stored_question()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $section = Section::factory()->create();
        $res = $this->post('/api/questions',[
            "front" => $this->front,
            "back" => $this->back,
            "section_id" => $section->id
        ],$this->headers);
        $res->assertJson([
            "question"=>[
                "front" => $this->front,
                "back" => $this->back,
            ]
        ]);
    }
    public function test_duplicated_questions_returns_409()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $section = Section::factory()->create();
        $this->post('/api/questions',[
          "front" => $this->front,
          "back" => $this->back,
          "section_id" => $section->id
        ],$this->headers);
        $res = $this->post('/api/questions',[
          "front" => $this->front,
          "back" => $this->back,
        ],$this->headers);
        $res->assertStatus(409);
    }
    public function test_question_show_returns_question()
    {
        $section = Section::factory()->create();
        $question = Question::factory()->create([
          "section_id" => $section->id
        ]);
        $res = $this->get('/api/questions/'.$question->id,$this->headers);
        $res->assertJson([
            "question"=>[
              "front" => $question->front,
              "back" => $question->back,
              "section_id" => $section->id
            ]
        ]);
    }
    public function test_question_update_returns_401_when_not_logged_in()
    {
        $updatedFront = "updated front";
        $section = Section::factory()->create();
        $question = Question::factory()->create([
          "section_id" => $section->id
        ]);
        $res = $this->put('/api/questions/'.$question->id,["front"=>$updatedFront],$this->headers);
        $res->assertStatus(401);
    }
    public function test_question_update_returns_202()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $updatedFront = "updated front";
        $section = Section::factory()->create();
        $question = Question::factory()->create([
          "section_id" => $section->id
        ]);
        $res = $this->put('/api/questions/'.$question->id,["front"=>$updatedFront],$this->headers);
        $res->assertStatus(202);
    }
    public function test_question_destroy_returns_401_when_not_logged_in()
    {
        $section = Section::factory()->create();
        $question = Question::factory()->create([
          "section_id" => $section->id
        ]);
        $res = $this->delete('/api/questions/'.$question->id,[],$this->headers);
        $res->assertStatus(401);
    }
    public function test_question_destroy_returns_204()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $section = Section::factory()->create();
        $question = Question::factory()->create([
          "section_id" => $section->id
        ]);
        $res = $this->delete('/api/questions/'.$question->id,[],$this->headers);
        $res->assertStatus(204);
    }
}