<?php

namespace App\Services;

use App\Interfaces\ProductDataHandlerInterface;
use App\Models\Category;
use App\Models\Products;
use App\Models\Photos;
use App\Models\Prices;
use App\Models\Rating;
use App\Models\pivot_categories_products;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Req;

class ProductDataHandler implements ProductDataHandlerInterface
{
    public function getProductsData(): array
    {
        $client = new Client();
        $request = new Req('GET', 'https://fakestoreapi.com/products');
        $send = $client->sendAsync($request)->wait();
        
        return json_decode($send->getBody()->getContents(), true);
    }

    public function saveProductData(array $productData)
    {
        return Products::insertGetId($productData);
    }

    public function saveProductImages(string $imageUrl, int $productId)
    {
        $response = Http::get($imageUrl);
        $destinationPath = 'public/Images';
        $imageData = $response->body();
        $image = Image::make($imageData);
        $filename = uniqid() . '.jpg';
        $image->save(storage_path($destinationPath . '/' . $filename));
        
        Photos::create([
            'path' => $filename,
            'product_id' => $productId,
            'updated_at' => now(),
            'created_at' => now(),
        ]);
    }

    public function saveCategory(array $categoryData)
    {
        return Category::insertGetId($categoryData);
    }
    public function saveProductPrice(int $price, int $productId)
    {
        Prices::create([
            'product_id' => $productId,
            'price' => $price,
            'updated_at' => now(),
            'created_at' => now(),
        ]);
    }

    public function saveProductRating(int $count, int $productId, int $rate)
    {
        Rating::create([
            'product_id' => $productId,
            'count' => $count,
            'rate' => $rate,
        ]);
    }

    public function saveProductCategoryPivot(int $categoryID, int $productId)
    {
        pivot_categories_products::create([
            'category_id' => $categoryID,
            'product_id' => $productId,
            'updated_at' => now(),
            'created_at' => now(),
        ]);
    }
}
