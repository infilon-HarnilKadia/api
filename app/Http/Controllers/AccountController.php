    <?php

    namespace App\Http\Controllers;

    use App\Models\Account;
    use App\Models\BookingOrder;
    use Illuminate\Http\Request;

    class AccountController extends Controllers
    {


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
                $icon1 = nlink('accounts/edit/1/' . enc(1, $v1[98]), 'border-color');
                $icon2 = nlink('accounts/print/' . enc(1, $v1[98]), 'file-pdf');
                $v1[7] = $icon1 . $icon2;
                $icon3 = nlink('accounts/edit/2/' . enc(1, $v1[98]), 'border-color');
                $icon4 =  nlink('accounts/print/' . enc(1, $v1[98]), 'file-pdf');
                $v1[8] = $icon3 . $icon4;
                $v1[9] = nlink('accounts/delete/' . enc(1, $v1[98]), 'delete');
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
            $table = Account::select([]);
            if ($field == "pending") {

                $table->select(array('account.name', 'a2.name as customer', 'a3.loading_date',  'a3.weight', 'rate', 'i_num', 'invoice_date', 'account.id'));
            } elseif ($field == "homepending") {
            } else {
                $table->select(array('account.*'));
            }
            $table->where('account.status', '!=', 0);
            $table->join("master_customer as a2", "account.name", "=", "a2.id")
                ->join("booking_order as a3", "account.name", "=", "a3.id");
            if ($order) {
                $table->orderBy('account.' . $order, $order_type);
            }
            if ($id) {
                $table->where('account.id', $id);
            }
            if ($search) {
                $table->where(function ($query) use ($search) {
                    $query->where('account.name', 'like',  $search . '%')
                        ->orWhere('a2.name', 'like',  $search . '%')
                        ->orWhere('a3.loading_date', 'like',  $search . '%')
                        ->orWhere('a3.weight', 'like',  $search . '%')
                        ->orWhere('account.rate', 'like',  $search . '%')
                        ->orWhere('account.i_num', 'like',  $search . '%')
                        ->orWhere('account.invoice_date', 'like',  $search . '%');
                });
            }
            if ($limit) {
                $table->limit($limit, $start);
            }
            $table->groupBy('account.id');

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
            $table = Account::select(['account.id']);
            if ($type == "pending") {
                $table->where('account.status', '!=', 0);
            }
            $table->join("master_customer as a2", "account.name", "=", "a2.id", "left outer")
                ->join("booking_order as a3", "account.name", "=", "a3.id", "left outer");

            if ($search) {
                $table->where(function ($query) use ($search) {
                    $query->where('account.name', 'like',  $search . '%')
                        ->orWhere('a2.name', 'like',  $search . '%')
                        ->orWhere('a3.loading_date', 'like',  $search . '%')
                        ->orWhere('a3.weight', 'like',  $search . '%')
                        ->orWhere('account.rate', 'like',  $search . '%')
                        ->orWhere('account.i_num', 'like',  $search . '%')
                        ->orWhere('account.invoice_date', 'like',  $search . '%');
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

        public function index()
        {
            $pc = parent__construct();
            $resp_data['th'] = $pc[0]['fields'];
            $resp_data['classname'] = request()->route()->uri();
            return view("accounts.account", $resp_data);
        }
        public function edit($type, $id)
        {
            $resp_data['type'] = $type;
            $data = Account::find(enc(2, $id));
            $result = BookingOrder::select("id", "name")->where('status', 1)->get()->toArray();
            $resp_data["data"] = $data;
            $resp_data["result"] = $result;
            return view("accounts.account-add", $resp_data);
        }
        public function print($id)
        {
            return redirect('/assets/files/Delivery note.pdf');
        }
        public function add(Request $req)
        {
            $resp_data['type'] = $req->type;
            $result = BookingOrder::select("id", "name")->where('status', 1)->get()->toArray();
            $resp_data['num'] = s_result('admin_setting', 'id', 'value', 26) + 1;
            $resp_data['result'] = $result;
            return view("accounts.account-add", $resp_data);
        }
        public function delete($id)
        {
            $table = Account::where('id', enc(2, $id))->update(['u_date' => date('Y-m-d H:i:s'), 'status' => 0]);
            if ($table) {
                return redirect('accounts')->with("success", "Data Deleted Successfully");
            }
        }

        public function save(Request $req)
        {
            $array = $req->all();
            if (isset($array['id'])) {
                unset($array['_token']);
                $array['u_date'] = date('Y-m-d H:i:s');
                $array['invoice_date'] = convertdate($array['invoice_date']);
                $array['u_user_id'] = getsession('id');
                $table = Account::where('id', $array['id'])->update($array);
                if ($table) {
                    return redirect("accounts")->with("success", "Data Updated Successfully");
                }
            } else {

                unset($array['_token']);
                $array['i_date'] = date('Y-m-d H:i:s');
                $array['invoice_date'] = convertdate($array['invoice_date']);
                $array['u_user_id'] = getsession('id');
                $table = Account::insert($array);
                $num = s_result("admin_setting", "id", "value", 26);
                \DB::table('admin_setting')->where('id', 26)->update(['value' => $num]);
                if ($table) {
                    return redirect("accounts")->with("success", "Data Added Successfully");
                }
            }
        }

        public function fetch(Request $req)
        {

            $result = BookingOrder::select(['cus.name as c_name', 'loading_date', 'lp.name as l_name', 'ml.name as m_name', 'mt.name as t_name', 'truck_no', 'mtl.name as mt_name', 'booking_order.return', 'booking_order.description', 'booking_order.container', 'booking_order.weight'])
                ->join("master_customer as cus", "booking_order.customer", "=", "cus.id", "left outer")
                ->join("master_trailer as mt", "booking_order.trailer", "=", "mt.id", "left outer")
                ->join("master_location as ml", "booking_order.destination", "=", "ml.id", "left outer")
                ->join("master_loading_point as lp", "booking_order.loading", "=", "lp.id", "left outer")
                ->join("master_trailer as mtl", "booking_order.trailer", "=", "mtl.id", "left outer")
                ->where('booking_order.id', $req->val)->get()->toArray();
    ?>

            <table class="table-1">
                <tr>
                    <th>Customer : </th>
                    <td><?= $result[0]['c_name'] ?></td>
                    <th>Truck : </th>
                    <td><?= $result[0]['t_name'] ?></td>
                    <th>Description : </th>
                    <td><?= $result[0]['description'] ?></td>
                </tr>
                <tr>
                    <th>Loading Date : </th>
                    <td><?= $result[0]['loading_date'] ?></td>
                    <th>Truck No.: </th>
                    <td><?= $result[0]['truck_no'] ?></td>
                    <th>Container No : </th>
                    <td><?= $result[0]['container'] ?></td>
                </tr>
                <tr>
                    <th>Loading Point : </th>
                    <td><?= $result[0]['l_name'] ?></td>
                    <th>Trailer No. : </th>
                    <td><?= $result[0]['mt_name'] ?></td>
                    <th>Weight : </th>
                    <td><?= $result[0]['weight'] ?></td>
                </tr>
                <tr>
                    <th>Destination : </th>
                    <td><?= $result[0]['m_name'] ?></td>
                    <th>Return Form : </th>
                    <td><?= $result[0]['return'] ?></td>
                </tr>

            </table>
    <?php

            // if ($result) {
            //     return response()->json(["status" => $result]);
            // }
        }
    }
