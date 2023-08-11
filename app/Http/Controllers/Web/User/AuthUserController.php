<?php

namespace App\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BigStores;
use App\Models\User;
use App\Models\Products;
use App\Models\Applications;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Services\VarableServices;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use Auth;

class AuthUserController extends BaseController
{
    public function registerShow(Request $request)
    {
        return view('user.login.register');
    }

    public function loginShow(Request $request)
    {
        return view('user.login.login');
    }
    public function dashboard()
    {
        $allProducts = Products::with('productImages','productPrice')->orderBy('created_at','desc')->paginate(10);
        // dd($allProducts);
        return view('user.dashboard',compact('allProducts'));
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

        $allProducts = Products::with('productImages','productPrice')->orderBy('created_at','desc')->paginate(10);
        // dd($allProducts);
        return view('user.dashboard',compact('allProducts'));
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
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $user = User::create([
            'status' => 0,
            'phone' => $input['phone'],
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $token = $user->createToken('Token Name')->accessToken;
        Auth::login($user);

        return view('user.dashboard');
    }

    public function logout(Request $request) 
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
