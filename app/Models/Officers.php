<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Officers extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'msbte_officers';

    public static $fields = ['id', 'name', 'post', 'img_path', 'phone', 'email'];

    public static function getFields()
    {
        return Officers::$fields;
    }
}
