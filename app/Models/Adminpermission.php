<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adminpermission extends Model
{
    public $table = 'admin_permission';
    public $timestamps = false;
    use HasFactory;
}
