<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Validator;
use Auth;
use File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


class PhotoController extends Controller
{
    public function index()
    {
        try {
            $result = Photo::get();
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'data'   => $result
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'     => 'failure',
                'message'   => 'Problem Fetching Photos...Error:' . $e->getMessage()
            ], 400);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'post'      => 'required',
            'file'      => 'required|max:100',
        ]);
        $name = $request->name;
        $post = $request->post;
        $extension = File::extension($request->file->getClientOriginalName());

        if ($validator->fails()) {
            return response()->json([
                "status"          => "failure",
                "message"         => $this->stringifyValidationArray($validator->errors()->getMessages()),
            ], 400);
        }
        $path           = public_path() . '/data/dignitaries/';
        $image          = 'Photo_' . str_replace(" ", "_", $name) . '.' . $extension;
        $serverPath     = Config::get('constants.PROJURL') . '/data/dignitaries/' . $image;

        \File::ensureDirectoryExists($path);
        $request->file->move($path, $image);

        try {
            $result = Photo::create([
                'name' => $name,
                'post' => $post,
                'img_path' => $serverPath,
            ]);

            return response()->json([
                "status"          => "success",
                "message"         => "Dignitaries Photo Uploaded Successfully...",
                "data"            => $result,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status"          => "failure",
                "message"         => 'Problem Dignitaries Photo...',
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $result = Photo::where('id', $id)->delete();
            return response()->json([
                "status"          => "success",
                "message"         => "Dignitaries Photo Deleted Successfully...",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status"          => "failure",
                "message"         => 'Problem Deleting Dignitaries Photo...',
            ], 400);
        }
    }

    function stringifyValidationArray($arr)
    {
        $str = '<ol>';
        foreach ($arr as $arrr) {
            $str = $str . '<li>' . $arrr[0] . '</li>';
        }
        $str = $str . '</ol>';
        return $str;
    }

    public function edit(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id'        => 'required',
                'name'      => 'required',
                'post'      => 'required'

            ]);
            if ($validator->fails()) {
                return response()->json([
                    "status"          => "failure",
                    "message"         => $this->stringifyValidationArray($validator->errors()->getMessages()),
                ], 400);
            }
            $name = $request->name;
            $post = $request->post;

            if (isset($request->file) && !empty($request->file)) {
                $extension = File::extension($request->file->getClientOriginalName());


                $path           = public_path() . '/data/dignitaries/';
                $image          = 'Photo_' . str_replace(" ", "_", $name) . '.' . $extension;
                $serverPath     = Config::get('constants.PROJURL') . '/data/dignitaries/' . $image;

                \File::ensureDirectoryExists($path);
                $request->file->move($path, $image);
                $result = Photo::where('id', $request->id)->update([

                    'name' => $name,
                    'post' => $post,
                    'img_path' => $serverPath,
                ]);
            } else {
                $result = Photo::where('id', $request->id)->update([

                    'name' => $name,
                    'post' => $post

                ]);
            }

            return response()->json([
                "status"          => "success",
                "message"         => "Dignitaries Photo Updated Successfully...",
                "data"            => Photo::where('id', $request->id)->first(),
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
            $result = Photo::where('id', $request->id)->update(['status' => $request->status]);
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
