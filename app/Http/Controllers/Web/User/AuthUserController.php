<?php

namespace App\Http\Controllers\Web\User;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BigStores;
use App\Models\User;
use App\Models\Prices;
use App\Models\Products;
use App\Models\Applications;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Services\VarableServices;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use Auth;
use App\Services\UserData;
use App\Http\Requests\Services\ValidateUserLogin;
use App\Http\Requests\Services\ValidateRegister;

class AuthUserController extends BaseController
{
    protected $userData;

    public function __construct(UserData $userData)
    {
        $this->userData = $userData;
    }
    public function registerShow(Request $request)
    {
        return view('user.login.register');
    }

    public function loginShow(Request $request)
    {
        return view('user.login.login');
    }
    

    public function login(ValidateUserLogin $request)
    {
        $user = User::where('email', $request['email'])->firstOrFail();
        Auth::login($user);

        return $this->userData->dashboard();
    }

    public function register(ValidateRegister $input)
    {
        $user = User::create([
            'status' => 0,
            'phone' => $input['phone'],
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $user->createToken('Token Name')->accessToken;
        Auth::login($user);

        return $this->userData->dashboard();
    }

    public function logout(Request $request) 
    {
        auth()->user()->tokens()->delete();

        return view('user.login.login');
    }

    public function dashboard() 
    {
        return $this->userData->dashboard();
    }
    
}
