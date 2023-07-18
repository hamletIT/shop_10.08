<?php

namespace App\Http\Controllers\Web\V1;

use Illuminate\Support\Facades\Validator;
use App\Models\Products;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Prices;
use App\Models\ChildSubCategory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Http\Services\VarableServices;
use Illuminate\Routing\Controller as BaseController;

class AdminGlobalUpdateSectionController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct(
        public VarableServices $varableServices,
    ) {
       
    }

    public function updateCategory(Request $request)
    {
        $rules = [
            'title'=>'required',
            'category_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $category = Category::where('id',$request['category_id'])->update([
            'title' => $request['title']
        ]);
        $allCategory = Category::get();

        return redirect()->back()->with(compact('allCategory'));
    }

    public function updateSubCategory(Request $request)
    {
        $rules = [
            'title'=>'required',
            'category_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(array('errors' => $validator->getMessageBag()->toArray()));
        }
        SubCategory::where('id',$request['category_id'])->update([
            'title' => $request['title']
        ]);

        return redirect()->back()->with($this->varableServices->getAllTypeofCategories());
    }

    public function updateChildSubCategory(Request $request)
    {
        $rules = [
            'title'=>'required',
            'category_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(array('errors' => $validator->getMessageBag()->toArray()));
        }
        ChildSubCategory::where('id',$request['category_id'])->update([
            'title' => $request['title']
        ]);

        return redirect()->back()->with($this->varableServices->getAllTypeofCategories());
    }

    public function updateProductFilds(Request $request)
    {
        Products::where('productNumber',$request->productNumber)->update([
            'name' => $request['name'],
            'type' => $request['type'],
            'color' => $request['color'],
            'description' => $request['description'],
            'size' => $request['size'],
            'standardCost' => $request['standardCost'],
            'listprice' => $request['listprice'],
            'totalPrice' => $request['totalPrice'],
            'weight' => $request['weight'],
            'totalQty' => $request['totalQty'],
        ]);
        $updatedProduct = Products::with('productPrice')->where('productNumber',$request->productNumber)->first();
        Prices::where('product_id',$updatedProduct->id)->update([
            'productPrice'=>$request['price'],
        ]);

        return redirect()->back()->with(compact('updatedProduct'));
    }
}
