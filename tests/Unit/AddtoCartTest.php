<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Products;
use App\Models\Carts;
use App\Models\Prices;

use GuzzleHttp\Psr7\Request as Req;

class AddtoCartTest extends TestCase
{
    // use RefreshDatabase;

    public function testCheckout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
    
        $product = Products::factory()->create();
        $data = [
            'product_id' => $product->id,
            'totalQty' => 1,
        ];
       
        Prices::create([
            'product_id' => $product->id,
            'price' => 10,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        $response = $this->post('user/add-to/cart', $data);
        $response->assertStatus(200);
        $viewResponse = $this->get(route('user.cart'));
        $viewResponse->assertStatus(200); // Or other appropriate status code
        $viewResponse->assertViewIs('user.cart');
    }
}

