<?php

namespace App\Http\Controllers;
use App\Models\BookingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ShortfallFormController extends Controller
{
    
    public function index()
    {
        $resp_data['title'] = "Shortfall Form";
        $resp_data['dn'] = BookingOrder::select(['id','name'])->where('status',1)->get();
        return view('orders.shortfall-add',$resp_data);
    }
    
    public function save(Request $request)
    {
        $r = $request->all();
        
        return redirect('/assets/files/s-form.pdf');
    }
    
    public function data(Request $request)
    {
        $table = BookingOrder::select(['booking_order.b_date','a1.name as driver','booking_order.truck_no','a3.name as trailer','booking_order.loading_date','a2.name as loading','a5.name as truck','a4.name as destination'])
            ->join("master_driver as a1", "booking_order.driver", "=", "a1.id", "left outer")
            ->join("master_trailer as a3", "booking_order.trailer", "=", "a3.id", "left outer")
            ->join("master_loading_point as a2", "booking_order.loading", "=", "a2.id", "left outer")
            ->join("master_location as a4", "booking_order.destination", "=", "a4.id", "left outer")
            ->join("master_truck as a5", "booking_order.truck", "=", "a5.id", "left outer")
            ->where('booking_order.id',$request->q)
            ->get()->toArray();
        
        $table[0]['b_date'] = dateind($table[0]['b_date']);
        $table[0]['loading_date'] = dateind($table[0]['loading_date']);
        
        return response()->json($table[0]);
    }
}
