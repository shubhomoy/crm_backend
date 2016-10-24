<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailList extends Model
{
    protected $table = 'maillist';
    protected $fillable = ['name'];

    public function customers() {
    	return $this->belongsToMany('\App\Customer', 'maillist_customer', 'm_id', 'c_id');
    }
}
