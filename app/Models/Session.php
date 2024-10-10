<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'sessions';

    public static $fields = ['id', 'uid', 'role', 'ip', 'starttime', 'endtime', 'created_at', 'updated_at'];

    protected $guarded = [];

    protected $primaryKey = 'id';

    public static function getFields()
    {
        return Session::$fields;
    }
}
