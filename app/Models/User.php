<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    use HasFactory;
    protected $guarded = [];

    protected $table = 'users';
    public static $fields = ['id', 'username','password','role','status','created_at','updated_at'];
    public static function getFields()
    {
      return User::$fields;
    }
   
    protected $hidden = [
        'password',
    ];
}
