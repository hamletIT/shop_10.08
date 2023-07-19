<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Applications;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiApplicationsController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/create/applications",
     *     summary="Request which create an application which automatically send an email to application creater",
     *     description="",
     *     tags={"Application Section"},
     *     @OA\Parameter(
     *        name="name",
     *        in="query",
     *        description="Please write application name",
     *        required=true,
     *        allowEmptyValue=true,
     *     ),
     *     @OA\Parameter(
     *        name="phone",
     *        in="query",
     *        description="Please write phone",
     *        required=true,
     *        allowEmptyValue=true,
     *     ),
     *     @OA\Parameter(
     *        name="password",
     *        in="query",
     *        description="Please write password",
     *        required=true,
     *        allowEmptyValue=true,
     *     ),
     *     @OA\Parameter(
     *        name="text",
     *        in="query",
     *        description="Please write text",
     *        required=true,
     *        allowEmptyValue=true,
     *     ),
     *     @OA\Parameter(
     *        name="email",
     *        in="query",
     *        description="Please write email for send notifi.",
     *        required=true,
     *        allowEmptyValue=true,
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
     *    ),
     * )
     */
    public function createApplications(Request $request) 
    {
        $data = [
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'password' => $request->input('password'),
            'text' => $request->input('text'),
            'updated_at' => now(),
            'created_at' => now(),
        ];
        // Check if all fields are empty
        if (empty(array_filter($data)) && empty($request->input('email'))) {
            return response()->json(['application' => 'Cannot be created because all fields were empty', 'status' => false]);
        }
        // Insert the data into the database
        $createApplication = Applications::insertGetId($data);
        $applications = Applications::find($createApplication);
        $data = [
            'applications' => $applications,
        ];
        $usersMail = $request->input('email');
        try {
            Mail::send('mail.email', $data, function ($message) use ($usersMail) {
                $message
                    ->to($usersMail, 'Administrator of Dstdelivery')
                    ->subject('Dstdelivery');
                $message->from(config('mail.from.address'), 'Dstdelivery');
            });
        } catch (\Throwable $th) {
            return response()->json(['application' => 'Created but not sent email', 'status' => true]);
        }

        return response()->json(['application' => 'Created and sent email', 'status' => true]);
    }

    /**
     * @OA\Get(
     *     path="/api/get/applications",
     *     summary="Request which returns all applications",
     *     description="",
     *     tags={"Application Section"},
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
     *    ),
     * )
     */
    public function getApplications(Request $request) 
    {
        return response()->json(['application'=>Applications::get()]);
    }

    /**
     * @OA\Post(
     *     path="/api/delete/applications",
     *     summary="Request which deletes certain application",
     *     description="",
     *     tags={"Application Section"},
     *     @OA\Parameter(
     *        name="application_id",
     *        in="query",
     *        description="Please write application_id",
     *        required=true,
     *        allowEmptyValue=true,
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
     *    ),
     * )
     */
    public function deleteByIDApplications(Request $request) 
    {
        $rules = [
            'application_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $deletedRows = Applications::where('id', $request->input('application_id'))->delete();

        if ($deletedRows) {
            return response()->json(['application' => 'Deleted', 'status' => true]);
        } else {
            return response()->json(['application' => 'Failed to delete', 'status' => false]);
        }
    }
}
