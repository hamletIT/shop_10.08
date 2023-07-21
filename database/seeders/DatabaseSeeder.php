<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\User;
use App\Models\Prices;
use App\Models\Addresses;
use App\Models\pivot_child_sub_categories;
use App\Models\pivot_sub_categories_products;
use App\Models\pivot_categories_products;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    use AuthorizesRequests, ValidatesRequests;
   
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create([
            'code' => '111111',
            'status' => '1',
            'phone' => '+37477374374',
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('1234567890'),
            'two_factor_secret' => 'admin',
        ]);
        $user->createToken('Token Name')->accessToken;

        DB::table('addresses')->insertGetId([
            'user_id' => $user->id,
            'house_number' => '111',
            'street_name' => 'Bagyan',
            'city' => 'Yerevan',
            'phone' => '+37477374373',
            'status' => 'active',
            'saved_address_status' => 'on',
        ]);

        $bigStore = DB::table('big_stores')->insertGetId([
            'name' => 'test big store',
            'info' => 'test big store info',
            'status' => '001',
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        $category = DB::table('Categories')->insertGetId([
            'big_store_id' => $bigStore,
            'title' => 'with out category',
            'status' => '001',
            'rating' => 'null',
            'photoFileName' => 'null',
            'photoFilePath' => 'null',
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        $subCategoery = DB::table('sub_categories')->insertGetId([
            'title' => 'with out sub category',
            'status' => '001',
            'rating' => 'null',
            'photoFileName' => 'null',
            'photoFilePath' => 'null',
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        $childSubCategory = DB::table('child_sub_categories')->insertGetId([
            'title' => 'with out child sub category',
            'status' => '001',
            'rating' => 'null',
            'photoFileName' => 'null',
            'photoFilePath' => 'null',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        $admin = User::where('two_factor_secret','admin')->first();
        $store = DB::table('stores')->insert([
            'user_id' => $admin->id,
            'name' => '001',
            'info' => 'store info',
            'status' => '0',
            'lng' => 'null',
            'lat' => 'null',
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        $product = DB::table('products')->insertGetId([
            'store_id' => $store,
            'name' => 'Test product',
            'productNumber' => '123456',
            'rating' => 0,
            'color' => 'Test product color',
            'type' => 'Test product type',
            'description' => 'Test product description',
            'photoFileName' => 'null',
            'photoFilePath' => 'null',
            'size' => '1',
            'status' => '1',
            'standardCost' => '1',
            'listprice' => '1',
            'totalPrice' => '1',
            'weight' => '1',
            'totalQty' => '1',
            'sellStartDate' => Carbon::now(config('app.timezone_now'))->toDateTimeString(),
            'sellEndDate' => Carbon::now(config('app.timezone_now'))->addYear()->format('Y-m-d H-i-m'),
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        DB::table('options')->insertGetId([
            'name' => 'Test product option',
            'name_info' => 'Test product option info',
            'status' => '1',
            'price' => '10',
            'photoFilePath' => 'test_test',
            'photoFileName' => 'test_test',
            'product_id' => $product,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        Prices::insertGetId([
            'product_id' => $product,
            'title' => 'Test product',
            'productPrice' => '1',
            'status' => '0',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        pivot_categories_products::create([
            'category_id' => $category,
            'product_id' => $product,
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        
        pivot_sub_categories_products::insertGetId([
            'sub_category_id' => $subCategoery,
            'category_id' => $category,
            'product_id' => $product,
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        
        pivot_child_sub_categories::insertGetId([
                'sub_category_id' => $subCategoery,
                'child_sub_category_id' => $childSubCategory,
                'product_id' => $product,
                'updated_at' => now(),
                'created_at' => now(),
        ]);
    }
}
