<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
      protected $fillable = [
        'id',
        'unit',
        'code',
        'create_at',
        'updated_at',
        

    ];
}
