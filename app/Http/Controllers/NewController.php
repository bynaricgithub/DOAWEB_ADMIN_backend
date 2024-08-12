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
                "type" => "required",
                "fromDate" => "required|date",
                "toDate" => "required|date",
                
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            if($request->type == 1)
            {
                $validator = Validator::make($input, [
                      "file"=> "required", 
                    ]);     
                    if ($validator->fails()) {
                        return response()->json([
                            'status'     => 'failure',
                            'message'   => $validator->errors()->first()
                        ], 400);
                    }
            }else{
                $validator = Validator::make($input, [
                    "url"=> "required", 
                  ]);     
                  if ($validator->fails()) {
                      return response()->json([
                          'status'     => 'failure',
                          'message'   => $validator->errors()->first()
                      ], 400);
                  }

            }
            $path           = public_path() . '/data/news/';
            $record = [
                'heading' => $request->heading,
                'fromDate' => $request->fromDate,
                'toDate' => $request->toDate,
                'date' => $request->date,
                'url'=> $request->url,
                'type'=> $request->type,
            ];
            if ($request->type == 2) {
                $record['url'] = $request->url;
            }
            //Log::info('Request Data: ', $request->all());
            $file = $request->file('file');
            //Log::info('Request Data: ',  $request->all());die();
            $result = News::create($record);
            if ($result) {
                if ($request->type == 1) {
                    if ($request->hasFile('file')) {
                        $file = $request->file('file');
                        $extension = $file->getClientOriginalExtension();
                    
                        $fileName = 'Doc_' . $result->id . '.' . $extension;
                        $serverPath = Config::get('constants.PROJURL') . '/data/news/' . $fileName;
                    
                        File::ensureDirectoryExists($path);
                        $file->move($path, $fileName);
                    
                        $result->url = $serverPath;
                        $result->save();
                    } else {
                        return response()->json([
                            'status' => 'failure',
                            'message' => 'No file uploaded or file upload failed.'
                        ], 400);
                    }
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
            $result->fromDate = $input['fromDate'];
            $result->toDate = $input['toDate'];
           

            if ($input['type'] == 2) {
                $result->url = $input['url'];
            }

            if ($result->save()) {
                if ($input['type'] == 1) {
                    if ($request->hasFile('file')) {
                        $file = $request->file('file');
                        $extension = $file->getClientOriginalExtension();
                    
                        $fileName = 'Doc_' . $result->id . '.' . $extension;
                        $serverPath = Config::get('constants.PROJURL') . '/data/news/' . $fileName;
                    
                        File::ensureDirectoryExists($path);
                        $file->move($path, $fileName);
                    
                        $result->url = $serverPath;
                        $result->save();
                    } else {
                        return response()->json([
                            'status' => 'failure',
                            'message' => 'No file uploaded or file upload failed.'
                        ], 400);
                    }
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
