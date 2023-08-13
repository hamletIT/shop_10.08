<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Support\Facades\Validator;
use App\Models\BigStores;
use App\Models\User;
use App\Models\Products;
use App\Models\Prices;
use App\Models\Category;
use App\Models\Applications;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Http\Services\VarableServices;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Services\AdminDarta;
use App\Http\Requests\Services\ValidateUserLogin;
use App\Http\Requests\Services\ValidateRegister;


use Illuminate\Routing\Controller as BaseController;

class AuthAdminController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $adminData;

    public function __construct(AdminDarta $adminData)
    {
        $this->adminData = $adminData;
    }

    public function registerShow(Request $request)
    {
        return view('admin.login.register');
    }

    public function loginShow(Request $request)
    {
        return view('admin.login.login');
    }

    public function login(ValidateUserLogin $request)
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
        Auth::login($user);

        return $this->dashboard();
    }

    public function register(ValidateRegister $input)
    {
        $user = User::create([
            'status' => 1,
            'phone' => $input['phone'],
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'two_factor_secret' => 'admin'
        ]);

        $user->createToken('Token Name')->accessToken;
        Auth::login($user);

        return $this->dashboard();
    }

    public function logout(Request $request) 
    {
        auth()->user()->tokens()->delete();

        return view('admin.login.login');
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

    public function dashboardPublic()
    {
        $allProducts = Products::with('productImages','productPrice')->orderBy('created_at','desc')->paginate(10);
        $collection = collect(Prices::pluck('price'));
        $maxValue = $collection->max();
        $minValue = $collection->min();
        $allCategory = Category::get();

        return view('admin.dashboard',compact('allProducts','maxValue','minValue','allCategory'));
    }

}