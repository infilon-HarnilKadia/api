<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTrailer extends Model
{
    public $table = 'master_trailer';
    public $timestamps = false;
    use HasFactory;
}
