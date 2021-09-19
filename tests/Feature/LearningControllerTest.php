<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Learning;
use App\Models\Question;
use App\Models\Section;
use App\Models\User;
use Laravel\Sanctum\Sanctum;



class LearningControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */

    private $headers  = [
        "Accept"=>"application/json"
    ];

    public function test_no_learning_before_store()
    {
        $questions = Learning::get();
        $this->assertEmpty($questions);
    }
    public function test_new_question_returns_error_when_next_assignment_is_not_yet()
    {
      $number_of_questions = 3;
      $user = User::factory()->create([
        "next_assignment"=>date('Y-m-d', strtotime('+1 day'))
      ]);
      Sanctum::actingAs($user);
      $section = Section::factory()->create();
      $questions = Question::factory($number_of_questions)->create([
        "section_id"=>$section->id
      ]);
      $res = $this->get('/api/sections/'.$section->id.'/new_questions',[
        "number_of_questions"=>$number_of_questions
      ],$this->headers);
      $res->assertJson([
        "message"=>"next assignment isn't yet"
      ]);
    }
    public function test_newQuestion_returns_the_same_number_of_questions_as_required()
    {
      $number_of_questions = 3;
      $user = User::factory()->create([
        "next_assignment"=>date('Y-m-d', strtotime('-1 day'))
      ]);
      Sanctum::actingAs($user);
      $section = Section::factory()->create();
      $questions = Question::factory($number_of_questions)->create([
        "section_id"=>$section->id
      ]);
      $res = $this->get('/api/sections/'.$section->id.'/new_questions',[
        "number_of_questions"=>$number_of_questions
      ],$this->headers);
      $this->assertEquals(count($res["questions"]),$number_of_questions);
    }
    public function test_answerQuestions_returns_the_same_number_of_questions_as_answered()
    {
      $number_of_questions = 3;
      $user = User::factory()->create([
        "next_assignment"=>date('Y-m-d', strtotime('-1 day'))
      ]);
      Sanctum::actingAs($user);
      $section = Section::factory()->create();
      $questions = Question::factory($number_of_questions)->create([
        "section_id"=>$section->id
      ]);
      $qids = [];
      foreach($questions as $question){
        array_push($qids,$question->id);
      }
      $res = $this->post('/api/answer_questions',[
        "question_ids"=>$qids
      ],$this->headers);
      $this->assertEquals(count($res["learnings"]),$number_of_questions);
    }
    public function test_reviewQuestions_returns_only_questions_whose_span_came()
    {
      $number_of_questions = 3;
      $user = User::factory()->create([
        "next_assignment"=>date('Y-m-d', strtotime('-1 day'))
      ]);
      Sanctum::actingAs($user);
      $section = Section::factory()->create();
      $questions = Question::factory(5)->create([
        "section_id"=>$section->id,
      ]);
      foreach($questions as $idx=>$question){
        Learning::factory()->create([
            "question_id"=>$question->id,
            "user_id"=>$user->id,
            "next_period"=>$idx < $number_of_questions? date('Y-m-d', strtotime('-1 day')):date('Y-m-d', strtotime('+1 day'))
        ]);
      }
      $res = $this->get('/api/sections/'.$section->id.'/review_questions',$this->headers);
      $this->assertEquals(count($res["questions"]),$number_of_questions);
    }
    public function test_ranswerReviews_returns_the_same_number_of_questions_as_answered()
    {
      $number_of_questions = 3;
      $user = User::factory()->create([
        "next_assignment"=>date('Y-m-d', strtotime('-1 day'))
      ]);
      Sanctum::actingAs($user);
      $section = Section::factory()->create();
      $questions = Question::factory($number_of_questions)->create([
        "section_id"=>$section->id,
      ]);
      $qIds = [];
      foreach($questions as $question){
        array_push($qIds,$question->id);
        Learning::factory()->create([
            "question_id"=>$question->id,
            "user_id"=>$user->id,
            "next_period"=>date('Y-m-d', strtotime('-1 day'))
        ]);
      }
      $res = $this->post('/api/answer_reviews',[
        "question_ids"=>$qIds 
      ],$this->headers);
      $this->assertEquals(count($res["learnings"]),$number_of_questions);
    }
}