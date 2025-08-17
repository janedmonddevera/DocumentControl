<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = [
    'name',
    'logo',
    'button_color',
    'text_color',
];
}
