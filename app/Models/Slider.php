<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'slider';

    public static $fields = ['id', 'name', 'description', 'img_path', 'created_at', 'updated_at'];

    public static function getFields()
    {
        return Slider::$fields;
    }
}
