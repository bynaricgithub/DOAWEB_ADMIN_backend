<?php

namespace App\Http\Controllers;

use App\Models\EventVideos;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class EventVidController extends Controller
{
    public function index()
    {
        try {
            //$result = DB::select("SELECT * FROM circular WHERE '" . Carbon::now() . "' between fromDate AND toDate");
            $result = EventVideos::get();
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'data'   => $result
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'     => 'failure',
                'message'   => 'Problem Fetching Event Videos...Error:' . $e->getMessage()
            ], 400);
        }
    }
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                "name" => "required",
                "description" => "required",
                "url" => "required",
                
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            $result = EventVideos ::create([
                'name' => $request->name,
                'description' => $request->description,
                'url' => $request->url,
                'status' => 1
            ]);
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'message' => 'Event Videos added successfully',
                    'data'   => $result
                ], 200);
            } else {
                return response()->json([
                    'status'     => 'failure',
                    'message' => 'Failed to add Event Videos',
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
                "id" => "required|exists:event_videos,id",
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            $result = EventVideos ::where('id', $request->id)->delete();
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'message' => 'Event Deleted deleted successfully',
                    'data'   => $result
                ], 200);
            } else {
                return response()->json([
                    'status'     => 'failure',
                    'message' => 'Failed to delete Event Videos',
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
                "id" => "required",
                "name" => "required",
                "description" => "required",
                "url" => "",
                
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            //$result = Circular::create($input);
            
            $result = DB::table('event_videos') ->where('id', $request['id']) ->limit(1) ->update( [ 'name' => $request['name'], 'description' => $request['description'], 'url' => $request['url']]); 
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'message' => 'Event Video Edited successfully',
                    'data'   => DB::table('event_videos') ->where('id', $request['id']) ->first()
                ], 200);
            } else {
                return response()->json([
                    'status'     => 'failure',
                    'message' => 'Failed to edit Event Videos',
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
            $result = DB::table('event_videos') -> where('id', $request->id)->update(['status' => $request->status]);
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
