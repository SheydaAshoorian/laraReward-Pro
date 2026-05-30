<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointLog extends Model
{
   protected $fillable = ['points', 'reason'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}