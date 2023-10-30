<?php

namespace App\Http\Controllers;

use App\Models\BookingOrder;
use Illuminate\Http\Request;

class DailyLocationController extends Controller
{
    public function index()
    {
        return view("location.daily-location");
    }
    public function add()
    {
        $resp_data['result'] = BookingOrder::select(["id", 'name'])->where('status', 1)->get()->toArray();
        return view("location.daily-location-add", $resp_data);
    }
    public function fetchData(Request $req)
    {
        $result = BookingOrder::select(['cus.name as c_name', 'md.name as md_name', 'md.number as md_num', 'md.cell as md_cell', 'md.licence as md_license', 'md.passport as md_passport', 'loading_date', 'lp.name as l_name', 'ml.name as m_name', 'mt.name as t_name', 'truck_no', 'mtl.name as mt_name', 'booking_order.return', 'booking_order.description', 'booking_order.container', 'booking_order.weight', 'booking_order.b_date as b_date'])
            ->join("master_customer as cus", "booking_order.customer", "=", "cus.id", "left outer")
            ->join("master_truck as mt", "booking_order.truck", "=", "mt.id", "left outer")
            ->join("master_location as ml", "booking_order.destination", "=", "ml.id", "left outer")
            ->join("master_loading_point as lp", "booking_order.loading", "=", "lp.id", "left outer")
            ->join("master_trailer as mtl", "booking_order.trailer", "=", "mtl.id", "left outer")
            ->join("master_driver as md", "booking_order.driver", "=", "md.id", "left outer")
            ->where('booking_order.id', $req->val)->get()->toArray();
?>
        
            <div class="col-lg-12">
                <table class="table table-borderless">
                    <tr>
                        <th>Truck : </th>
                        <td><?= $result[0]['t_name']; ?></td>
                        <th>Truck No. : </th>
                        <td><?= $result[0]['truck_no']; ?></td>
                        <th>Trailer No. : </th>
                        <td><?= $result[0]['mt_name']; ?></td>
                    </tr>
                    <tr>
                        <th>Loading Date : </th>
                        <td><?= $result[0]['loading_date']; ?></td>
                        <th>Departure Date : </th>
                        <td><?= $result[0]['b_date']; ?></td>
                        <th>From : </th>
                        <td><?= $result[0]['l_name']; ?></td>
                    </tr>
                    <tr>
                        <th>Customer : </th>
                        <td><?= $result[0]['c_name']; ?></td>
                        <th>Driver : </th>
                        <td><?= $result[0]['md_name']; ?></td>
                        <th>Driver No. : </th>
                        <td><?php echo $result[0]['md_num'] . "/" . $result[0]['md_cell']; ?></td>
                    </tr>
                    <tr>
                        <th>Driver License No. : </th>
                        <td><?= $result[0]['md_license']; ?></td>
                        <th>Passport No. : </th>
                        <td><?= $result[0]['md_passport']; ?></td>
                    </tr>
                </table>
            </div>
       
<?php
    }
}
