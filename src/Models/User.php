<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
  protected $table = 'users'; // nombre de la tabla
      
  protected $fillable = ['id', 'username', 'email', 'password', 'is_verified', 'verification_token', 'verification_expires', 'role_id', 'status', 'created_at', 'updated_at']; // columnas escribibles
}
