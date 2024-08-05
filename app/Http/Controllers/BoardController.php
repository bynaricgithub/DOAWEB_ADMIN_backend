<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class BoardController extends Controller
{
    public function index()
    {
        try {
            //$result = DB::select("SELECT * FROM circular WHERE '" . Carbon::now() . "' between fromDate AND toDate");
            $result = Board::get();
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'data'   => $result
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'     => 'failure',
                'message'   => 'Problem Fetching Board Member...Error:' . $e->getMessage()
            ], 400);
        }
    }
    public function store(Request $request)
    {
        try {
            // $input = $request->all();
            $validator = Validator::make($request->all(), [
                "name" => "required",
                "post" => "required",
                "description" => "required",
                
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            $result = Board ::create([
                'name' => $request->name,
                'description' => $request->description,
                'post' => $request->post,
                'status' => 1
            ]);
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'message' => 'Board Member added successfully',
                    'data'   => $result
                ], 200);
            } else {
                return response()->json([
                    'status'     => 'failure',
                    'message' => 'Failed to add Board Member',
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
    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "id" => "required|exists:board,id",
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            $result = Board ::where('id', $request->id)->delete();
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'message' => 'Board Member deleted successfully',
                    'data'   => $result
                ], 200);
            } else {
                return response()->json([
                    'status'     => 'failure',
                    'message' => 'Failed to delete Board Member',
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
        
            $input = $request->all();
            $validator = Validator::make($input, [
                "name" => "required",
                "post" => "required",
                "description" => "required",
                "id" => "required"
                
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            //$result = Circular::create($input);
            
            $result = DB::table('board') ->where('id', $request['id']) ->limit(1) ->update( [ 'name' => $request['name'], 'post' => $request['post'], 'description' => $request['description'] ]); 
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'message' => 'Board Member Edited successfully',
                    'data'   => DB::table('board') ->where('id', $request['id']) ->first()
                ], 200);
            } else {
                return response()->json([
                    'status'     => 'failure',
                    'message' => 'Failed to edit Board Member',
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
            $result = DB::table('board') ->where('id', $request->id)->update(['status' => $request->status]);
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'data'   => $result,
                    'message' =>  $request->id == 1 ? "Status enabled successfully" : "Status disabled successfully"
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
