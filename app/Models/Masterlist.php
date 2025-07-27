<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Masterlist extends Model
{
    protected $fillable = [
        'id',
        'title',
        'doc_code',
        'unit',
        'type',
        'type_name',
        'seq',
        'org_name',
        'username',
        'create_at',
        'updated_at',
        

    ];
}
