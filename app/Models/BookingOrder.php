<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingOrder extends Model
{
    public $table = 'booking_order';
    public $timestamps = false;
    use HasFactory;
}
