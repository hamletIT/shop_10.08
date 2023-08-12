<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Products;
use App\Models\Prices;
use App\Models\Rating;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testUpdateProductFields()
    {
        // Create a user and log in
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a product and associated data
        $product = Products::factory()->create();
        Prices::factory()->create(['product_id' => $product->id]);
        Rating::factory()->create(['product_id' => $product->id]);

        // Simulate a form submission with updated data
        $updatedData = [
            'id' => $product->id,
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'count' => 10,
            'price' => 99.99,
            'rate' => 4.5,
        ];

        // Act: Send an HTTP request to the update route
        $response = $this->post('/update-product-fields', $updatedData);

        // Assert: Check the response status and redirection
        $response->assertStatus(302); // 302 is the HTTP status code for redirection

        // Assert: Check any other assertions you need based on the response
        // For example, you can assert that the updated product's attributes are as expected.

        // You can also assert that the database records were updated correctly
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'title' => $updatedData['title'],
            'description' => $updatedData['description'],
            'count' => $updatedData['count'],
        ]);

        // ... (assertions for other related models)

        // You can also assert that the session has the expected values, if your controller redirects back with a session message.

        // Finally, you can assert the redirection URL if needed.
        // For example, if the redirection is based on your application's logic.
    }
}

