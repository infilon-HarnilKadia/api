<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterLoadingPoint extends Model
{
    public $table = 'master_loading_point';
    public $timestamps = false;
    use HasFactory;
}
