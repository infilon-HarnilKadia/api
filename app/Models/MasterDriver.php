<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterDriver extends Model
{
    public $table = 'master_driver';
    public $timestamps = false;
    use HasFactory;
}
