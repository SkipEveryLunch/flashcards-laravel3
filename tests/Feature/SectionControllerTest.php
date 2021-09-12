<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Section;
use App\Models\User;
use Laravel\Sanctum\Sanctum;



class SectionControllerTest extends TestCase
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

    public function test_no_section_before_register()
    {
        $sections = Section::get();
        $this->assertEmpty($sections);
    }
    public function test_section_store_returns_code_401_when_not_logged_in()
    {
        $res = $this->post('/api/sections',[
            "title" => $this->title,
        ],$this->headers);
        $res->assertStatus(401);
    }
    public function test_section_store_returns_code_201_when_logged_in()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $res = $this->post('/api/sections',[
            "title" => $this->title,
        ],$this->headers);
        $res->assertStatus(201);
    }
    public function test_section_store_returns_stored_section()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $res = $this->post('/api/sections',[
            "title" => $this->title,
        ],$this->headers);
        $res->assertJson([
            "section"=>[
                "title" => $this->title,
            ]
        ]);
    }
    public function test_duplicated_sections_returns_409()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $this->post('/api/sections',[
            "title" => $this->title,
        ],$this->headers);
        $res = $this->post('/api/sections',[
            "title" => $this->title,
        ],$this->headers);
        $res->assertStatus(409);
    }
    public function test_section_show_returns_section()
    {
        $section = Section::factory()->create();
        $res = $this->get('/api/sections/'.$section->id,$this->headers);
        $res->assertJson([
            "section"=>[
                "title" => $section->title,
            ]
        ]);
    }
    public function test_section_update_returns_401_when_not_logged_in()
    {
        $updatedTitle = "updated title";
        $section = Section::factory()->create();
        $res = $this->put('/api/sections/'.$section->id,["title"=>$updatedTitle],$this->headers);
        $res->assertStatus(401);
    }
    public function test_section_update_returns_202()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $updatedTitle = "updated title";
        $section = Section::factory()->create();
        $res = $this->put('/api/sections/'.$section->id,["title"=>$updatedTitle],$this->headers);
        $res->assertStatus(202);
    }
    public function test_section_destroy_returns_401_when_not_logged_in()
    {
        $section = Section::factory()->create();
        $res = $this->delete('/api/sections/'.$section->id,[],$this->headers);
        $res->assertStatus(401);
    }
    public function test_section_destroy_returns_204()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $section = Section::factory()->create();
        $res = $this->delete('/api/sections/'.$section->id,[],$this->headers);
        $res->assertStatus(204);
    }
}