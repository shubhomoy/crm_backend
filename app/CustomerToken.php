<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerToken extends Model
{
    protected $table = "customer_tokens";
    protected $hidden = ['created_at', 'updated_at', 'id', 'a_id'];

    protected $fillable = ['token'];
}
