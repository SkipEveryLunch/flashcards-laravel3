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
}
