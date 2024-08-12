<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class NewController extends Controller
{
    //

    public function index()
    {
        try {
            
            $result = News::get();
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'data'   => $result
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'     => 'failure',
                'message'   => 'Problem Fetching News...Error:' . $e->getMessage()
            ], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                "date" => "required",
                "heading" => "required",
                "url"=> "required",
                "type" => "required",
                "status"=> "required",
                "fromDate" => "required|date",
                "toDate" => "required|date",
                
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            $path           = public_path() . '/data/news/';
            $record = [
                'heading' => $request->heading,
                'fromDate' => $request->fromDate,
                'toDate' => $request->toDate,
                'date' => $request->date,
                'url'=> $request->url,
                'type'=> $request->type,
                'status'=> $request->status,
            ];
            if ($request->type == 2) {
                $record['url'] = $request->url;
            }
            $result = News::create($record);
            if ($result) {
                if ($request->type == 1) {
                    // $extension = File::extension($request->file->getClientOriginalName());

                    // $file          = 'Doc_' . $result->id . '.' . $extension;
                    // $serverPath     = Config::get('constants.PROJURL') . '/data/news/' . $file;
                    // File::ensureDirectoryExists($path);
                    // $request->file->move($path, $file);
                    // $result->url = $serverPath;
                    $result->save();
                }

                return response()->json([
                    'status'     => 'success',
                    'message' => 'News added successfully',
                    'data'   => $result
                ], 200);
            } else {
                return response()->json([
                    'status'     => 'failure',
                    'message' => 'Failed to add News',
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
                "id" => "required|exists:news,id",
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            $result = News::where('id', $request->id)->delete();
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'message' => 'News deleted successfully',
                    'data'   => $result
                ], 200);
            } else {
                return response()->json([
                    'status'     => 'failure',
                    'message' => 'Failed to delete News',
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
       
       $input = is_array($request) ? $request : $request->all();
        try {
          $validator = Validator::make($input, [
            "date" => "required|date",
            "heading" => "required",
            "url"=> "required",
            "type" => "required",
            "status"=> "required",
            "fromDate" => "required|date",
            "toDate" => "required|date",
            
        ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            $path           = public_path() . '/data/news/';


            $result = News::where('id', $input['id'])->first();
            $result->date =    $input['date'];
            $result->heading = $input['heading'];
            $result->url = $input['url'];
            $result->type =  $input['type'];
            $result->status = $input['status'];
            $result->fromDate = $input['fromDate'];
            $result->toDate = $input['toDate'];
           

            if ($input['type'] == 2) {
                $result->url = $input['url'];
            }

            if ($result->save()) {
                if ($input['type'] == 1) {
                    // $extension = File::extension($request->file->getClientOriginalName());

                    // $file          = 'Doc_' . $result->id . '.' . $extension;
                    // $serverPath     = Config::get('constants.PROJURL') . '/data/news/' . $file;
                    // File::ensureDirectoryExists($path);
                    // $request->file->move($path, $file);
                    // $result->url = $serverPath;
                    $result->save();
                }
                return response()->json([
                    'status'     => 'success',
                    'message' => 'News edited successfully',
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


}
