<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Products;
use App\Models\Prices;
use App\Models\Rating;

class UserTest extends TestCase
{
    // use RefreshDatabase;

    public function testUpdateProductFields()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Products::factory()->create();
        Prices::factory()->create(['product_id' => $product->id]);
        Rating::factory()->create(['product_id' => $product->id]);

        $updatedData = [
            'id' => $product->id,
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'count' => 10,
            'price' => 99.99,
            'rate' => 4,
        ];
        $response = $this->post('admin/update-product-filds', $updatedData);
        $response->assertRedirect('/show-product-update/'.$product->id);
    }
}

