<?php

namespace App\Http\Controllers;

use App\Models\Council;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GovCouncilController extends Controller
{
    public function index()
    {
        try {
            $result = Council::paginate(10);
            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'data' => $result,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failure',
                'message' => 'Problem Fetching Member...Error:' . $e->getMessage(),
            ], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            // $input = $request->all();
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'post' => 'required',
                'description' => 'required',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'failure',
                    'message' => $validator->errors()->first(),
                ], 400);
            }
            $result = Council::create([
                'name' => $request->name,
                'description' => $request->description,
                'post' => $request->post,
                'status' => 1,
            ]);
            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Member added successfully',
                    'data' => $result,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'failure',
                    'message' => 'Failed to add Member',
                    'data' => $result,
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failure',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:council,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'failure',
                    'message' => $validator->errors()->first(),
                ], 400);
            }
            $result = Council::where('id', $request->id)->delete();
            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Member deleted successfully',
                    'data' => $result,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'failure',
                    'message' => 'Failed to delete Member',
                    'data' => $result,
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failure',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit(Request $request)
    {

        try {

            $input = $request->all();
            $validator = Validator::make($input, [

                'name' => 'required',
                'post' => 'required',
                'description' => 'required',
                'id' => 'required',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'failure',
                    'message' => $validator->errors()->first(),
                ], 400);
            }
            //$result = Circular::create($input);

            $result = Council::where('id', $request['id'])->update([
                'name' => $request['name'],
                'post' => $request['post'],
                'description' => $request['description']
            ]);
            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Member Edited successfully',
                    'data' => Council::where('id', $request['id'])->first(),
                ], 200);
            } else {
                return response()->json([
                    'status' => 'failure',
                    'message' => 'Failed to edit Member',
                    'data' => $result,
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failure',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function disable(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'status' => 'required',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'failure',
                    'message' => $validator->errors()->first(),
                ], 400);
            }
            $result = DB::table('council')->where('id', $request->id)->update(['status' => $request->status]);
            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'data' => $result,
                    'message' => $request->status == 1 ? 'Status enabled successfully' : 'Status disabled successfully',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failure',
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
