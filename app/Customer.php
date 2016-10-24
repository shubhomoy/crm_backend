<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = "customers";
    protected $hidden = ['password'];
    protected $guarded = ['password'];

    public function tokens() {
    	return $this->hasMany('\App\CustomerToken', 'c_id', 'id');
    }

    public function contracts() {
    	return $this->hasMany('\App\Contract', 'c_id', 'id');
    }

    public function emailqueries() {
    	return $this->hasMany('\App\EmailQuery', 'c_id', 'id');
    }
}
