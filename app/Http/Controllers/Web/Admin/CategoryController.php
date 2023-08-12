<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\AdminDarta;
use Illuminate\Routing\Controller as BaseController;

class CategoryController extends BaseController
{
    protected $adminData;

    public function __construct(AdminDarta $adminData)
    {
        $this->adminData = $adminData;
    }
    public function showCategory(Request $request)
    {
        $categoris = Category::with('products')->paginate(10);

        return view('admin.category',compact('categoris'));
    }
}