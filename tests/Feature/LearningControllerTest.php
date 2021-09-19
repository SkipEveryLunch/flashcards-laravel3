<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Learning;
use App\Models\Question;
use App\Models\Section;
use App\Models\SectionRestriction;
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
      $user = User::factory()->create();
      Sanctum::actingAs($user);
      $section = Section::factory()->create();
      $questions = Question::factory($number_of_questions)->create([
        "section_id"=>$section->id
      ]);
      SectionRestriction::create([
        "user_id"=>$user->id,
        "section_id"=>$section->id,
        "next_assignment"=>date('Y-m-d', strtotime('+1 day'))
      ]);
      $res = $this->get('/api/sections/'.$section->id.'/new_questions',[
        "number_of_questions"=>$number_of_questions
      ],$this->headers);
      $res->assertJson([
        "message"=>"next assignment isn't yet"
      ]);
    }
    public function test_new_question_returns_the_same_number_of_questions_when_next_assingment_has_come()
    {
      $number_of_questions = 3;
      $user = User::factory()->create();
      Sanctum::actingAs($user);
      $section = Section::factory()->create();
      $questions = Question::factory($number_of_questions)->create([
        "section_id"=>$section->id
      ]);
      SectionRestriction::create([
        "user_id"=>$user->id,
        "section_id"=>$section->id,
        "next_assignment"=>date('Y-m-d', strtotime('-1 day'))
      ]);
      $res = $this->get('/api/sections/'.$section->id.'/new_questions',[
        "number_of_questions"=>$number_of_questions
      ],$this->headers);
      $this->assertEquals(count($res["questions"]),$number_of_questions);
    }
    public function test_answerQuestions_returns_the_same_number_of_questions_as_answered()
    {
      $number_of_questions = 3;
      $user = User::factory()->create();
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
    public function test_answerQuestions_updates_next_period_of_learning()
    {
      $user = User::factory()->create();
      Sanctum::actingAs($user);
      $section = Section::factory()->create();
      $question = Question::factory()->create([
        "section_id"=>$section->id
      ])->first();
      $this->post('/api/answer_questions',[
        "question_ids"=>[$question->id]
      ],$this->headers);
      $learning = Learning::where("user_id","=",$user->id)->where("question_id","=",$question->id)->first();
      $this->assertEquals(date('Y-m-d', strtotime('+1 day')),$learning->next_period);
    }
    public function test_reviewQuestions_returns_only_questions_whose_span_came()
    {
      $number_of_questions = 3;
      $user = User::factory()->create();
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
    public function test_answerReviews_returns_the_same_number_of_questions_as_answered()
    {
      $number_of_questions = 3;
      $user = User::factory()->create();
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
    public function test_answerReviews_updates_next_period_of_learning()
    {
      $user = User::factory()->create();
      Sanctum::actingAs($user);
      $section = Section::factory()->create();
      $question = Question::factory()->create([
        "section_id"=>$section->id,
      ])->first();
      Learning::factory()->create([
          "question_id"=>$question->id,
          "user_id"=>$user->id,
          "next_period"=>date('Y-m-d', strtotime('-1 day'))
      ]);
      $res = $this->post('/api/answer_reviews',[
        "question_ids"=>[$question->id]
      ],$this->headers);
      $learning = Learning::where("user_id","=",$user->id)->where("question_id","=",$question->id)->first();
      $this->assertEquals(date('Y-m-d', strtotime('+1 day')),$learning->next_period);
    }
}