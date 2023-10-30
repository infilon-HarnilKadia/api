<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adminloginhistory extends Model
{
    public $table = 'admin_login_history';
    public $timestamps = false;
    use HasFactory;
}
