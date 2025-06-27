<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\DonutApi;

class DonutApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_return_paginated_donuts()
    {
        DonutApi::factory()->count(15)->create();

        $response = $this->getJson('/api/donuts');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'links',
            'meta',
        ]);
        $this->assertCount(10, $response->json('data')); // Default pagination = 10
    }

    public function test_can_create_a_valid_donut_with_image()
    {
        Storage::fake('public');

        $payload = [
            'name' => 'Test Donut',
            'seal_of_approval' => 4,
            'price' => 7.5,
            'image' => UploadedFile::fake()->image('donut.jpg'),
        ];

        $response = $this->postJson('/api/donuts', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Test Donut']);

        $this->assertDatabaseHas('donuts', ['name' => 'Test Donut']);
    }

    public function test_validation_fails_with_invalid_data()
    {
        $payload = [
            'name' => '',
            'seal_of_approval' => 10,
            'price' => -5,
        ];

        $response = $this->postJson('/api/donuts', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'seal_of_approval', 'price']);
    }

    public function test_can_delete_a_donut()
    {
        $donut = DonutApi::factory()->create();

        $response = $this->deleteJson('/api/donuts/' . $donut->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Donut deleted']);

        $this->assertDatabaseMissing('donuts', ['id' => $donut->id]);
    }

    public function test_delete_returns_404_if_donut_not_found()
    {
        $response = $this->deleteJson('/api/donuts/99999');

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Donut not found']);
    }

    public function test_can_sort_donuts_by_name_ascending()
    {
        DonutApi::factory()->create(['name' => 'B Donut', 'seal_of_approval' => 3]);
        DonutApi::factory()->create(['name' => 'A Donut', 'seal_of_approval' => 5]);

        $response = $this->getJson('/api/donuts?sort=name&order=asc');

        $response->assertStatus(200);
        $this->assertEquals('A Donut', $response->json('data.0.name'));
    }

    public function test_can_sort_donuts_by_approval_descending()
    {
        DonutApi::factory()->create(['name' => 'Donut One', 'seal_of_approval' => 2]);
        DonutApi::factory()->create(['name' => 'Donut Two', 'seal_of_approval' => 5]);

        $response = $this->getJson('/api/donuts?sort=approval&order=desc');

        $response->assertStatus(200);
        $this->assertEquals(5, $response->json('data.0.seal_of_approval'));
    }
}
