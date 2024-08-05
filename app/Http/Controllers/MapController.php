<?php

namespace App\Http\Controllers;

use App\Models\Map;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MapController extends Controller
{
    public function index()
    {
        try {
            $result = DB::select("SELECT * FROM lat_long_source_list");
            $result = Map::get();
            if ($result) {
                return response()->json([
                    'status'     => 'success',
                    'data'   => $result
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'     => 'failure',
                'message'   => 'Problem Fetching Map...Error:' . $e->getMessage()
            ], 400);
        }
    }
   
    
    
}
