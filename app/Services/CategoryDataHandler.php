<?php

namespace App\Services;

use App\Interfaces\CategoryDataHandlerInterface;
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

class CategoryDataHandler implements CategoryDataHandlerInterface
{
    public function saveCategory(array $categoryData)
    {
        return Category::insertGetId($categoryData);
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

