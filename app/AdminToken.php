<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminToken extends Model
{
    protected $table = "admin_tokens";
    protected $hidden = ['created_at', 'updated_at', 'id', 'a_id'];

    protected $fillable = ['token'];
}
