<?php

namespace App\Http\Controllers;

use App\Models\Officers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OfficersController extends Controller
{
    public function index()
    {
        try {
            $result = Officers::paginate(10);
            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'data' => $result,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failure',
                'message' => 'Problem Fetching Information...Error:' . $e->getMessage(),
            ], 400);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'post' => 'required',
            'file' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failure',
                'message' => $this->stringifyValidationArray($validator->errors()->getMessages()),
            ], 400);
        }
        $name = $request->name;
        $post = $request->post;
        $phone = $request->phone;
        $email = $request->email;
        $img_path = $request->file;

        try {
            $result = Officers::create([
                'name' => $name,
                'post' => $post,
                'img_path' => $img_path,
                'phone' => $phone,
                'email' => $email,
                'status' => 1,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Officers Photo Uploaded Successfully...',
                'data' => $result,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failure',
                'message' => 'Problem Officers Photo...',
                'data' => $e,
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $result = Officers::where('id', $id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Officers Photo Deleted Successfully...',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failure',
                'message' => 'Problem Deleting Officers Photo...',
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
                'name' => 'required',
                'post' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'failure',
                    'message' => $this->stringifyValidationArray($validator->errors()->getMessages()),
                ], 400);
            }
            $name = $request->name;
            $post = $request->post;
            $phone = $request->phone;
            $email = $request->email;
            $img_path = $request->file;

            if (isset($request->file) && ! empty($request->file)) {
                $result = Officers::where('id', $request->id)->update([
                    'name' => $name,
                    'post' => $post,
                    'img_path' => $img_path,
                    'phone' => $phone,
                    'email' => $email,
                ]);
            } else {
                $result = Officers::where('id', $request->id)->update([
                    'name' => $name,
                    'post' => $post,
                    'phone' => $phone,
                    'email' => $email,
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Officers Photo Updated Successfully...',
                'data' => Officers::where('id', $request->id)->first(),
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
            $result = Officers::where('id', $request->id)->update(['status' => $request->status]);
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
