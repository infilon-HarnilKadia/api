<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Adminlogin extends Model
{
    use HasApiTokens;
    public $table = 'admin_login';
    public $timestamps = false;
    use HasFactory;
}