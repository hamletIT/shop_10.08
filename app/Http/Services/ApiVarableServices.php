<?php

namespace App\Http\Services;

use App\Models\BigStores;
use App\Models\User;
use App\Models\Carts;
use App\Models\Products;
use App\Models\Stores;
use App\Models\Options;
use App\Models\Applications;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildSubCategory;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Req;


class ApiVarableServices
{
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

    public function categoryAndImages()
    {
        return Category::with(['categoryImages'])->get();
    }

    public function childSubCategoryAndImages()
    {
        return [
            'ChildsubCategoryImages','products'
        ];
    }

    public function productsSchema()
    {
        return [
            'productPrice','productImages','productOptions','productOptions.optionImages'
        ];
    }

    /**
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

        // Wait for the responses to complete
        $resSendCost = $client->sendAsync($requestSendCost)->wait();
        $resBalance = $client->sendAsync($requestBalance)->wait();
        $send = $client->sendAsync($requestSend)->wait();

        return $send->getBody()->getContents();
    }

    /**
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

        // Wait for the responses to complete
        $resSendCost = $client->sendAsync($requestSendCost)->wait();
        $resBalance = $client->sendAsync($requestBalance)->wait();
        $send = $client->sendAsync($requestSendCall)->wait();

        return $send->getBody()->getContents();
    }

}