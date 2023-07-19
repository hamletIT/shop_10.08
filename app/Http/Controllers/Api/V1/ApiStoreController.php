<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Stores;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ApiStoreController extends BaseController
{
    /**
    * @OA\Post(
    *    path="/api/create/store",
    *    summary="Store which consists of [status, name, info, user ID]",
    *    description="",
    *    tags={"Store Section"},
    *    @OA\Parameter(
    *       name="status",
    *       in="query",
    *       description="Please write store status",
    *       required=true,
    *       allowEmptyValue=true,
    *    ),
    *    @OA\Parameter(
    *       name="name",
    *       in="query",
    *       description="Please write store name",
    *       required=true,
    *       allowEmptyValue=true,
    *    ),
    *    @OA\Parameter(
    *       name="info",
    *       in="query",
    *       description="Please write store info",
    *       required=true,
    *       allowEmptyValue=true,
    *    ),
    *    @OA\Parameter(
    *       name="user_id",
    *       in="query",
    *       description="Please write store user ID",
    *       required=true,
    *       allowEmptyValue=true,
    *    ),
    *    @OA\Response(
    *       response=200,
    *       description="OK",
    *       @OA\MediaType(
    *           mediaType="application/json",
    *       )
    *    ),
    *    @OA\Response(
    *        response=401,
    *        description="Unauthenticated",
    *    ),
    *    @OA\Response(
    *        response=403,
    *        description="Forbidden"
    *    ),
    *    @OA\Response(
    *        response=429,
    *        description="validation error"
    *    )
    *  ),
    * )
    */
    public function createStore(Request $request)
    {
        $rules = [
            'name' => 'required',
            'status' => 'required',
            'info' => 'required',
            'user_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $user = User::where('id',$request['user_id'])->first();

        if ($user !== null) {
            $store = Stores::insertGetId([
                'name' => $request['name'],
                'status' => $request['status'],
                'info' => $request['info'],
                'user_id' => $request['user_id'],
                'updated_at' => now(),
                'created_at' => now(),
            ]);

            return response()->json(['store'=>Stores::find($store)]);
        } else {

            return response()->json(['user'=>$request['user_id'].' user not found' ]);
        }
    }

    /**
    * @OA\Get(
    *     path="/api/get/store",
    *     summary="Request that returns store by [ID]",
    *     description="",
    *     tags={"Store Section"},
    *     @OA\Parameter(
    *        name="store_id",
    *        in="query",
    *        description="Please write store ID",
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
    *    @OA\Response(
    *        response=401,
    *        description="Unauthenticated",
    *    ),
    *    @OA\Response(
    *        response=403,
    *        description="Forbidden"
    *    ),
    *    @OA\Response(
    *        response=429,
    *        description="validation error"
    *    )
    *  ),
    * )
    */
    public function getStore(Request $request)
    {
        $rules = [
            'store_id' => 'required|numeric|integer',
           
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        return response()->json(['store'=>Stores::find($request['store_id'])]);
    }

    /**
    * @OA\Get(
    *    path="/api/get/all/store",
    *    summary="Request that returns all stores",
    *    description="",
    *    tags={"Store Section"},
    *    @OA\Response(
    *       response=200,
    *       description="OK",
    *       @OA\MediaType(
    *           mediaType="application/json",
    *       )
    *    ),
    *    @OA\Response(
    *        response=401,
    *        description="Unauthenticated",
    *    ),
    *    @OA\Response(
    *        response=403,
    *        description="Forbidden"
    *    ),
    *    @OA\Response(
    *        response=429,
    *        description="validation error"
    *    )
    *  ),
    * )
     */
    public function getAllStores(Request $request)
    {
        return response()->json(['store'=>Stores::get()]);
    }

    /**
    * @OA\Post(
    *    path="/api/delete/store",
    *    summary="Request that deletes store by [ID]",
    *    description="",
    *    tags={"Store Section"},
    *    @OA\Parameter(
    *       name="store_id",
    *       in="query",
    *       description="Please write store ID",
    *       required=true,
    *       allowEmptyValue=true,
    *    ),
    *    @OA\Response(
    *       response=200,
    *       description="OK",
    *       @OA\MediaType(
    *           mediaType="application/json",
    *       )
    *    ),
    *    @OA\Response(
    *        response=401,
    *        description="Unauthenticated",
    *    ),
    *    @OA\Response(
    *        response=403,
    *        description="Forbidden"
    *    ),
    *    @OA\Response(
    *        response=429,
    *        description="validation error"
    *    )
    *  ),
    * )
    */
    public function deleteStore(Request $request)
    {
        $rules = [
            'store_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $store = Stores::where('id',$request['store_id'])->first();
        if($store == null){
            return response()->json(['store' =>$request['store_id'].'id store not found']);
        }
        
        return response()->json(['status'=>Stores::where('id',$request['store_id'])->delete()]);
    }

    /**
    * @OA\Get(
    *    path="/api/filter/store",
    *    summary="Request that Filters store by [name]",
    *    description="",
    *    tags={"Store Section"},
    *    @OA\Parameter(
    *       name="name",
    *       in="query",
    *       description="Please write store name",
    *       required=true,
    *       allowEmptyValue=true,
    *    ),
    *    @OA\Response(
    *       response=200,
    *       description="OK",
    *       @OA\MediaType(
    *           mediaType="application/json",
    *       )
    *    ),
    *    @OA\Response(
    *        response=401,
    *        description="Unauthenticated",
    *    ),
    *    @OA\Response(
    *        response=403,
    *        description="Forbidden"
    *    ),
    *    @OA\Response(
    *        response=429,
    *        description="validation error"
    *    )
    *  ),
    * )
    */
    public function filterStore(Request $request)
    {
        if (!is_null($request['name'])) {
            $storeFilter = Stores::where('name', 'LIKE', '%'.$request['name'].'%')->get();

            return response()->json(['stores' => $storeFilter]);
        }

        return response()->json(['options' => []]);
    }

    /**
    * @OA\Get(
    *     path="/api/get/all/stores/unAuth",
    *     summary="Request that returns all stores with out user information",
    *     description="",
    *     tags={"Store Section"},
    *     @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\MediaType(
    *            mediaType="application/json",
    *        )
    *     ),
    *    @OA\Response(
    *        response=401,
    *        description="Unauthenticated",
    *    ),
    *    @OA\Response(
    *        response=403,
    *        description="Forbidden"
    *    ),
    *    @OA\Response(
    *        response=429,
    *        description="validation error"
    *    )
    *  ),
    * )
    */
    public function getAllStoreUnAuth(Request $request)
    {
        return response()->json(['store'=>Stores::get()]);
    }

        /**
    * @OA\Get(
    *     path="/api/get/single/store/unAuth",
    *     summary="Request that returns single store with out user information",
    *     description="",
    *     tags={"Store Section"},
    *    @OA\Parameter(
    *       name="store_id",
    *       in="query",
    *       description="Please write store ID",
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
    *    @OA\Response(
    *        response=401,
    *        description="Unauthenticated",
    *    ),
    *    @OA\Response(
    *        response=403,
    *        description="Forbidden"
    *    ),
    *    @OA\Response(
    *        response=429,
    *        description="validation error"
    *    )
    *  ),
    * )
    */
    public function getSingleStoreUnAuth(Request $request)
    {
         $rules = [
            'store_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $store = Stores::where('id',$request['store_id'])->first();
        if($store == null){
            return response()->json(['store' =>$request['store_id'].' id store not found']);
        }
        return response()->json(['store'=>Stores::where('id',$request['store_id'])->get()]);
    }
}