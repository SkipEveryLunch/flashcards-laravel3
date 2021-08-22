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

    public function test_no_user_before_register()
    {
        $users = User::get();
        $this->assertEmpty($users);
    }
    public function test_register_returns_code_201()
    {
        $first_name = "John";
        $last_name = "Smith";
        $email = "js@test.io";
        $password = 1234;
        $res = $this->post('/api/register',[
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email"=>$email,
            "password"=>$password
        ]);
        $res->assertStatus(201);
    }
    public function test_register_increases_number_of_user()
    {
        $first_name = "John";
        $last_name = "Smith";
        $email = "js@test.io";
        $password = 1234;
        $numberBefore = User::get()->count(); 
        $res = $this->post('/api/register',[
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email"=>$email,
            "password"=>$password
        ]);
        $numberAfter = User::get()->count();
        $this->assertEquals(1,$numberAfter-$numberBefore);
    }
    public function test_login_works_with_proper_arguments()
    {
        $first_name = "John";
        $last_name = "Smith";
        $email = "js@test.io";
        $password = 1234;
        $this->post('/api/register',[
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email"=>$email,
            "password"=>$password
        ]);
        $res = $this->post('/api/login',[
            "email"=>$email,
            "password"=>$password
        ]);
        $res->assertStatus(200);
    }
    public function test_user_returns_200_when_logged_in()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $res = $this->get('/api/user');
        dump($res);
        $res->assertStatus(200);
    }
}
