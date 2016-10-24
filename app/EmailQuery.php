<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailQuery extends Model
{
    protected $table = 'email_queries';
    protected $fillable = ['remarks', 'contract_id'];

    public function customer() {
    	return $this->hasOne('\App\Customer', 'id', 'c_id');
    }

    public function contract() {
    	return $this->hasOne('\App\Contract', 'id', 'contract_id');
    }
}
