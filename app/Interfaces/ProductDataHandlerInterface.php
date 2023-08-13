<?php

namespace App\Interfaces;

interface ProductDataHandlerInterface
{
    public function saveProductData(array $productData);
    public function saveProductImages(string $imageUrl, int $productId);
    public function saveProductPrice(int $price, int $productId);
    public function saveProductRating(int $count, int $productId, int $rate);
    public function getProductsData();
}
