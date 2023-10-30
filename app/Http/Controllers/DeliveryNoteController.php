<?php

namespace App\Http\Controllers;
use App\Models\BookingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class DeliveryNoteController extends Controller
{
    
    public function index()
    {
        $pc = parent__construct();

        $resp_data['th'] = $pc[0]['fields'];
        $resp_data['classname'] = Route::getFacadeRoot()->current()->uri();
        $resp_data['title'] = "Delivery Note";
        
        return view('orders.delivery-note-list',$resp_data);
    }

    public function ajax(Request $r)
    {
        $get = $r->all();

        $order = $get['order'];
        $columns = $get['columns'];
        $data = $this->getlist('', 'pending', $columns[$order[0]['column']]['name'], $order[0]['dir'], $get['search']['value'], $get['start'], $get['length'], $get['more']);
        $dataarray = array();
        foreach ($data as $r) {
            $v1 = array_values($r);
            $v1[98] = $v1[7];
            $v1[7] = nlink('delivery-note/print/' . enc(1, $v1[98]), 'file-pdf',1);
            $v1[8] = nlink('delivery-note/delete/' . enc(1, $v1[98]), 'delete');
            unset($v1[98]);
            $dataarray[] = $v1;
        }
        $json = array(
            'draw' => $get['draw'],
            'recordsTotal' => $this->rows('pending', '', $get['more']),
            'recordsFiltered' => $this->rows('pending', $get['search']['value'], $get['more']),
            'data' => $dataarray
        );

        return response()->json($json);
    }

    private function getlist($id = '', $field = '', $order = '', $order_type = '', $search = '', $start = '', $limit = '', $advance_search = '')
    {
        $table = BookingOrder::select([]);
        if ($field == "pending") {
            $table->select(array('booking_order.name', 'booking_order.b_date', 'a2.name as customer', 'a3.name as loading', 'a4.name as destination', 'booking_order.description','booking_order.weight','booking_order.id'));
        } elseif ($field == "homepending") {
        } else {
            $table->select(array('booking_order.*'));
        }
        $table->where('booking_order.status', '!=', 0);
        $table->join("master_customer as a2", "booking_order.customer", "=", "a2.id", "left outer")
            ->join("master_loading_point as a3", "booking_order.loading", "=", "a3.id", "left outer")
            ->join("master_location as a4", "booking_order.destination", "=", "a4.id", "left outer");
        if ($order) {
            $table->orderBy('booking_order.' . $order, $order_type);
        }
        if ($id) {
            $table->where('booking_order.id', $id);
        }
        if ($search) {
            $table->where(function ($query) use ($search) {
                $query->where('booking_order.name', 'like',  $search . '%')
                    ->orWhere('booking_order.b_date', 'like',  $search . '%')
                    ->orWhere('a2.name', 'like',  $search . '%')
                    ->orWhere('a3.name', 'like',  $search . '%')
                    ->orWhere('a4.name', 'like',  $search . '%')
                    ->orWhere('booking_order.description', 'like',  $search . '%')
                    ->orWhere('booking_order.weight', 'like',  $search . '%')
                    ->orWhere('booking_order.id', 'like',  $search . '%');
            });
        }
        if ($limit) {
            $table->limit($limit, $start);
        }
        $table->groupBy('booking_order.id');

        if ($advance_search) {
            $morelist = json_decode($advance_search, true);
            if (count($morelist)) {
                foreach ($morelist as $key => $advn) {
                    if ($advn) {
                        $table->where($key, 'like',  $advn . '%');
                    }
                }
            }
        }

        $query = $table->get();
        $r = $query->toArray();
        return $r;
    }

    private function rows($type, $search, $advance_search)
    {
        $table = BookingOrder::select(['booking_order.id']);
        if ($type == "pending") {
            $table->where('booking_order.status', '!=', 0);
        }
        $table->join("master_customer as a2", "booking_order.customer", "=", "a2.id", "left outer")
            ->join("master_loading_point as a3", "booking_order.loading", "=", "a3.id", "left outer")
            ->join("master_location as a4", "booking_order.destination", "=", "a4.id", "left outer");
        
        if ($search) {
            $table->where(function ($query) use ($search) {
                $query->where('booking_order.name', 'like',  $search . '%')
                    ->orWhere('booking_order.b_date', 'like',  $search . '%')
                    ->orWhere('a2.name', 'like',  $search . '%')
                    ->orWhere('a3.name', 'like',  $search . '%')
                    ->orWhere('a4.name', 'like',  $search . '%')
                    ->orWhere('booking_order.description', 'like',  $search . '%')
                    ->orWhere('booking_order.weight', 'like',  $search . '%')
                    ->orWhere('booking_order.id', 'like',  $search . '%');
            });
        }

        if ($advance_search) {
            $morelist = json_decode($advance_search, true);
            if (count($morelist)) {
                foreach ($morelist as $key => $advn) {
                    if ($advn) {
                        $table->where($key, 'like', $advn . '%');
                    }
                }
            }
        }
        return $table->get()->count();
    }
    
    public function print($id)
    {
        return redirect('/assets/files/Delivery note.pdf');
    }
    
    public function delete($id)
    {
        $result = BookingOrder::where('id',enc(2,$id))->update(['u_date'=> date('Y-m-d H:i:s'),'status' => 0]);    
        if($result){
           return redirect('/delivery-note')->with('success', 'Deleted successfully.'); 
        }
        
        return redirect('/delivery-note')->with('error', 'Not deleted successfully.');
    }
}
