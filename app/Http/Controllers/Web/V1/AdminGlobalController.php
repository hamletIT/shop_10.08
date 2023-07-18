<?php

namespace App\Http\Controllers\Web\V1;

use Illuminate\Support\Facades\Validator;
use App\Models\BigStores;
use App\Models\User;
use App\Models\Applications;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Http\Services\VarableServices;
use Illuminate\Routing\Controller as BaseController;

class AdminGlobalController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct(
        public VarableServices $varableServices,
    ) {
       
    }

    public function showForm()
    {
        return view('admin.login.login');
    }

    public function login(Request $request)
    {
        $rules = [
                  'email'   => 'required|email',
                  'password' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return redirect()->back()->with(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        if($user->two_factor_secret == 'admin'){
            $token = $user->createToken('Token Name')->accessToken;
            $application = Applications::get();

            return redirect()->route('admin.dashboard',compact('application'));
        }
        $array = array('Access denied');
        
        return redirect()->back()->with(array('errors'=> $array));
    }

    public function logout(Request $request) 
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }

    public function dashboard()
    {
        // $category = BigStores::with(
        //     [
        //     'bigStoreImages',
        //     'categories',
        //     'categories.categoryImages',
        //     'categories.categories',
        //     'categories.categories.subCategoryImages',
        //     'categories.categories.categories.ChildsubCategoryImages',
        //     'categories.categories.categories.products',
        //     'categories.categories.categories.products',
        //     'categories.categories.categories.products.store',
        //     'categories.categories.categories.products.productPrice',
        //     'categories.categories.categories.products.productImages',
        //     'categories.categories.categories.products.productOptions',
        //     'categories.categories.categories.products.productOptions.optionImages'
        //     ]
        // )->get();

        // return response()->json(['categories'=>$category]);

        return view('admin.dashboard')->with($this->varableServices->getdashboardVarables());
    }
    
    public function filter(Request $request)
    {
        $request_name = $request['name'];
        $table_name = $request->table_name;
        $answer = $this->varableServices->getFilterResponse($request_name,$table_name);

        return view('admin.dashboard')->with($answer);
    }
}