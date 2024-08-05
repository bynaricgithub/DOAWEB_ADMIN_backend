<?php

namespace App\Http\Controllers;

use App\Models\Circular;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;


class CircularController extends Controller
{
    public function index()
    {
        try {
            //$result = DB::select("SELECT * FROM circular WHERE '" . Carbon::now() . "' between fromDate AND toDate");
            $result = Circular::get();
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'data'   => $result
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'     => 'failure',
                'message'   => 'Problem Fetching Circulars...Error:' . $e->getMessage()
            ], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                "heading" => "required",
                "type" => "required",
                "fromDate" => "required|date",
                "toDate" => "required|date",
                "file" => 'required_if:type,1',
                "url" => 'required_if:type,2',
                "date" => "required|date",
                "category" => "required",
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            $path           = public_path() . '/data/circular/';
            $record = [
                'heading' => $request->heading,
                'fromDate' => $request->fromDate,
                'toDate' => $request->toDate,
                'date' => $request->date,
                'category' => $request->category,
            ];
            if ($request->type == 2) {
                $record['url'] = $request->url;
            }
            $result = Circular::create($record);
            if ($result) {
                if ($request->type == 1) {
                    $extension = File::extension($request->file->getClientOriginalName());

                    $file          = 'Doc_' . $result->id . '.' . $extension;
                    $serverPath     = Config::get('constants.PROJURL') . '/data/circular/' . $file;
                    File::ensureDirectoryExists($path);
                    $request->file->move($path, $file);
                    $result->url = $serverPath;
                    $result->save();
                }

                return response()->json([
                    'status'     => 'success',
                    'message' => 'circular added successfully',
                    'data'   => $result
                ], 200);
            } else {
                return response()->json([
                    'status'     => 'failure',
                    'message' => 'Failed to add circular',
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
                "id" => "required|exists:circular,id",
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            $result = Circular::where('id', $request->id)->delete();
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'message' => 'Circular deleted successfully',
                    'data'   => $result
                ], 200);
            } else {
                return response()->json([
                    'status'     => 'failure',
                    'message' => 'Failed to delete circular',
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
                "heading" => "required",
                "type" => "required",
                "fromDate" => "required|date",
                "toDate" => "required|date",
                "file" => 'required_if:type,1',
                "url" => 'required_if:type,2',
                "date" => "required|date",
                "category" => "required",

            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            $path           = public_path() . '/data/circular/';


            $result = Circular::where('id', $request->id)->first();
            $result->heading = $request->heading;
            $result->fromDate = $request->fromDate;
            $result->toDate = $request->toDate;
            $result->date = $request->date;
            $result->category = $request->category;

            if ($request->type == 2) {
                $result->url = $request->url;
            }

            if ($result->save()) {
                if ($request->type == 1) {
                    $extension = File::extension($request->file->getClientOriginalName());

                    $file          = 'Doc_' . $result->id . '.' . $extension;
                    $serverPath     = Config::get('constants.PROJURL') . '/data/circular/' . $file;
                    File::ensureDirectoryExists($path);
                    $request->file->move($path, $file);
                    $result->url = $serverPath;
                    $result->save();
                }
                return response()->json([
                    'status'     => 'success',
                    'message' => 'Circular edited successfully',
                    'data'   => $result
                ], 200);
            } else {
                return response()->json([
                    'status'     => 'failure',
                    'message' => 'Failed to edit circular',
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
            $result = Circular::where('id', $request->id)->update(['status' => $request->status]);
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


    public function search()
    {
        $search_text = $_GET['search'];
        $result = Circular::where('heading', 'LIKE', '%' . $search_text . '%')->get();
    }
}
