<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;



class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    private $first_name = "John";
    private $last_name = "Smith";
    private $email = "js@test.io";
    private $password = 1234;

    private $headers  = [
        "Accept"=>"application/json"
    ];

    public function test_no_user_before_register()
    {
        $users = User::get();
        $this->assertEmpty($users);
    }
    public function test_register_returns_code_201()
    {
        $res = $this->post('/api/register',[
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "email"=>$this->email,
            "password"=>$this->password
        ],$this->headers);
        $res->assertStatus(201);
    }
    public function test_register_returns_registered_user()
    {
        $res = $this->post('/api/register',[
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "email"=>$this->email,
            "password"=>$this->password
        ],$this->headers);
        $res->assertJson([
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "email"=>$this->email,
        ]);
    }
    public function test_login_works_with_proper_arguments()
    {
        $this->post('/api/register',[
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "email"=>$this->email,
            "password"=>$this->password
        ],$this->headers);
        $res = $this->post('/api/login',[
            "email"=>$this->email,
            "password"=>$this->password
        ],$this->headers);
        $res->assertStatus(200);
    }
    public function test_user_returns_200_when_logged_in()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $res = $this->get('/api/user',$this->headers);
        $res->assertStatus(200);
    }
    public function test_user_returns_current_user_when_logged_in()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $res = $this->get('/api/user',$this->headers);
        $res->assertJson([
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "email"=>$user->email,
        ]);
    }
    public function test_user_returns_error_when_not_logged_in()
    {
        $res = $this->get('/api/user',$this->headers);
        $res->assertJson([
            "message" => "Unauthenticated."
        ]);
    }
    public function test_logout_returns_error_when_not_logged_in()
    {
        $res = $this->delete('/api/logout',[],$this->headers);
        $res->assertJson([
            "message" => "Unauthenticated."
        ]);
    }
    public function test_logout_returns_message()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $res = $this->delete('/api/logout',[],$this->headers);
        $res->assertJson([
            "jwt" => null,
            "message" => "success"
        ]);
    }
    public function test_user_returns_error_after_logged_out()
    {
        $this->markTestIncomplete(
            'このテストは、まだ実装されていません。'
          );
        // $user = User::factory()->create();
        // Sanctum::actingAs($user);
        // $res = $this->delete('/api/logout',[],$this->headers);
        // $this->app->get('auth')->forgetGuards();
        // $res = $this->get('/api/user',$this->headers);
        // $res->assertJson([
        //     "message" => "Unauthenticated."
        // ]);
    }
}
