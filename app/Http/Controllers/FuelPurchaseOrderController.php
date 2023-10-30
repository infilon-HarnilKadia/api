<?php

namespace App\Http\Controllers;
use App\Models\FuelPurchaseOrder;
use App\Models\BookingOrder;
use App\Models\MasterLocation;
use App\Models\MasterStation;
use App\Models\MasterSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class FuelPurchaseOrderController extends Controller
{
    
    public function index()
    {
        $pc = parent__construct();

        $resp_data['th'] = $pc[0]['fields'];
        $resp_data['classname'] = Route::getFacadeRoot()->current()->uri();
        $resp_data['title'] = "Fuel Purchase Order";
        
        return view('orders.fuel-purchase-order-list',$resp_data);
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
            $v1[8] = nlink('fuel-purchase-order/edit/' . enc(1, $v1[98]), 'border-color');
            $v1[9] = nlink('fuel-purchase-order/delete/' . enc(1, $v1[98]), 'delete');
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
        $table = FuelPurchaseOrder::select([]);
        if ($field == "pending") {
            $table->select(array('fuel_purchase_order.name', 'fuel_purchase_order.b_date', 'a2.name as dn', 'a3.name as supplier',  'a4.name as from', 'a5.name as truck', 'a6.name as trailer','fuel_purchase_order.qty_lts','fuel_purchase_order.id'));
        } elseif ($field == "homepending") {
        } else {
            $table->select(array('fuel_purchase_order.*'));
        }
        $table->where('fuel_purchase_order.status', '!=', 0);
        $table->join("booking_order as a2", "fuel_purchase_order.dn", "=", "a2.id", "left outer")
            ->join("master_supplier as a3", "fuel_purchase_order.supplier", "=", "a3.id", "left outer")
            ->join("master_location as a4", "fuel_purchase_order.from", "=", "a4.id", "left outer")
            ->join("master_truck as a5", "a2.truck", "=", "a5.id", "left outer")
            ->join("master_trailer as a6", "a2.trailer", "=", "a6.id", "left outer");
        if ($order) {
            $table->orderBy('fuel_purchase_order.' . $order, $order_type);
        }
        if ($id) {
            $table->where('fuel_purchase_order.id', $id);
        }
        if ($search) {
            $table->where(function ($query) use ($search) {
                $query->where('fuel_purchase_order.name', 'like',  $search . '%')
                    ->orWhere('fuel_purchase_order.b_date', 'like',  $search . '%')
                    ->orWhere('a2.name', 'like',  $search . '%')
                    ->orWhere('a3.name', 'like',  $search . '%')
                    ->orWhere('a4.name', 'like',  $search . '%')
                    ->orWhere('a5.name', 'like',  $search . '%')
                    ->orWhere('a6.name', 'like',  $search . '%')
                    ->orWhere('fuel_purchase_order.qty_lts', 'like',  $search . '%');
            });
        }
        if ($limit) {
            $table->limit($limit, $start);
        }
        $table->groupBy('fuel_purchase_order.id');

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
        $table = FuelPurchaseOrder::select(['fuel_purchase_order.id']);
        if ($type == "pending") {
            $table->where('fuel_purchase_order.status', '!=', 0);
        }
        $table->join("booking_order as a2", "fuel_purchase_order.dn", "=", "a2.id", "left outer")
            ->join("master_supplier as a3", "fuel_purchase_order.supplier", "=", "a3.id", "left outer")
            ->join("master_location as a4", "fuel_purchase_order.from", "=", "a4.id", "left outer")
            ->join("master_truck as a5", "a2.truck", "=", "a5.id", "left outer")
            ->join("master_trailer as a6", "a2.trailer", "=", "a6.id", "left outer");
        
        if ($search) {
            $table->where(function ($query) use ($search) {
                $query->where('fuel_purchase_order.name', 'like',  $search . '%')
                    ->orWhere('fuel_purchase_order.b_date', 'like',  $search . '%')
                    ->orWhere('a2.name', 'like',  $search . '%')
                    ->orWhere('a3.name', 'like',  $search . '%')
                    ->orWhere('a4.name', 'like',  $search . '%')
                    ->orWhere('a5.name', 'like',  $search . '%')
                    ->orWhere('a6.name', 'like',  $search . '%')
                    ->orWhere('fuel_purchase_order.qty_lts', 'like',  $search . '%');
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
        $resp_data['title'] = "Fuel Purchase Order";
        $resp_data['dn'] = BookingOrder::select(['booking_order.id','booking_order.name','booking_order.truck_no','a1.name as trailer','a2.name as driver'])
            ->join('master_trailer as a1','booking_order.trailer','=','a1.id','left outer')
            ->join('master_driver as a2','booking_order.driver','=','a2.id','left outer')
            ->where('booking_order.status',1)->get();
        $resp_data['station'] = MasterStation::select(['id','name'])->where('status',1)->get();
        $resp_data['supplier'] = MasterSupplier::select(['id','name'])->where('status',1)->get();
        $resp_data['location'] = MasterLocation::select(['id','name'])->where('status',1)->get();
        $resp_data['fpo'] = s_result('admin_setting','id','value',10) + 1;
        return view('orders.fuel-purchase-order-add',$resp_data);
    }
    
    public function save(Request $request)
    {
        $array = $request->all();
        if(isset($array['id'])){
            $id = enc(2, $array['id']);
            unset($array['_token']);
            unset($array['id']);
            $array['b_date'] = convertdate($array['b_date']);
            $array['u_date'] = date('Y-m-d H:i:s');
            $array['u_user_id'] = getsession('id');
            $array['status'] = 1;
            $userid = FuelPurchaseOrder::where('id', $id)->update($array);
            $msg = 'Updated Successfully.';
        }else{
            unset($array['_token']);
            $array['l_date'] = date('Y-m-d H:i:s');
            $array['user_id'] = getsession('id');
            $array['b_date'] = convertdate($array['b_date']);
            $array['status'] = 1;
            $fpo = s_result('admin_setting','id','value',10) + 1;
            $array['name'] = $fpo;
            $userid = FuelPurchaseOrder::insertGetId($array);
            \DB::table('admin_setting')->where('id', 10)->update(['value'=>$fpo]);
            $msg = 'Saved successfully.';
        }

        if($userid){
             return redirect('/fuel-purchase-order')->with('success', $msg);
        }else{
             return redirect('/fuel-purchase-order')->with('error', 'Not saved successfully.');
        }
    }
    
    public function edit($id)
    {
        $result = FuelPurchaseOrder::find(enc(2,$id))->toArray();     
        $resp_data['title'] = "Fuel Purchase Order";
        $resp_data['result'] = $result;
        $resp_data['id'] = enc(1,$result['id']); 
        $resp_data['dn'] = BookingOrder::select(['booking_order.id','booking_order.name','booking_order.truck_no','a1.name as trailer','a2.name as driver'])
            ->join('master_trailer as a1','booking_order.trailer','=','a1.id','left outer')
            ->join('master_driver as a2','booking_order.driver','=','a2.id','left outer')
            ->where('booking_order.status',1)->get();
        $resp_data['station'] = MasterStation::select(['id','name'])->where('status',1)->get();
        $resp_data['supplier'] = MasterSupplier::select(['id','name'])->where('status',1)->get();
        $resp_data['location'] = MasterLocation::select(['id','name'])->where('status',1)->get();
        
        return view('orders.fuel-purchase-order-add',$resp_data);
    }
    
    public function delete($id)
    {
        $result = FuelPurchaseOrder::where('id',enc(2,$id))->update(['u_date'=> date('Y-m-d H:i:s'),'status' => 0]);    
        if($result){
           return redirect('/fuel-purchase-order')->with('success', 'Deleted successfully.'); 
        }
        
        return redirect('/fuel-purchase-order')->with('error', 'Not deleted successfully.');
    }
}
