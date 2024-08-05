<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Circular extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'circular';
    public static $fields = ["id", "date", "heading", "url", "fromDate", "toDate", "created_at", "updated_at"];
    public static function getFields()
    {
        return Circular::$fields;
    }
}
