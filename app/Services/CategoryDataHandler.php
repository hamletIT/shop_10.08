<?php

namespace App\Services;

use App\Interfaces\CategoryDataHandlerInterface;
use App\Models\Category;
use App\Models\pivot_categories_products;

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

