<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    public $table = 'notify';
    public $timestamps = false;
    use HasFactory;
}
