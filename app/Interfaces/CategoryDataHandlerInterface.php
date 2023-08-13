<?php

namespace App\Interfaces;

interface CategoryDataHandlerInterface
{
    public function saveCategory(array $categoryData);
    public function saveProductCategoryPivot(int $categoryID, int $productId);

}
