<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'news';
    public static $fields = ["id", "date", "heading", "url","type","status", "fromDate", "toDate", "created_at", "updated_at"];
    public static function getFields()
    {
        return News::$fields;
    }
}
