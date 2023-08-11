<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Support\Facades\Validator;
use App\Models\BigStores;
use App\Models\User;
use App\Models\Applications;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Http\Services\VarableServices;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\pivot_categories_products;
use App\Models\Products;
use App\Models\Prices;
use App\Models\Orders;
use App\Models\Photos;
use App\Models\Category;
use GuzzleHttp\Client;
use App\Models\Rating;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use GuzzleHttp\Psr7\Request as Req;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Controller as BaseController;

class AdminProductsController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function showProducts()
    {
        return view('admin.products');
    }
    public function addProduct(Request $request)
    {
        $client = new Client();
        $request = new Req('GET', 'https://fakestoreapi.com/products');
        $send = $client->sendAsync($request)->wait();
        
        $arrayProducts = $send->getBody()->getContents();
        foreach (json_decode($arrayProducts) as  $value) {
            $category = Category::insertGetId([
                'title' =>$value->category,
                'status'=>'active',
                'updated_at' => now(),
                'created_at' => now(),
            ]);
            $trimmedDescription = substr($value->description, 0, 500);
            $product = Products::insertGetId([
                'title' =>$value->category,
                'description'=>$trimmedDescription,
                'image' => $value->image,
                'count' => $value->rating->count ?? 1,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
            
            $response = Http::get($value->image);
            $destinationPath = 'public/Images';
            $imageData = $response->body();
            $image = Image::make($imageData);
            $filename = uniqid() . '.jpg';
            $image->save(storage_path($destinationPath . '/'. $filename));
            Photos::create([
                'path' => $filename,
                'product_id' => $product,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
            
            Prices::create([
                'product_id' => $product,
                'price' => $value->price,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
            Rating::create([
                'product_id' => $product,
                'count' => $value->rating->count ?? 1,
                'rate' => $value->rating->rate
            ]);
            pivot_categories_products::create([
                'category_id' => $category,
                'product_id' => $product,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }
        $allProducts = Products::paginate(10);

        return view('admin.dashboard',compact('allProducts'));
    }

    public function showPrductUpdate($id)
    {
        $singleProduct = Products::with('productPrice','productRating')->find($id);
       
        return view('admin.updateProduct',compact('singleProduct'));
    }

    public function updateProductFilds(Request $request)
    {
        Products::where('id',$request->id)->update([
            'title' => $request['title'],
            'description' => $request['description'],
            'count' => $request['count'],
        ]);
        $updatedProduct = Products::with('productPrice')->where('id',$request->id)->first();
        Prices::where('product_id',$updatedProduct->id)->update([
            'price'=>$request['price'],
        ]);
        Rating::where('product_id',$updatedProduct->id)->update([
            'count' => $request['count'],
            'rate' => $request['rate'],
        ]);

        return redirect()->back()->with(compact('updatedProduct'));
    }

    public function deleteProductByID(Request $request)
    {
        $rules = [
            'product_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $product = Products::where('id',$request->product_id)->first();
        $product->delete();
        $singleProduct = Products::with('productPrice','productRating')->paginate(10);

        return redirect()->back()->with(compact('singleProduct'));
    }

    public function showAllOrders(Request $request)
    {
        $orders = Orders::with('address_order','users')->paginate(10);

        return view('admin.orders',compact('orders'));
    }

    public function showAllUsers(Request $request)
    {
        $users = User::where('two_factor_secret',null)->paginate(10);

        return view('admin.users',compact('users'));
    }
    
}