<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingOrderExpense extends Model
{
    public $table = 'booking_order_expense';
    public $timestamps = false;
    use HasFactory;
}
