<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = 'contracts';
    protected $fillable = ['title', 'description'];

    public function customer() {
    	return $this->hasOne('\App\Customer', 'id', 'c_id');
    }

    public function admin() {
    	return $this->hasOne('\App\Admin', 'id', 'a_id');
    }

    public function emailqueries() {
    	return $this->hasMany('\App\EmailQuery', 'contract_id', 'id');
    }
}
