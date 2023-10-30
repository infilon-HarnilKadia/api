<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerChartController extends Controller
{
    public function index()
    {
        return view("borders.borders-customer-chart");
    }
}
