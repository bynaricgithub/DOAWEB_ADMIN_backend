<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImpLinks extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'impLinks';

    public static $fields = ['id', 'heading', 'url', 'type', 'fromDate', 'toDate', 'created_at', 'updated_at'];

    public static function getFields()
    {
        return ImpLinks::$fields;
    }
}
