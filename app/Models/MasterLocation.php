<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterLocation extends Model
{
    public $table = 'master_location';
    public $timestamps = false;
    use HasFactory;
}
