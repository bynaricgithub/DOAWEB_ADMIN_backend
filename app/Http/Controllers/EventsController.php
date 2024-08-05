<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class EventsController extends Controller
{
    public function index()
    {
        try {
            $result = DB::select("SELECT * FROM slider");
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
                "file" => "required",
                "name" => "required",
                "description" => "required",

            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            $name = $request->name;
            $extension      = File::extension($request->file->getClientOriginalName());
            $path           = public_path() . '/data/events/';
            $image          = 'Photo_' . str_replace(" ", "_", $name) . "." . $extension;
            $serverPath     = Config::get('constants.PROJURL') . '/data/events/' . $image;

            File::ensureDirectoryExists($path);
            $request->file->move($path, $image);

            $result = Slider::create([
                'name' => $name,
                'description' => $request->description,
                'img_path' => $serverPath,
            ]);
            if ($result) {
                return response()->json([
                    "status"          => "success",
                    "message"         => "Event Photo Uploaded Successfully...",
                    "data" => $result
                ], 200);
            } else {
                return response()->json([
                    "status"          => "failure",
                    "message"         => "Failed to add event photo",
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
                "id" => "required|exists:slider,id",
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'     => 'failure',
                    'message'   => $validator->errors()->first()
                ], 400);
            }
            $result = Slider::where('id', $request->id)->delete();
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
                "description" => "required",

            ]);
            if ($validator->fails()) {
                return response()->json([
                    "status"          => "failure",
                    "message"         => $validator->errors()->first()
                ], 400);
            }
            $name = $request->name;


            if (isset($request->file) && !empty($request->file)) {



                $extension = File::extension($request->file->getClientOriginalName());


                $path           = public_path() . '/data/dignitaries/';
                $image          = 'Photo_' . str_replace(" ", "_", $name) . '.' . $extension;
                $serverPath     = Config::get('constants.PROJURL') . '/data/dignitaries/' . $image;

                \File::ensureDirectoryExists($path);
                $request->file->move($path, $image);
                $result = Slider::where('id', $request->id)->update([

                    'name' => $name,
                    'description' => $request->description,
                    'img_path' => $serverPath,
                ]);
            } else {
                $result = Slider::where('id', $request->id)->update([

                    'name' => $name,
                    'description' => $request->description,

                ]);
            }

            return response()->json([
                "status"          => "success",
                "message"         => "Dignitaries Photo Updated Successfully...",
                "data"            => Slider::where('id', $request->id)->first(),
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
            $result = Slider::where('id', $request->id)->update(['status' => $request->status]);
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
