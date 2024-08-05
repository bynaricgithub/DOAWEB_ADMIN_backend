<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'board';
    public static $fields = ["id", "name", "post", "description", "status"];
    public static function getFields()
    {
        return Board::$fields;
    }
}