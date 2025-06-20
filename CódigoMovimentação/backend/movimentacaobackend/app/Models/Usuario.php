<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Model
{

    use HasApiTokens, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = ['username', 'email', 'senha'];

    protected $hidden = ['senha'];

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}
