<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Support\Facades\Validator;
use App\Models\BigStores;
use App\Models\User;
use App\Models\Products;
use App\Models\Applications;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Http\Services\VarableServices;
use Illuminate\Support\Facades\Hash;
use Auth;
use Illuminate\Routing\Controller as BaseController;

class AuthAdminController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct(
        public VarableServices $varableServices,
    ) {
       
    }

    public function registerShow(Request $request)
    {
        return view('admin.login.register');
    }

    public function loginShow(Request $request)
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
        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }

    public function register(Request $input)
    {
        $rules = [
            'phone'   =>'required|unique:users',
            'name'    =>'required|string|max:255',
            'email'   =>'required|string|email|max:255|unique:users',
            'password'=>'required|min:6',
        ];
        $validator = Validator::make($input->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->with(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $user = User::create([
            'status' => 1,
            'phone' => $input['phone'],
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'two_factor_secret' => $input['two_factor_secret'],
        ]);

        $token = $user->createToken('Token Name')->accessToken;
        Auth::login($user);

        return view('admin.dashboard');
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
        $allProducts = Products::with('productImages','productPrice')->paginate(10);
        // dd($allProducts);
        return view('admin.dashboard',compact('allProducts'));
    }
    
    public function filter(Request $request)
    {
        $request_name = $request['name'];
        $table_name = $request->table_name;
        $answer = $this->varableServices->getFilterResponse($request_name,$table_name);

        return view('admin.dashboard')->with($answer);
    }
}