<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users_statu extends Model
{
    protected $table = 'users_status';

    protected $fillable = ['name'];

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany(User::class, 'status_id');
    }
}
