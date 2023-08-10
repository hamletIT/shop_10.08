<?php

namespace App\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Routing\Controller as BaseController;

class GoogleController extends BaseController
{
    public function provider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackHandle()
    {
        $user = Socialite::driver('google')->user();
        $data = User::where('email',$user->emaail)->first();
        if (is_null($data)) {
            $users['name'] = $user->name;
            $users['email'] = $user->email;
            $data = User::create($users);
        }
        Auth::login($data);

        return redirect();
    }
}
