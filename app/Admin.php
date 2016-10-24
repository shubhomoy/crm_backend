<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = "admins";
    protected $hidden = ['password'];
    protected $guarded = ['password'];

    public function tokens() {
    	return $this->hasMany('\App\AdminToken', 'a_id', 'id');
    }
}
