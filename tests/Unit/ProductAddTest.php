<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Products;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Req;

class ProductAddTest extends TestCase
{
    // use RefreshDatabase;

    public function testAddProduct()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $client = new Client();
        $request = new Req('GET', 'https://fakestoreapi.com/products');
        $send = $client->sendAsync($request)->wait();
        $body = json_decode($send->getBody()->getContents());

        $trimmedDescription = substr($body[0]->description, 0, 500);
        $productData = [
            'title' => $body[0]->title,
            'description' => $trimmedDescription,
            'image' => $body[0]->image,
            'count' => $body[0]->rating->count ?? 1,
            'updated_at' => now(),
            'created_at' => now(),
        ];

        $viewResponse = Products::insertGetId($productData);
        $viewResponse = $this->get(route('admin.dashboard'));
        $viewResponse->assertStatus(302);
    }
}

