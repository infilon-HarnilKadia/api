<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterCountry extends Model
{
    public $table = 'master_country';
    public $timestamps = false;
    use HasFactory;
}
