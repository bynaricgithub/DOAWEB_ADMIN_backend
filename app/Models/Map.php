<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'lat_long_source_list';

    public static $fields = ['inst_id', 'name', 'region', 'district', 'taluka', 'lat', 'long', 'created_at', 'updated_at'];

    public static function getFields()
    {
        return Map::$fields;
    }
}
