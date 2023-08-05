<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_reward_poin extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id', 'user_id','order_id','reward_point','expired_at'
   ];
   protected $table = 'product_reward_poins';
}
