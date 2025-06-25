<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\DonutApi;

class DonutApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    use RefreshDatabase;

    /** @test */
    public function can_create_a_valid_donut()
    {
        $payload = [
            'name' => 'Test Donut',
            'seal_of_approval' => 4,
            'price' => 7.5,
        ];

        $response = $this->postJson('/api/donuts', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Test Donut']);

        $this->assertDatabaseHas('donuts', ['name' => 'Test Donut']);
    }

    /** @test */
    public function validation_fails_with_invalid_data()
    {
        $payload = [
            'name' => '',
            'seal_of_approval' => 10,
            'price' => -5,
        ];

        $response = $this->postJson('/api/donuts', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'seal_of_approval', 'price']);
    }

    /** @test */
    public function can_delete_a_donut()
    {
        $donut = DonutApi::factory()->create();

        $response = $this->deleteJson('/api/donuts/' . $donut->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Donut deleted']);

        $this->assertDatabaseMissing('donuts', ['id' => $donut->id]);
    }

    /** @test */
    public function delete_returns_404_if_donut_not_found()
    {
        $response = $this->deleteJson('/api/donuts/99999');

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Donut not found']);
    }

     /** @test */
    public function can_sort_donuts_by_name_ascending()
    {
        DonutApi::factory()->create(['name' => 'B Donut', 'seal_of_approval' => 3]);
        DonutApi::factory()->create(['name' => 'A Donut', 'seal_of_approval' => 5]);

        $response = $this->getJson('/api/donuts?sort=name&order=asc');

        $response->assertStatus(200);
        $donuts = $response->json();

        $this->assertEquals('A Donut', $donuts[0]['name']);
        $this->assertEquals('B Donut', $donuts[1]['name']);
    }

    /** @test */
    public function can_sort_donuts_by_approval_descending()
    {
        DonutApi::factory()->create(['name' => 'Donut One', 'seal_of_approval' => 2]);
        DonutApi::factory()->create(['name' => 'Donut Two', 'seal_of_approval' => 5]);

        $response = $this->getJson('/api/donuts?sort=approval&order=desc');

        $response->assertStatus(200);
        $donuts = $response->json();

        $this->assertEquals(5, $donuts[0]['seal_of_approval']);
        $this->assertEquals(2, $donuts[1]['seal_of_approval']);
    }
}
