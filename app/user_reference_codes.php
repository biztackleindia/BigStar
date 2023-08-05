<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_reference_codes extends Model
{
    use HasFactory;
    protected $fillable = [
         'user_id', 'reference_codes'
    ];
    protected $table = 'user_reference_codes';
}
