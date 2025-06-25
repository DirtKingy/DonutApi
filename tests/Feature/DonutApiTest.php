<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\DonutApi;

class DonutApiTest extends TestCase
{
    /**
     * The test method to check if the API endpoint returns all donuts.
     */
    public function test_can_return_all_donuts(): void
    {
        $response = $this->get('/api/donuts');

        $response->assertStatus(200);
    }

    use RefreshDatabase;

    /** 
     * The test method to create a valid donut and assert that it was created successfully.
     */
    public function test_can_create_a_valid_donut()
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

    /** 
     * The test method to validate data with invalid input and ensure validation fails as expected.
     */
    public function test_validation_fails_with_invalid_data()
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

    /** 
     * The test method to delete an existing donut and verify its removal from the database.
     */
    public function test_can_delete_a_donut()
    {
        $donut = DonutApi::factory()->create();

        $response = $this->deleteJson('/api/donuts/' . $donut->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Donut deleted']);

        $this->assertDatabaseMissing('donuts', ['id' => $donut->id]);
    }

    /** 
     * The test method to attempt deletion of a non-existent donut and expect a 404 response.
     */
    public function test_delete_returns_404_if_donut_not_found()
    {
        $response = $this->deleteJson('/api/donuts/99999');

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Donut not found']);
    }

     /** 
      * The test method to sort donuts by name in ascending order and verify the result.
      */
    public function test_can_sort_donuts_by_name_ascending()
    {
        DonutApi::factory()->create(['name' => 'B Donut', 'seal_of_approval' => 3]);
        DonutApi::factory()->create(['name' => 'A Donut', 'seal_of_approval' => 5]);

        $response = $this->getJson('/api/donuts?sort=name&order=asc');

        $response->assertStatus(200);
        $donuts = $response->json();

        $this->assertEquals('A Donut', $donuts[0]['name']);
        $this->assertEquals('B Donut', $donuts[1]['name']);
    }

    /** 
     * The test method to sort donuts by approval rating in descending order and verify the result.
     */
    public function test_can_sort_donuts_by_approval_descending()
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
