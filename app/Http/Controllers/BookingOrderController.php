<?php

namespace App\Http\Controllers;
use App\Models\BookingOrder;
use App\Models\BookingOrderExpense;
use App\Models\MasterCustomer;
use App\Models\MasterTruck;
use App\Models\MasterTrailer;
use App\Models\MasterLoadingPoint;
use App\Models\MasterLocation;
use App\Models\MasterDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class BookingOrderController extends Controller
{
    
    public function index()
    {
        $pc = parent__construct();

        $resp_data['th'] = $pc[0]['fields'];
        $resp_data['classname'] = Route::getFacadeRoot()->current()->uri();
        $resp_data['title'] = "Booking Order";
        
        return view('orders.booking-order-list',$resp_data);
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
            $v1[98] = $v1[8];
            $v1[8] = nlink('booking-order/edit/' . enc(1, $v1[98]), 'border-color');
            $v1[9] = nlink('booking-order/delete/' . enc(1, $v1[98]), 'delete');
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
            $table->select(array('booking_order.b_date', 'booking_order.name', 'a2.name as customer', 'booking_order.truck_no',  'a3.name as trailer', 'a4.name as destination', 'booking_order.invoice','booking_order.i_date','booking_order.id'));
        } elseif ($field == "homepending") {
        } else {
            $table->select(array('booking_order.*'));
        }
        $table->where('booking_order.status', '!=', 0);
        $table->join("master_customer as a2", "booking_order.customer", "=", "a2.id", "left outer")
            ->join("master_trailer as a3", "booking_order.trailer", "=", "a3.id", "left outer")
            ->join("master_location as a4", "booking_order.destination", "=", "a4.id", "left outer");
        if ($order) {
            $table->orderBy('booking_order.' . $order, $order_type);
        }
        if ($id) {
            $table->where('booking_order.id', $id);
        }
        if ($search) {
            $table->where(function ($query) use ($search) {
                $query->where('booking_order.b_date', 'like',  $search . '%')
                    ->orWhere('booking_order.name', 'like',  $search . '%')
                    ->orWhere('a2.name', 'like',  $search . '%')
                    ->orWhere('booking_order.truck_no', 'like',  $search . '%')
                    ->orWhere('a3.name', 'like',  $search . '%')
                    ->orWhere('a4.name', 'like',  $search . '%')
                    ->orWhere('booking_order.invoice', 'like',  $search . '%')
                    ->orWhere('booking_order.i_date', 'like',  $search . '%')
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
            ->join("master_trailer as a3", "booking_order.trailer", "=", "a3.id", "left outer")
            ->join("master_location as a4", "booking_order.destination", "=", "a4.id", "left outer");
        
        if ($search) {
            $table->where(function ($query) use ($search) {
                $query->where('booking_order.b_date', 'like',  $search . '%')
                    ->orWhere('booking_order.name', 'like',  $search . '%')
                    ->orWhere('a2.name', 'like',  $search . '%')
                    ->orWhere('booking_order.truck_no', 'like',  $search . '%')
                    ->orWhere('a3.name', 'like',  $search . '%')
                    ->orWhere('a4.name', 'like',  $search . '%')
                    ->orWhere('booking_order.invoice', 'like',  $search . '%')
                    ->orWhere('booking_order.i_date', 'like',  $search . '%')
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
    
    public function add()
    {
        $resp_data['title'] = "Booking Order";
        $resp_data['customer'] = MasterCustomer::select(['id','name'])->where('status',1)->get();
        $resp_data['truck'] = MasterTruck::select(['id','name','number'])->where('status',1)->get();
        $resp_data['trailer'] = MasterTrailer::select(['id','name'])->where('status',1)->get();
        $resp_data['loading'] = MasterLoadingPoint::select(['id','name'])->where('status',1)->get();
        $resp_data['location'] = MasterLocation::select(['id','name'])->where('status',1)->get();
        $resp_data['driver'] = MasterDriver::select(['id','name','cell','licence'])->where('status',1)->get();
        $resp_data['dn'] = s_result('admin_setting','id','value',9) + 1;
        return view('orders.booking-order-add',$resp_data);
    }
    
    public function save(Request $request)
    {
        $array = $request->all();
         
        if(isset($array['id'])){
            $id = enc(2, $array['id']);
            $extra = $array['extra'];
            $extra['u_date'] = date('Y-m-d H:i:s');
            unset($array['extra']);
            unset($array['_token']);
            unset($array['id']);
            $array['i_date'] = convertdate($array['i_date']);
            $array['b_date'] = convertdate($array['b_date']);
            $array['loading_date'] = convertdate($array['loading_date']);
            $array['u_date'] = date('Y-m-d H:i:s');
            $array['u_user_id'] = getsession('id');
            $array['status'] = 1;
            $userid = BookingOrder::where('id', $id)->update($array);
            BookingOrderExpense::where('parent_id', $id)->update($extra);
            $msg = 'Updated Successfully.';
        }else{
            $extra = $array['extra'];
            unset($array['extra']);
            unset($array['_token']);
            $array['l_date'] = date('Y-m-d H:i:s');
            $array['user_id'] = getsession('id');
            $array['i_date'] = convertdate($array['i_date']);
            $array['b_date'] = convertdate($array['b_date']);
            $array['loading_date'] = convertdate($array['loading_date']);
            $array['status'] = 1;
            $dn = s_result('admin_setting','id','value',9) + 1;
            $array['name'] = $dn;
            $userid = BookingOrder::insertGetId($array);
            $extra['parent_id'] = $userid;
            BookingOrderExpense::insertGetId($extra);
            \DB::table('admin_setting')->where('id', 9)->update(['value'=>$dn]);
            $msg = 'Saved successfully.';
        }

        if($userid){
             return redirect('/booking-order')->with('success', $msg);
        }else{
             return redirect('/booking-order')->with('error', 'Not saved successfully.');
        }
    }
    
    public function edit($id)
    {
        $result = BookingOrder::find(enc(2,$id));     
        $resp_data['title'] = "Booking Order";
        $resp_data['result'] = $result;
        $resp_data['result']['extra'] = BookingOrderExpense::where('parent_id',enc(2,$id))->get()->toArray(); 
        $resp_data['id'] = enc(1,$result['id']); 
        $resp_data['customer'] = MasterCustomer::select(['id','name'])->where('status',1)->get()->toArray();
        $resp_data['truck'] = MasterTruck::select(['id','name','number'])->where('status',1)->get()->toArray();
        $resp_data['trailer'] = MasterTrailer::select(['id','name'])->where('status',1)->get()->toArray();
        $resp_data['loading'] = MasterLoadingPoint::select(['id','name'])->where('status',1)->get()->toArray();
        $resp_data['location'] = MasterLocation::select(['id','name'])->where('status',1)->get()->toArray();
        $resp_data['driver'] = MasterDriver::select(['id','name','cell','licence'])->where('status',1)->get()->toArray()    ;
        
        return view('orders.booking-order-add',$resp_data);
    }
    
    public function delete($id)
    {
        $result = BookingOrder::where('id',enc(2,$id))->update(['u_date'=> date('Y-m-d H:i:s'),'status' => 0]);    
        if($result){
           return redirect('/booking-order')->with('success', 'Deleted successfully.'); 
        }
        
        return redirect('/booking-order')->with('error', 'Not deleted successfully.');
    }
}
