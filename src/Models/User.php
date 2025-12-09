<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
  protected $table = 'users'; // nombre de la tabla

  protected $fillable = ['id', 'username', 'email', 'password', 'role_id', 'status', 'created_at', 'updated_at']; // columnas escribibles
}
