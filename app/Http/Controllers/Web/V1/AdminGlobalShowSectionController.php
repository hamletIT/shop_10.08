<?php

namespace App\Http\Controllers\Web\V1;

use Illuminate\Support\Facades\Session;
use App\Models\Products;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Http\Services\VarableServices;
use Illuminate\Routing\Controller as BaseController;

class AdminGlobalShowSectionController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct(
        public VarableServices $varableServices,
    ) {
       
    }

    public function showBigStore()
    {
        return view('admin.bigStore');
    }

    public function showSubCategory(Request $request)
    {
         return view('admin.subCategory')->with($this->varableServices->getAllTypeofCategories());
    }

    public function showChildSubCategory(Request $request)
    {
        return view('admin.childSubCategory')->with($this->varableServices->getAllTypeofCategories());
    }

    public function showStatistic()
    {
        return view('admin.statistic')->with($this->varableServices->getStatisticSOfYear());
    }

    public function showlanguages()
    {
        $locale = session('locale');
        Session::put('locale',$locale);
        Session::save();

        return view('admin.language');
    }

    public function showProducts()
    {
        return view('admin.products', $this->varableServices->getAllTypeofCategories());
    }

    public function showCategory(Request $request)
    {
       return view('admin.category')->with($this->varableServices->getAllTypeofCategories());
    }

    public function showOption()
    {
        return view('admin.option', $this->varableServices->getUsersProductsStoresOptions());
    }

    public function showStore()
    {
        return view('admin.store', $this->varableServices->getUsersProductsStoresOptions());
    }

    public function showPrductUpdate($id)
    {
        $singleProduct = Products::with('productPrice')->find($id);
       
        return view('admin.updateProduct',compact('singleProduct'));
    }
}
