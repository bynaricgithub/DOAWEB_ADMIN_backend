<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventVideos extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'event_videos';
    public static $fields = ["id", "name", "description", "url", "status"];
    public static function getFields()
    {
        return EventVideos::$fields;
    }
}
