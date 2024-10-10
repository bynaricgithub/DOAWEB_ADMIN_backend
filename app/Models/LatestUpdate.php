<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LatestUpdate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'latestUpdates';

    public static $fields = ['id', 'heading', 'url', 'fromDate', 'toDate', 'created_at', 'updated_at'];

    public static function getFields()
    {
        return LatestUpdate::$fields;
    }
}
