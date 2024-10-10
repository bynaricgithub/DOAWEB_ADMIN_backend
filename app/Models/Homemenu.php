<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homemenu extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'homemenu';

    public static $fields = ['id', 'title', 'parent_id', 'menu_url', 'status', 'updated_at', 'created_at'];

    public static function getFields()
    {
        return Homemenu::$fields;
    }

    public function childs()
    {
        return $this->hasMany('App\Menu', 'id', 'parent_id', 'menu_url');
    }

    public function parent()
    {
        return $this->belongsTo(Homemenu::class, 'parent_id');
    }
}
