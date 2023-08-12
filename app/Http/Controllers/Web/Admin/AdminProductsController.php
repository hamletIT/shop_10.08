<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Models\pivot_categories_products;
use App\Models\Products;
use App\Models\Prices;
use App\Models\Category;
use App\Models\Rating;
use App\Abstracts\BaseAdminController;
use App\Interfaces\ProductDataHandlerInterface;
use App\Jobs\ProcessProductData;
use App\Http\Requests\Services\ValidateupdateProductFilds;
use App\Http\Requests\Services\ValidateProductId;
use App\Http\Requests\Services\ValidateProductFilter;

class AdminProductsController extends BaseAdminController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct(ProductDataHandlerInterface $dataHandler)
    {
        parent::__construct($dataHandler);
    }

    public function addProduct(Request $request)
    {
        ProcessProductData::dispatch($this->dataHandler);

        return $this->dashboard();
    }

    public function showPrductUpdate($id)
    {
        $singleProduct = Products::with('productPrice','productRating')->find($id);
       
        return view('admin.updateProduct',compact('singleProduct'));
    }

    public function updateProductFilds(ValidateupdateProductFilds $request)
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

    public function deleteProductByID(ValidateProductId $request)
    {
        $product = Products::where('id',$request->product_id)->first();
        $product->delete();

        return $this->dashboard();
    }

    public function filterProduct(ValidateProductFilter $request)
    {
        $query = Products::query();
        $collection = collect(Prices::pluck('price'));
        $maxValue = $request->max_val;
        $minValue = $request->min_val;
        $allCategory = Category::get();

        if ($request->has('title') && $request->title !== null) {
            $query->where('title', 'LIKE', '%' . $request->input('title') . '%');
        }
    
        if ($request->has('category_id') && $request->category_id !== null) {
            $pivotTable = pivot_categories_products::where('category_id', $request->input('category_id'))->get();
            $categoryIds = $pivotTable->pluck('product_id');
            $query->whereIn('id', $categoryIds);
        }
    
        if ($request->has('min_val') && $request->has('max_val') && $request->has('max_val') && $request->has('min_val')) {
            $min = $request->input('min_val');
            $max = $request->input('max_val');
            
            $query->whereHas('productPrice', function ($q) use ($min, $max) {
                $q->where('price', '>', $min)->where('price', '<', $max);
            });
        }
    
        $allProducts = $query->with('productImages','productPrice')->orderBy('created_at','desc')->paginate(10);
    
        return view('user.dashboard',compact('allProducts','maxValue','minValue','allCategory'));
    }

    protected function dashboard()
    {
        $allProducts = Products::with('productImages','productPrice')->orderBy('created_at','desc')->paginate(10);
        $collection = collect(Prices::pluck('price'));
        $maxValue = $collection->max();
        $minValue = $collection->min();
        $allCategory = Category::get();

        return view('admin.dashboard',compact('allProducts','maxValue','minValue','allCategory'));
    }
}