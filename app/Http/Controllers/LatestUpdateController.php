<?php

namespace App\Http\Controllers;

use App\Models\LatestUpdate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class LatestUpdateController extends Controller
{
    public function index()
    {
        try {
            // $result = DB::select("SELECT * FROM latestUpdates WHERE '" . Carbon::now() . "' between fromDate AND toDate");
            $result = LatestUpdate::get();
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'data'   => $result
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'     => 'failure',
                'message'   => 'Problem Fetching Latest Updates...Error:' . $e->getMessage()
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
                "url" => 'required_if:type,2'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            $path           = public_path() . '/data/latestUpdate/';
            $record = [
                'heading' => $request->heading,
                'fromDate' => $request->fromDate,
                'toDate' => $request->toDate,
            ];
            if ($request->type == 2) {
                $record['url'] = $request->url;
            }
            $result = LatestUpdate::create($record);
            if ($result) {
                if ($request->type == 1) {
                    $extension = File::extension($request->file->getClientOriginalName());

                    $file          = 'Doc_' . $result->id . '.' . $extension;
                    $serverPath     = Config::get('constants.PROJURL') . '/data/latestUpdate/' . $file;
                    File::ensureDirectoryExists($path);
                    $request->file->move($path, $file);
                    $result->url = $serverPath;
                    $result->save();
                }

                return response()->json([
                    'status'     => 'success',
                    'message' => 'Latest update added successfully',
                    'data'   => $result
                ], 200);
            } else {
                return response()->json([
                    'status'     => 'failure',
                    'message' => 'Failed to add link',
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
                "id" => "required|exists:latestUpdates,id",
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            $result = LatestUpdate::where('id', $request->id)->delete();
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'message' => 'Link deleted successfully',
                    'data'   => $result
                ], 200);
            } else {
                return response()->json([
                    'status'     => 'failure',
                    'message' => 'Failed to delete link',
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
                "url" => 'required_if:type,2'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            $path           = public_path() . '/data/latestUpdate/';


            $result = LatestUpdate::where('id', $request->id)->first();
            $result->heading = $request->heading;
            $result->fromDate = $request->fromDate;
            $result->toDate = $request->toDate;

            if ($request->type == 2) {
                $result->url = $request->url;
            }

            if ($result->save()) {
                if ($request->type == 1) {
                    $extension = File::extension($request->file->getClientOriginalName());

                    $file          = 'Doc_' . $result->id . '.' . $extension;
                    $serverPath     = Config::get('constants.PROJURL') . '/data/latestUpdate/' . $file;
                    File::ensureDirectoryExists($path);
                    $request->file->move($path, $file);
                    $result->url = $serverPath;
                    $result->save();
                }
                return response()->json([
                    'status'     => 'success',
                    'message' => 'Latest update edited successfully',
                    'data'   => $result
                ], 200);
            } else {
                return response()->json([
                    'status'     => 'failure',
                    'message' => 'Failed to edit latest update',
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
            $result = LatestUpdate::where('id', $request->id)->update(['status' => $request->status]);
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
