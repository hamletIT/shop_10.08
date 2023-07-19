<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Jetstream;
use GuzzleHttp\Psr7\Request as Req;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController;

class ApiRegisterController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    /**
    * @OA\Post(
    *    path="/api/register/sms",
    *    summary="Registeration via private massage code",
    *    description="",
    *    tags={"User inforamtion Section"},
    *    @OA\Parameter(
    *       name="phone",
    *       in="query",
    *       description="Provide youre phone",
    *       required=true,
    *       allowEmptyValue=true,
    *    ),
    *    @OA\Parameter(
    *       name="name",
    *       in="query",
    *       description="Provide youre name",
    *       required=true,
    *       allowEmptyValue=true,
    *    ),
    *    @OA\Parameter(
    *       name="email",
    *       in="query",
    *       description="Provide youre email",
    *       required=true,
    *       allowEmptyValue=true,
    *    ),
    *    @OA\Parameter(
    *       name="password",
    *       in="query",
    *       description="Provide youre password",
    *       required=true,
    *       allowEmptyValue=true,
    *    ),
    *     @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\MediaType(
    *            mediaType="application/json",
    *        )
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthenticated",
    *     ),
    *     @OA\Response(
    *         response=403,
    *         description="Forbidden"
    *     ),
    *     @OA\Response(
    *         response=429,
    *         description="validation error"
    *     )
    *   ),
    * )
    */
    public function registerBySMS(Request $input)
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

        $randomNumber = rand(config('app.rand_min'),config('app.rand_max'));

        $client = new Client();
        $headers = [
            'Content-Type' => 'application/xml'
        ];
        
        $requestSend = new Req('GET', 'https://smsc.ru/sys/send.php?login='.config('phone.SMSC_LOGIN').'&psw='.config('phone.SMSC_PASSWORD').'&phones='.$input['phone'].'&mes=youre verification code is '.$randomNumber.'', $headers);
        $requestBalance = new Req('GET', 'https://smsc.ru/sys/balance.php?login='.config('phone.SMSC_LOGIN').'&psw='.config('phone.SMSC_PASSWORD').'' , $headers);
        $requestSendCost = new Req('GET', 'https://smsc.ru/sys/send.php?login='.config('phone.SMSC_LOGIN').'&psw='.config('phone.SMSC_PASSWORD').'&phones='.$input['phone'].'&mes=sms price&cost=1' , $headers);
        
        $resSendCost = $client->sendAsync($requestSendCost)->wait();
        $resBalance = $client->sendAsync($requestBalance)->wait();
      
        $send = $client->sendAsync($requestSend)->wait();
        $adminIsset = User::where('name','Admin')->first();

        if($adminIsset == null && $input['name'] == 'Admin'){
            $user = User::create([
                'code' => $randomNumber,
                'status' => 1,
                'phone' => $input['phone'],
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'two_factor_secret' => 'admin',
            ]);
        }
        if($input['name'] == 'admin' || $input['name'] == 'super admin'){
            return response()->json('Can`t create a name '.$input['name']);
        }
        if($input['email'] == 'admin@gmail.com' || $input['email'] == 'admin@mail.ru'){
            return response()->json('Can`t create a email '.$input['email']);
        }
        $user = User::create([
            'code' => $randomNumber,
            'status' => 0,
            'phone' => $input['phone'],
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $token = $user->createToken('Token Name')->accessToken;

        return response()->json(['sms_params'=>$send->getBody()->getContents(),'token'=>$token]);
    }

    public function registerByCall(Request $input)
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

        $randomNumber = rand(config('app.rand_min'),config('app.rand_max'));

        $client = new Client();
        $headers = [
            'Content-Type' => 'application/xml'
        ];
       

        $requestSendCall = new Req('GET', 'https://smsc.ru/sys/wait_call.php?login='.config('phone.SMSC_LOGIN').'&psw='.config('phone.SMSC_PASSWORD').'&phone='.$input['phone'].'', $headers);
        $requestBalance = new Req('GET', 'https://smsc.ru/sys/balance.php?login='.config('phone.SMSC_LOGIN').'&psw='.config('phone.SMSC_PASSWORD').'' , $headers);
        $requestSendCost = new Req('GET', 'https://smsc.ru/sys/send.php?login='.config('phone.SMSC_LOGIN').'&psw='.config('phone.SMSC_PASSWORD').'&phones='.$input['phone'].'&mes=sms price&cost=1' , $headers);

        $resSendCost = $client->sendAsync($requestSendCost)->wait();
        $resBalance = $client->sendAsync($requestBalance)->wait();
        $send = $client->sendAsync($requestSendCall)->wait();

        $user = User::create([
            'code' => $randomNumber,
            'status' => 0,
            'phone' => $input['phone'],
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        return response()->json(['param'=>$send->getBody()->getContents(),'comment'=>' Where:<phone> phone number to which you should make a call within 15 minutes to confirm your phone number.<all_phones> list of all possible phone numbers, one of which used the system to call the subscriber (depending on the country).']);
    }

    /** 
     * @OA\Post(
     *     path="/api/accept/register/code",
     *     summary="Massage confirmation field",
     *     description="",
     *     tags={"User inforamtion Section"},
     *     @OA\Parameter(
     *        name="code",
     *        in="query",
     *        description="Please write here your code that came to your phone",
     *        required=true,
     *       allowEmptyValue=true,
     *     ),
     *     @OA\Parameter(
     *        name="id",
     *        in="query",
     *        description="ID to register user",
     *        required=true,
     *       allowEmptyValue=true,
     *     ),
     *     @OA\Response(
     *        response=200,
     *        description="OK",
     *        @OA\MediaType(
     *            mediaType="application/json",
     *        )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="validation error"
     *     )
     *   ),
     * )
     */
    public function acceptRegisterCode(Request $input)
    {
        
        $rules = [
            'code'   =>'required|numeric|integer',
            'id'   =>'required|numeric|integer',
        ];
        $validator = Validator::make($input->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $userVerifiID = User::where('id',$input['id'])->first();
        $userVerifiCode = User::where('code',$input['code'])->first();
        if($userVerifiID == null){
            return response()->json(['user'=>'Not found']);
        }
        if($userVerifiCode == null){
            return response()->json(['code'=>'Code does not match']);
        }

        $user = User::where('id',$input['id'])->where('code',$input['code'])->update([
            'status'=>1
        ]);
        if ($user) {
            return response()->json(['user'=>User::where('id',$input['id'])->first()]);
        } else {
            return response()->json(['user' => 'No data found']);
        }

    }

    /**
    * @OA\Post(
    *     path="/api/login",
    *     summary="Log in",
    *     description="",
    *     tags={"User inforamtion Section"},
    *     @OA\Parameter(
    *        name="email",
    *        in="query",
    *        description="Please write your e-mail here",
    *        required=true,
    *       allowEmptyValue=true, 
    *     ),
    *     @OA\Parameter(
    *        name="password",
    *        in="query",
    *        description="Please write your password here",
    *        required=true,
    *       allowEmptyValue=true,
    *     ),
    *     @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\MediaType(
    *            mediaType="application/json",
    *        )
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthenticated",
    *     ),
    *     @OA\Response(
    *         response=403,
    *         description="Forbidden"
    *     ),
    *     @OA\Response(
    *         response=429,
    *         description="validation error"
    *     )
    *   ),
    * )
    */
    public function login(Request $request)
    {
        if (!\Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                'message' => 'Login information is invalid.'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('Token Name')->accessToken;
       
        return response()->json(['token' => $token,'user' => $user]);
    }

    /**
    * @OA\Post(
    *     path="/api/logout",
    *     summary="Log out",
    *     description="",
    *     tags={"User inforamtion Section"},
    *     @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\MediaType(
    *            mediaType="application/json",
    *        )
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthenticated",
    *     ),
    *     @OA\Response(
    *         response=403,
    *         description="Forbidden"
    *     ),
    *     @OA\Response(
    *         response=429,
    *         description="validation error"
    *     )
    *   ),
    * )
    */
    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}