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
use Illuminate\Support\Arr;

class BordersController extends Controller
{
    public function index()
    {
        // $pc = parent__construct();
        // $col['th'] = $pc[0]['fields'];
        // $col['classname'] = Route::getFacadeRoot()->current()->uri();
        return view("borders.borders-border-details-list");
    }

    public function edit($id) 
    {
        $result = BookingOrder::find(enc(2, $id))->toArray();
        $resp_data['title'] = "Booking Order";
        $resp_data['result'] = $result;
        $resp_data['result']['extra'] = BookingOrderExpense::where('parent_id', enc(2, $id))->get()->toArray();
        $resp_data['id'] = enc(1, $result['id']);
        $resp_data['customer'] = MasterCustomer::select(['id', 'name'])->where('status', 1)->get();
        $resp_data['truck'] = MasterTruck::select(['id', 'name', 'number'])->where('status', 1)->get();
        $resp_data['trailer'] = MasterTrailer::select(['id', 'name'])->where('status', 1)->get();
        $resp_data['loading'] = MasterLoadingPoint::select(['id', 'name'])->where('status', 1)->get();
        $resp_data['location'] = MasterLocation::select(['id', 'name'])->where('status', 1)->get();
        $resp_data['driver'] = MasterDriver::select(['id', 'name', 'cell', 'licence'])->where('status', 1)->get();

        return view("borders.borders-border-details-add", $resp_data);
    }

    public function delete($id)
    {
        $result = BookingOrder::where('id',enc(2,$id))->update(['u_date'=> date('Y-m-d H:i:s'),'status'=>0]);
        if($result)
        {
            return redirect("/borders-border-details")->with("success", "Data Deleted Successfully..");
        }
    }
    public function save(Request $request)
    {
        $array = $request->all();
        if (isset($array['id'])) {
            $id = enc(2, $array['id']);
            unset($array['_token']);
            unset($array['id']);
            $array['loading_date'] = convertdate($array['loading_date']);
            $array['u_date'] = date('Y-m-d H:i:s');
            $array['u_user_id'] = getsession('id');
            $array['status'] = 1;
            $userid = BookingOrder::where('id', $id)->update($array);
            if ($userid) {
                return redirect("/borders-border-details")->with("success", "Data Updated Successfully..");
            }
        }
    }

    public function ajax(Request $request)
    {
        $requestData = $request->all();

        $order = $requestData['order'][0]['column'];
        $columns = $requestData['columns'][$order]['name'];
        $data = $this->getlist('', 'pending', $columns, $requestData['order'][0]['dir'], $requestData['search']['value'], $requestData['start'], $requestData['length'], $requestData['more']);
        $dataarray = [];

        foreach ($data as $row) {
            $v1 = array_values($row);
            $v1[98] = $v1[1];
            $v1[9] = nlink('borders-border-details/edit/' . enc(1, $v1[98]), 'border-color'); // You need to define the nlink and enc functions.
            $v1[10] = nlink('broders-border-details/delete/' . enc(1,$v1[98]), 'delete'); // You need to define the nlink and enc functions.
            unset($v1[98]);
            $dataarray[] = $v1;
        }

        $json = [
            'draw' => $requestData['draw'],
            'recordsTotal' => $this->rows('pending', '', $requestData['more']),
            'recordsFiltered' => $this->rows('pending', $requestData['search']['value'], $requestData['more']),
            'data' => $dataarray,
        ];

        return response()->json($json);
    }

    private function getlist($id = '', $field = '', $order = '', $order_type = '', $search = '', $start = '', $limit = '', $advance_search = '')
    {
        $table = BookingOrder::query();

        if ($field == "pending") {
            $table->select(['booking_order.b_date', 'booking_order.name', 'a2.name as customer', 'a5.name as names', 'booking_order.truck_no', 'a3.name as trailer', 'a6.name as lname', 'a7.name as dname', 'booking_order.description']);
        } elseif ($field == "homepending") {
            // Add logic for homepending if needed.
        } else {
            $table->select(['booking_order.*']);
        }

        $table->where('booking_order.status', '!=', 0)
            ->join("master_customer as a2", "booking_order.customer", "=", "a2.id")
            ->join("master_trailer as a3", "booking_order.trailer", "=", "a3.id")
            ->join("master_location as a4", "booking_order.destination", "=", "a4.id")
            ->join('master_truck as a5', "booking_order.truck", "=", "a5.id")
            ->join('master_loading_point as a6', "booking_order.loading", "=", "a6.id")
            ->join('master_location as a7', "booking_order.destination", "=", "a7.id");

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
                    ->orWhere('a5.name', 'like',  $search . '%')
                    ->orWhere('booking_order.truck_no', 'like',  $search . '%')
                    ->orWhere('a3.name', 'like',  $search . '%')
                    ->orWhere('booking_order.loading', 'like',  $search . '%')
                    ->orWhere('booking_order.destination', 'like',  $search . '%')
                    ->orWhere('booking_order.description', 'like',  $search . '%');
            });
        }

        if ($limit) {
            $table->skip($start)->take($limit);
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
        $result = $query->toArray();

        return $result;
    }

    private function rows($type, $search, $advance_search)
    {
        $table = BookingOrder::query();

        if ($type == "pending") {
            $table->where('booking_order.status', '!=', 0);
        }

        $table->join("master_customer as a2", "booking_order.customer", "=", "a2.id")
            ->join("master_trailer as a3", "booking_order.trailer", "=", "a3.id")
            ->join('master_truck as a5', "booking_order.truck", "=", "a5.id")
            ->join("master_location as a4", "booking_order.destination", "=", "a4.id");

        if ($search) {
            $table->where(function ($query) use ($search) {
                $query->where('booking_order.b_date', 'like',  $search . '%')
                    ->orWhere('booking_order.name', 'like',  $search . '%')
                    ->orWhere('a2.name', 'like',  $search . '%')
                    ->orWhere('a5.name', 'like',  $search . '%')
                    ->orWhere('booking_order.truck_no', 'like',  $search . '%')
                    ->orWhere('a3.name', 'like',  $search . '%')
                    ->orWhere('booking_order.loading', 'like',  $search . '%')
                    ->orWhere('booking_order.destination', 'like',  $search . '%')
                    ->orWhere('booking_order.description', 'like',  $search . '%');
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

        return $table->count();
    }
}
