<?php

namespace App\Http\Controllers;

use App\Models\Officers;
use Illuminate\Http\Request;
use Validator;
use Auth;
use File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


class OfficersController extends Controller
{
    public function index()
    {
        try {
           $result = Officers::select(['id','name','post', 'img_path', 'phone','email', 'status'])->get();
           //$result = Officers::get();
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'data'   => $result
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'     => 'failure',
                'message'   => 'Problem Fetching Information...Error:' . $e->getMessage()
            ], 400);
        }
    }
  
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'post'      => 'required',
            'file'      => 'required|max:100',
            'phone'      => 'required',
            'email'      => 'required',
        ]);
        $name = $request->name;
        $post = $request->post;
        $phone = $request->phone;
        $email = $request->email;
        $extension = File::extension($request->file->getClientOriginalName());

        if ($validator->fails()) {
            return response()->json([
                "status"          => "failure",
                "message"         => $this->stringifyValidationArray($validator->errors()->getMessages()),
            ], 400);
        }
        $path           = public_path() . '/data/dignitaries/';
        $image          = 'Photo_'.str_replace(" ","_",$name).'.'. $extension;
        $serverPath     = Config::get('constants.PROJURL').'/data/dignitaries/'.$image;
        
        \File::ensureDirectoryExists($path);
        $request->file->move($path, $image);

        try {
            $result = Officers::create([
                'name' => $name,
                'post' => $post,
                'img_path' => $serverPath,
                'phone' => $phone,
                'email' => $email,
                'status' => 1
            ]);

            return response()->json([
                "status"          => "success",
                "message"         => "Officers Photo Uploaded Successfully...",
                "data"            => $result,
            ], 200);
        }
        catch(\Exception $e) {
            return response()->json([
                "status"          => "failure",
                "message"         => 'Problem Officers Photo...',
                "data" => $e
            ], 400);
        }
    }

    public function destroy($id) 
    {
        try {
            $result = Officers::where('id',$id)->delete();
            return response()->json([
                "status"          => "success",
                "message"         => "Officers Photo Deleted Successfully...",
            ], 200);
        }
        catch(\Exception $e) {
            return response()->json([
                "status"          => "failure",
                "message"         => 'Problem Deleting Officers Photo...',
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

                
                
                'name'      => 'required',
                'post'      => 'required',
                'phone'     => 'required',
                'email'     => 'required',
                'id' => 'required'
                
            ]);
            if ($validator->fails()) {
                return response()->json([
                    "status"          => "failure",
                    "message"         => $this->stringifyValidationArray($validator->errors()->getMessages()),
                ], 400);
            }
            $name = $request->name;
            $post = $request->post;
            $phone = $request->phone;
            $email = $request->email;
    
            if (isset($request->file) && !empty($request->file)){
    
    
            
            $extension = File::extension($request->file->getClientOriginalName());
    
            
            $path           = public_path() . '/data/dignitaries/';
            $image          = 'Photo_'.str_replace(" ","_",$name).'.'. $extension;
            $serverPath     = Config::get('constants.PROJURL').'/data/dignitaries/'.$image;
            
            \File::ensureDirectoryExists($path);
            $request->file->move($path, $image);
            $result = Officers::where('id',$request->id)->update([
                
                'name' => $name,
                'post' => $post,
                'img_path' => $serverPath,
                'phone' => $phone,
                'email' => $email
            ]);
            }
            else {
                $result = Officers::where('id',$request->id)->update([
    
                    'name' => $name,
                    'post' => $post,
                    'phone' => $phone,
                    'email' => $email,
                    
                ]);
    
            }

            return response()->json([
                "status"          => "success",
                "message"         => "Officers Photo Updated Successfully...",
                "data"            => Officers::where('id', $request->id)->first(),
            ], 200);
        }
        catch(\Exception $e) {
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
            $result = Officers::where('id', $request->id)->update(['status' => $request->status]);
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