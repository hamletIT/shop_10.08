<?php

namespace App\Jobs;

use App\Interfaces\ProductDataHandlerInterface;
use App\Interfaces\CategoryDataHandlerInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessProductData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dataHandler;
    protected $categoryHandler;

    public function __construct(
        ProductDataHandlerInterface $dataHandler,
        CategoryDataHandlerInterface $categoryHandler
    ) {

        $this->dataHandler = $dataHandler;
        $this->categoryHandler = $categoryHandler;
    }

    public function handle()
    {
        $getProductsData = $this->dataHandler->getProductsData();

        foreach ($getProductsData as $value) {
            $category = $this->categoryHandler->saveCategory([
                'title' => $value['category'],
                'status' => 'active',
                'updated_at' => now(),
                'created_at' => now(),
            ]);

            $trimmedDescription = substr($value['description'], 0, 500);
            $productData = [
                'title' => $value['title'],
                'description' => $trimmedDescription,
                'image' => $value['image'],
                'count' => $value->rating->count ?? 1,
                'updated_at' => now(),
                'created_at' => now(),
            ];

            $product = $this->dataHandler->saveProductData($productData);

            $this->dataHandler->saveProductImages($value['image'], $product);

            $this->dataHandler->saveProductPrice($value['price'], $product);

            $this->dataHandler->saveProductRating($value['rating']['count'] ?? 1, $product, $value['rating']['rate']);

            $this->categoryHandler->saveProductCategoryPivot($category, $product);
        }
    }
}
