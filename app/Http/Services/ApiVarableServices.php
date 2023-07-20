<?php

namespace App\Http\Services;

use App\Models\Carts;
use App\Models\Products;
use App\Models\Options;
use App\Models\Category;
use App\Models\SubCategory;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Req;

class ApiVarableServices
{
    /**
     * Comment => This function returns the standard project layout without the big store
     */
    public function category_CatImg_SubCategory_SubImg_Products_Store_ProdPrice_ProdImg_Option_OptionImg()
    {
        return Category::with(
            [
            'categoryImages',
            'categories',
            'categories.subCategoryImages',
            'categories.products',
            'categories.products.store',
            'categories.products.productPrice',
            'categories.products.productImages',
            'categories.products.productOptions',
            'categories.products.productOptions.optionImages'
            ]
        )->get();
    }

    /**
     * Comment => This function returns the standard project schema
     */
    public function StructureOfTheStandardSchema()
    {
        return [
            'bigStoreImages',
            'categories',
            'categories.categoryImages',
            'categories.categories',
            'categories.categories.subCategoryImages',
            'categories.categories.categories.ChildsubCategoryImages',
            'categories.categories.categories.products',
            'categories.categories.categories.products.store',
            'categories.categories.categories.products.productPrice',
            'categories.categories.categories.products.productImages',
            'categories.categories.categories.products.productOptions',
            'categories.categories.categories.products.productOptions.optionImages'
        ];
    }

    /**
     * Comment => This function returns the filtered category
     * @param ?string $title
     */
    public function filteredCategory($title)
    {
        return Category::Where('title', 'LIKE', '%'.$title.'%')->with(
            [
                'categoryImages',
                'categories.categories',
                'categories.categories.subCategoryImages',
                'categories.categories.categories.ChildsubCategoryImages',
                'categories.categories.categories.products',
                'categories.categories.categories.products.store',
                'categories.categories.categories.products.productPrice',
                'categories.categories.categories.products.productImages',
                'categories.categories.categories.products.productOptions',
                'categories.categories.categories.products.productOptions.optionImages'
            ]
        )->get();
    }

    /**
     * Comment => This function returns the filtered sub category
     * @param ?string $title
     */
    public function filteredSubCategory($title)
    {
        return SubCategory::Where('title', 'LIKE', '%'.$title.'%')->with(
            [
                'categories',
                'categories.ChildsubCategoryImages',
                'categories.products',
                'categories.products.store',
                'categories.products.productPrice',
                'categories.products.productImages',
                'categories.products.productOptions',
                'categories.products.productOptions.optionImages'
            ]
        )->get();
    }

    /**
     * Comment => The function returns categories with photos
     */
    public function categoryAndImages()
    {
        return Category::with(['categoryImages'])->get();
    }

    /**
     * Comment => The function returns child sub categories with photos
     */
    public function childSubCategoryAndImages()
    {
        return [
            'ChildsubCategoryImages','products'
        ];
    }

    /**
     * Comment => This function returns the standard product schema
     */
    public function productsSchema()
    {
        return [
            'productPrice','productImages','productOptions','productOptions.optionImages'
        ];
    }

    /**
     * Comment => This function returns the response from the https://smsc.ru/sms/
     * @param ?string $phone
     * @param int $number
     * @return string The response from the SMS service
     */
    public function requestThatSendsACodeToYourPhone($phone, $number)
    {
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/xml'
        ];

        $requestSend = new Req('GET', 'https://smsc.ru/sys/send.php?login=' . config('phone.SMSC_LOGIN') . '&psw=' . config('phone.SMSC_PASSWORD') . '&phones=' . $phone . '&mes=youre verification code is ' . $number . '', $headers);
        $requestBalance = new Req('GET', 'https://smsc.ru/sys/balance.php?login=' . config('phone.SMSC_LOGIN') . '&psw=' . config('phone.SMSC_PASSWORD') . '', $headers);
        $requestSendCost = new Req('GET', 'https://smsc.ru/sys/send.php?login=' . config('phone.SMSC_LOGIN') . '&psw=' . config('phone.SMSC_PASSWORD') . '&phones=' . $phone . '&mes=sms price&cost=1', $headers);

        // Wait for the responses to complete, this two varables Will be implemented then when will the real test(resSendCost,resBalance)
        $resSendCost = $client->sendAsync($requestSendCost)->wait();
        $resBalance = $client->sendAsync($requestBalance)->wait();
        $send = $client->sendAsync($requestSend)->wait();

        return $send->getBody()->getContents();
    }

    /**
     * Comment => This function returns the response from the https://smsc.ru/sms/
     * @param ?string $phone
     * @return string The response from the SMS service
     */
    public function requestThatCallToYourPhone($phone)
    {
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/xml'
        ];
       
        $requestSendCall = new Req('GET', 'https://smsc.ru/sys/wait_call.php?login='.config('phone.SMSC_LOGIN').'&psw='.config('phone.SMSC_PASSWORD').'&phone='. $phone .'', $headers);
        $requestBalance = new Req('GET', 'https://smsc.ru/sys/balance.php?login='.config('phone.SMSC_LOGIN').'&psw='.config('phone.SMSC_PASSWORD').'' , $headers);
        $requestSendCost = new Req('GET', 'https://smsc.ru/sys/send.php?login='.config('phone.SMSC_LOGIN').'&psw='.config('phone.SMSC_PASSWORD').'&phones='. $phone .'&mes=sms price&cost=1' , $headers);

        // Wait for the responses to complete, this two varables Will be implemented then when will the real test(resSendCost,resBalance)
        $resSendCost = $client->sendAsync($requestSendCost)->wait();
        $resBalance = $client->sendAsync($requestBalance)->wait();
        $send = $client->sendAsync($requestSendCall)->wait();

        return $send->getBody()->getContents();
    }

    /**
     * Comment => This function returns all products from the cart, the price of a specific product and the price of all products
     * @param ?int $userID
     */
    public function getCart($userID)
    {
        $cart = Carts::where('user_id',$userID)->with('product')->get()->groupBy('random_number');
        $randPrcies = [];
        
        foreach($cart as $items)
        {
            $productTable = Products::with('productPrice')->where('id',$items[0]->product_id)->first();
            $prod_price = $productTable->productPrice[0]->productPrice * $items[0]->totalQty;
            $randPrice = 0;

            foreach($items as $item)
            {
                $option_array_price = json_decode($item->array_options);
                $option_price = json_decode($option_array_price->id);
                $option_qty = json_decode($option_array_price->qty);  
                $optionTable = Options::where('id',$option_price)->first();
                $qty_price = $option_qty * $optionTable->price * $items[0]->totalQty; 
                $randPrice += $qty_price;
            }
            $randPrcies['product_id: '.$items[0]->product_id] = $randPrice + $prod_price;
            $product[$items[0]->product_id] = $items[0];
        }
        
        return ['products'=>$product,'product prices'=>$randPrcies,'Total price:'=>array_sum($randPrcies)];  
    }
}