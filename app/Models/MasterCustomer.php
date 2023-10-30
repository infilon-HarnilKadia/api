<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterCustomer extends Model
{
    public $table = 'master_customer';
    public $timestamps = false;
    use HasFactory;
}
