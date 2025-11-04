<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kasir extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'kasirs';
    protected $primaryKey = 'id_kasir';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['nama_kasir','email','password'];

    protected $hidden = ['password','remember_token'];
}
