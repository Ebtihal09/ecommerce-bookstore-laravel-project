<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class orders extends Model
{
    protected $fillable = [
        "customer_id","product_id","status"
    ] ;
}
