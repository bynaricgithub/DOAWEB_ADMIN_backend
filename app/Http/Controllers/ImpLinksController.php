<?php

namespace App\Http\Controllers;

use App\Models\ImpLinks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ImpLinksController extends Controller
{
    public function index()
    {
        try {
            $result = ImpLinks::paginate(10);
            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'data' => $result,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failure',
                'message' => 'Problem Fetching Latest Updates...Error:' . $e->getMessage(),
            ], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'heading' => 'required',
                'file' => 'max:1000',
                'type' => 'required',
                'fromDate' => 'required|date',
                'toDate' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'failure',
                    'message' => $validator->errors()->first(),
                ], 400);
            }
            $url = '';
            if ($request->type == 1) {
                $url = $request->file;
            } else {
                $url = $request->url;
            }

            $result = ImpLinks::create([
                'heading' => $request->heading,
                'type' => $request->type,
                'fromDate' => $request->fromDate,
                'toDate' => $request->toDate,
                'url' => $url,
            ]);
            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Latest update added successfully',
                    'data' => $result,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'failure',
                    'message' => 'Failed to add latest update',
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
                'id' => 'required|exists:impLinks,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'failure',
                    'message' => $validator->errors()->first(),
                ], 400);
            }
            $result = ImpLinks::where('id', $request->id)->delete();
            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Latest update deleted successfully',
                    'data' => $result,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'failure',
                    'message' => 'Failed to delete latest update',
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
            Log::info($input);
            $validator = Validator::make($input, [
                'id' => 'required',
                'heading' => 'required',
                // 'url' => 'required',
                'type' => 'required',
                'fromDate' => 'required|date',
                'toDate' => 'required|date',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'failure',
                    'message' => $validator->errors()->first(),
                ], 400);
            }

            $url = "";
            if ($request->type == 1) {
                if ($request->file) {
                    $url = $request->file;
                } else {
                    $url = $request->url;
                }
            } else {
                $url = $request->url;
            }

            $result = ImpLinks::where('id', $request->id)
                ->update([
                    'heading' => $request->heading,
                    'type' => $request->type,
                    'url' => $url,
                    'fromDate' => $request->fromDate,
                    'toDate' => $request->toDate
                ]);
            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Latest update edited successfully',
                    'data' => ImpLinks::where('id', $request['id'])->first(),
                ], 200);
            } else {
                return response()->json([
                    'status' => 'failure',
                    'message' => 'Failed to edit latest update',
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
            $result = ImpLinks::where('id', $request->id)->update(['status' => $request->status]);
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

    public function search()
    {
        $search_text = $_GET['search'];
        $result = ImpLinks::where('heading', 'LIKE', '%' . $search_text . '%')->get();
    }
}
