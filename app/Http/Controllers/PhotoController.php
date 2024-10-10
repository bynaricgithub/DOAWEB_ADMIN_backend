<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Validator;

class PhotoController extends Controller
{
    public function index()
    {
        try {
            $result = Photo::paginate(10);
            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'data' => $result,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failure',
                'message' => 'Problem Fetching Photos...Error:' . $e->getMessage(),
            ], 400);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'post' => 'required',
            'file' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failure',
                'message' => $this->stringifyValidationArray($validator->errors()->getMessages()),
            ], 400);
        }
        $name = $request->name;
        $post = $request->post;
        $img_path = $request->file;

        try {
            $result = Photo::create([
                'name' => $name,
                'post' => $post,
                'img_path' => $img_path,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Dignitaries Photo Uploaded Successfully...',
                'data' => $result,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failure',
                'message' => 'Problem Dignitaries Photo...',
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $result = Photo::where('id', $id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Dignitaries Photo Deleted Successfully...',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failure',
                'message' => 'Problem Deleting Dignitaries Photo...',
            ], 400);
        }
    }

    public function stringifyValidationArray($arr)
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
                'id' => 'required',
                'name' => 'required',
                'post' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'failure',
                    'message' => $this->stringifyValidationArray($validator->errors()->getMessages()),
                ], 400);
            }
            $name = $request->name;
            $post = $request->post;

            if (isset($request->file) && ! empty($request->file)) {
                $result = Photo::where('id', $request->id)->update([
                    'name' => $name,
                    'post' => $post,
                    'img_path' => $request->file,
                ]);
            } else {
                $result = Photo::where('id', $request->id)->update([
                    'name' => $name,
                    'post' => $post,
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Dignitaries Photo Updated Successfully...',
                'data' => Photo::where('id', $request->id)->first(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failure',
                'message' => $e->getMessage(),
            ], 400);
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
            $result = Photo::where('id', $request->id)->update(['status' => $request->status]);
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
