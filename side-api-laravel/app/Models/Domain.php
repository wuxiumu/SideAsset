<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = [
        'domain', 'registrar', 'purchase_date', 'expire_date', 'project', 'price', 'status'
    ];
}
