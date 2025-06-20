<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $table = 'movements';

    protected $fillable = ['type', 'value', 'category', 'description', 'user_id'];


    public function user()
    {
        return $this->belongsTo(Usuario::class);
    }
}
