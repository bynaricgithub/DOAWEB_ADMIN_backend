<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'photo';

    public static $fields = ['id', 'img_path', 'name', 'post', 'created_at', 'updated_at'];

    public static function getFields()
    {
        return Photo::$fields;
    }
}
