<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelPurchaseOrder extends Model
{
    public $table = 'fuel_purchase_order';
    public $timestamps = false;
    use HasFactory;
}
