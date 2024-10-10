<?php

namespace App\Http\Controllers;

use App\Models\Regional_offices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class RegionalOfficeController extends Controller

{
    public function index()
    {
        try {
            $result = Regional_offices::paginate(10);
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'data'   => $result
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'     => 'failure',
                'message'   => 'Problem Fetching Events...Error:' . $e->getMessage()
            ], 400);
        }
    }

    public function store(Request $request)
    {
        try {

            $input = $request->all();
            $validator = Validator::make($input, [
                "name" => "required",
                "post" => "required",
                "email" => "required",
                "img_path" => "required",
                "region" => "required",
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }

            $result = Regional_offices::create([
                'name' => $request->name,
                'post' => $request->post,
                'email' => $request->email,
                'img_path' => $request->img_path,
                'region' => $request->region,
            ]);

            if ($result) {
                return response()->json([
                    "status"          => "success",
                    "message"         => "Regional officer Uploaded Successfully...",
                    "data" => $result
                ], 200);
            } else {
                return response()->json([
                    "status"          => "failure",
                    "message"         => "Failed to add Regional officer",
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status"          => "failure",
                "message"         => $e->getMessage(),
            ], 500);
        }
    }
    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "id" => "required",
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            $result = Regional_offices::where('id', $request->id)->delete();
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'message' => 'Record deleted successfully',
                    'data'   => $result
                ], 200);
            } else {
                return response()->json([
                    'status'     => 'failure',
                    'message' => 'Failed to delete record',
                    'data'   => $result
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'     => 'failure',
                'message'   => $e->getMessage()
            ], 500);
        }
    }

    public function edit(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                "id" => "required",
                "name" => "required",
                "post" => "required",
                "email" => "required",
                "img_path" => "required",
                "region" => "required",
            ]);
            if ($validator->fails()) {
                return response()->json([
                    "status"          => "failure",
                    "message"         => $validator->errors()->first()
                ], 400);
            }

            $result = Regional_offices::where('id', $request->id)->update([
                'name' => $request->name,
                'post' => $request->post,
                'email' => $request->email,
                'img_path' => $request->img_path,
                'region' => $request->region,
            ]);

            return response()->json([
                "status"          => "success",
                "message"         => "Regional officers Updated Successfully...",
                "data"            => Regional_offices::where('id', $request->id)->first(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status"          => "failure",
                "message"         => $e->getMessage(),
            ], 400);
        }
    }


    public function disable(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id'        => 'required',
                'status'      => 'required',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    "status"          => "failure",
                    "message"         => $validator->errors()->first(),
                ], 400);
            }
            $result = Regional_offices::where('id', $request->id)->update(['status' => $request->status]);
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'data'   => $result,
                    'message' =>  $request->status == 1 ? "Status enabled successfully" : "Status disabled successfully"
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'     => 'failure',
                'message'   => $e->getMessage()
            ], 400);
        }
    }
}
