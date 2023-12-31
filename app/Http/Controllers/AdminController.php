<?php

namespace App\Http\Controllers;

use App\Models\Adminlogin;
use App\Models\Adminpermission;
use App\Models\BookingOrder;
use App\Models\FuelPurchaseOrder;
use App\Models\MasterCustomer;
use App\Models\MasterDriver;
use App\Models\MasterLoadingPoint;
use App\Models\MasterLocation;
use App\Models\MasterSupplier;
use App\Models\MasterTrailer;
use App\Models\MasterTruck;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Js;
use Laravel\Passport\Passport;

class AdminController extends Controller
{

    public function index()
    {
        $pc = parent__construct();
        $resp_data['th'] = $pc[0]['fields'];
        $resp_data['classname'] = Route::getFacadeRoot()->current()->uri();
        $resp_data['title'] = "Admin Master";

        $count = Adminlogin::where('status', '!=', '0')->count();

        $per_page = admin_setting(8);
        $current = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $offset = ($current - 1) * $per_page;

        $result = Adminlogin::select(['admin_login.*', 'admin_login.name as fname'])
            ->where('admin_login.status', '!=', 0)
            ->get()->toArray();
        $result = object_to_array($result);

        $retutnarray = array();
        foreach ($result as $r) {
            if ($r['useragent']) {
                $browser = getBrowser($r['useragent']);
                $r['name'] = $browser['name'];
                $r['osname'] = os_info($r['useragent']);
            } else {
                $r['name'] = "";
                $r['osname'] = "";
            }

            array_push($retutnarray, $r);
        }
        $resp_data['users'] = $retutnarray;

        return view('admin', $resp_data);
    }
    public function add()
    {
        $resp_data['title'] = "Admin Master";
        $vp = \DB::table('admin_page')->select('*')
            ->orderBy('name', 'asc')
            ->get()->toArray();
        $vp = object_to_array($vp);

        $resp_data['viewpages'] = $vp;

        return view('admin-add', $resp_data);
    }
    public function edit($id)
    {
        $resp_data['title'] = "Admin Master";
        $vp = \DB::table('admin_page')->select('*')
            ->orderBy('name', 'asc')
            ->get()->toArray();
        $vp = object_to_array($vp);

        $resp_data['viewpages'] = $vp;

        $permis = Adminpermission::select(['page_id', 'fields'])
            ->where('user_id', $id)
            ->get()->toArray();

        $result = Adminlogin::select(['status', 'name', 'username', 'password', 'firstpage', 'email', 'digit_password'])
            ->where('status', '!=', 0)
            ->where('id', $id)
            ->get()->toArray();

        $resp_data['id'] = $id;
        $resp_data['result'] = $result;
        $resp_data['permission'] = group_assoc($permis, 'page_id');

        return view('admin-add', $resp_data);
    }
    public function lock($id)
    {
        $data = explode('-', $id);
        $status = ($data[0] == 2) ? 1 : 2;
        $userid = enc(2, $data[1]);
        Adminlogin::where('id', $userid)->update(['status' => $status]);
        return redirect('/admin')->with('success', 'User updated successfully.');
        exit;
    }
    public function save(Request $r)
    {
        $array = $r->all();
        if (isset($array['id'])) {
            return $this->update($array);
        }
        $name = trim(strtolower($array['name']));
        $password = rand(11111, 999999);

        $fpage = $array['fpage'];

        if (!$name || !$password) {
            return redirect('/admin')->with('error', 'Please fill all the required data.');
            exit;
        }

        $query = Adminlogin::select('id')
            ->where('username', $name)
            ->whereIn('status', [1, 2])
            ->count();

        if ($query == 0) {
            $data = array(
                'name' => $array['fname'],
                'email' => $array['email'],
                'type_id' => 1,
                'username' => $name,
                'password' => $password,
                'firstpage' => $fpage,
                'status' => 1
            );

            $userid = Adminlogin::insertGetId($data);

            $permission = array();
            if (count($array['per'])) {
                foreach ($array['per'] as $p) {
                    $permission[] = array(
                        'user_id' => $userid,
                        'page_id' => $p
                    );
                }
                Adminpermission::insert($permission);
            }
            if (count($array['field'])) {
                foreach ($array['field'] as $k => $l) {
                    $pagesdata = array(
                        'user_id' => $userid,
                        'fields' => implode(',', $l)
                    );
                    Adminpermission::where('user_id', $userid)->where('page_id', $k)->update($pagesdata);
                }
            }
            return redirect('/admin')->with('success', 'User saved successfully.');
            exit;
        } else {
            return redirect('/admin')->with('error', 'Username not available.');
            exit;
        }
    }
    public function update($array)
    {
        $userid = $array['id'];
        $name = trim(strtolower($array['name']));
        $check_dup = Adminlogin::where('username', $name)->whereIn('status', [1, 2])->where('id', '!=', $userid)->count();

        if ($check_dup != 0) {
            return redirect('/admin')->with('error', 'Username not available.');
            exit;
        }

        $data = array(
            'name' => $array['fname'],
            'email' => $array['email'],
            'type_id' => 1,
            'username' => $name,
            'password' => $array['password'],
            'firstpage' => $array['fpage'],
            'status' => 1
        );

        Adminlogin::where('id', $userid)->update($data);
        Adminpermission::where('user_id', $userid)->delete();

        $permission = array();
        if (count($array['per'])) {
            foreach ($array['per'] as $p) {
                $permission[] = array(
                    'user_id' => $userid,
                    'page_id' => $p
                );
            }
            Adminpermission::insert($permission);
        }
        if (count($array['field'])) {
            foreach ($array['field'] as $k => $l) {
                $pagesdata = array(
                    'user_id' => $userid,
                    'fields' => implode(',', $l)
                );
                Adminpermission::where('user_id', $userid)->where('page_id', $k)->update($pagesdata);
            }
        }
        return redirect('/admin')->with('success', 'User updated successfully.');
        exit;
    }
    public function savenot(Request $r)
    {
        echo getsession('id');
        $query = \DB::table('admin_view_permission')->select('id')
            ->where('user_id', getsession('id'))
            ->where('page', $r['page'])
            ->limit(1)
            ->get();

        $x = $query->toArray();
        if ($x) {
            $data = array(
                'fields' => implode(',', $r['r']),
            );
            \DB::table('admin_view_permission')->where('id', $x[0]->id)->update($data);
        } else {
            $data = array(
                'user_id' => getsession('id'),
                'page' => $r['page'],
                'fields' => implode(',', $r['r']),
            );
            \DB::table('admin_view_permission')->insert($data);
        }
    }
    public function notification()
    {
        $resp_data['data'] = [
            ['l_date' => date('Y-m-d H:i:s'), 'msg' => 'New Order Created'],
            ['l_date' => date('Y-m-d H:i:s'), 'msg' => 'New Order Created'],
            ['l_date' => date('Y-m-d H:i:s'), 'msg' => 'New Order Created'],
            ['l_date' => date('Y-m-d H:i:s'), 'msg' => 'New Order Created'],
            ['l_date' => date('Y-m-d H:i:s'), 'msg' => 'New Order Created'],
            ['l_date' => date('Y-m-d H:i:s'), 'msg' => 'New Order Created']
        ];

        return view('notification', $resp_data);
    }
    public function login(Request $req)
    {
        $output = [];
        $validation = Validator::make($req->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if ($validation->fails()) {
            // return response()->json(["error" => $validation->errors()->all()]);
            $output["error"] = $validation->errors();
        }

        $table = Adminlogin::select('id')->where("username", $req->username)->where("password", $req->password)->first();
        if ($table) {
            $token = $table->createToken("api_token")->accessToken;
            // Passport::tokensExpireIn(Carbon::now()->addMinutes(2));
            Adminlogin::where("id", $table->id)->update(["token" => $token, "expire_at" => Carbon::now()]);
            // return response()->json(["status" => "success", "token" => $token, "user_id" => $table->id]);
            $output["status"] = "success";
            $output["token"] = $token;
            $output["user_id"] = $table->id;
        } else {
            if (!array_key_exists("error", $output)) {
                $output["status"] = "failed";
                $output["token"] = null;
                $output["user_id"] = null;
                $output["message"] = "Username or Password is incorrect";
            }
            // $output["username"] = $req->username;
        }
        // $output["header"] = $req->header(); 

    }

    public function dashboard(Request $req)
    {
        $output = [];
        $id = $req->input("user_id");
        $table = Adminlogin::select("token", "name")->where("id", $id)->first();
        if ($table) {
            $output["username"] = $table->name;
            $output["project_name"] = config('app.prj_name');
            $output['status'] = "success";
        } else {
            $output['message'] = "Fail to login due to invalid credential";
            $output['status'] = "failed";
        }

        return response()->json($output);
    }
    public function refresh_token(Request $req)
    {
        $output = [];
        $table = Adminlogin::select("id", "token", "expire_at", "name")->where("token", $req->header("Authorization"))->where("id", $req->user_id)->first();

        if ($table) {
            $token = $table->createToken("refreshtoken")->accessToken;
            $update = Adminlogin::where("id", $req->user_id)->update(["token" => $token, "expire_at" => Carbon::now()]);
            if ($update) {
                $output['status'] = "success";
                $output['message'] = "Token Refressed Successfully";
                $output['token'] = $token;
            }
        }
        return response()->json($output);
    }
    public function truckMaster(Request $req)
    {
        $output = [];
        $id = $req->user_id;
        $table = MasterTruck::select("id", "name", "number", "model", "year", "axles", "weight", "gvm")->where("user_id", $id)->where("status", 1)->get()->toArray();
        if ($table) {
            $output['data'] = $table;
        } else {
            $output['data'] = null;
        }
        return response()->json($output);
    }
    public function addtruckMaster(Request $req)
    {
        $output = [];
        $vali = Validator::make($req->all(), [
            "name" => "required",
            "number" => "required",
            "model" => "required",
            "year" => "required",
            "axles" => "required",
            "weight" => "required",
            "gvm" => "required"
        ]);
        if ($vali->fails()) {
            $output["error"] = $vali->errors();
            $output["status"] = "failed";
        } else {
            $array = $req->all();
            $array["l_date"] = date("Y-m-d H:i:s");
            $array["user_id"] = $req->user_id;
            $array["status"] = 1;
            $table = MasterTruck::insert($array);
            if ($table) {
                $output["status"] = "success";
                $output["message"] = "Data inserted successfully";
            } else {
                $output["status"] = "failed";
                $output["message"] = "Data failed to insert";
            }
        }

        return response()->json($output);
    }
    public function deletetruckMaster(Request $req)
    {
        $output = [];
        $id = $req->id;
        $table = MasterTruck::where("id", $id)->update(["status" => 0]);
        if ($table) {
            $output["status"] = "success";
            $output["message"] = "Data deleted Successfully";
        } else {
            $output["status"] = "failed";
            $output["message"] = "Data Failed to deleted";
        }
        return response()->json($output);
    }
    public function showtruckmaster(Request $req)
    {
        $output = [];
        $table = MasterTruck::select("id", "name", "number", "model", "year", "axles", "weight", "gvm")->where("id", $req->id)->where("user_id", $req->user_id)->where("status", 1)->get()->toArray();
        if ($table) {
            $output['data'] = $table;
        } else {
            $output['data'] = null;
        }
        return response()->json($output);
    }
    public function edittruckmaster(Request $req)
    {
        $output = [];
        $array = $req->all();
        $array["u_date"] = date("Y-m-d H:i:s");
        $array["u_user_id"] = $req->user_id;
        $table = MasterTruck::where("id", $req->id)->update($array);
        if ($table) {
            $output["status"] = "success";
            $output["message"] = "Data Updated Successfully";
        } else {
            $output["status"] = "failed";
            $output["message"] = "Data Failed Update";
        }
        return response()->json($output);
    }
    public function drivermaster(Request $req)
    {
        $output = [];
        // dd($req->id);
        $table = MasterDriver::select(['id', 'name', 'number', 'passport', 'licence', 'licence_date'])->where('user_id', $req->user_id)->where("status", 1)->get()->toArray();
        if ($table) {
            $output['data'] = $table;
        } else {
            // $output['data']=null;       
        }
        return response()->json($output);
    }
    public function adddrivermaster(Request $req)
    {
        $output = [];
        $array = $req->all();
        $array['user_id'] = $req->user_id;
        $array['l_date'] = date('Y-m-d H:i:s');
        $array["status"] = 1;
        $table = MasterDriver::insert($array);
        if ($table) {
            $output["status"] = "success";
            $output["message"] = "Data Inserted Successfully";
        } else {
            $output["status"] = "failed";
            $output['message'] = "Data Failed To Insert";
        }
        return response()->json($output);
    }
    public function editdrivermaster(Request $req)
    {
        $output = [];
        $array = $req->all();
        $array['u_user_id'] = $req->user_id;
        $array['u_date'] = date('Y-m-d H:i:s');
        $table = MasterDriver::where('id', $req->id)->update($array);
        if ($table) {
            $output["status"] = "success";
            $output["message"] = "Data Updated Successfully";
        } else {
            $output["status"] = "failed";
            $output['message'] = "Data Failed To Update";
        }
        return response()->json($output);
    }
    public function showdrivermaster(Request $req)
    {
        $output = [];
        $table = MasterDriver::select(['id', 'name', 'number', 'cell', 'nida', 'passport', 'passport_date', 'licence', 'licence_date', 'tin'])->where('user_id', $req->user_id)->where('status', 1)->where('id', $req->id)->get()->toArray();
        if ($table) {
            $output['data'] = $table;
        } else {
            $output['data'] = null;
        }
        return response()->json($output);
    }
    public function deletedrivermaster(Request $req)
    {
        $output = [];
        $table = MasterDriver::where('id', $req->id)->where("user_id", $req->user_id)->update(['status' => 0]);
        if ($table) {
            $output["status"] = "success";
            $output["message"] = "Deleted Successfully";
        } else {
            $output["status"] = "failed";
            $output['message'] = "Failed to Delete";
        }
        return response()->json($output);
    }

    public function locationmaster(Request $req)
    {
        $output = [];
        $table = MasterLocation::select(["master_location.id", 'master_location.name', 'c.name as c_name', 'km'])
            ->join("master_country as c", "master_location.country", "=", "c.id")
            ->where("master_location.user_id", $req->user_id)
            ->where("master_location.status", 1)
            ->get()->toArray();
        // dd($table);
        if ($table) {
            $output['data'] = $table;
        } else {
            $output['data'] = null;
        }
        return response()->json($output);
    }

    public function addlocationmaster(Request $req)
    {
        $output = [];
        $array = $req->all();
        $array['user_id'] = $req->user_id;
        $array['l_date'] = date('Y-m-d H:i:s');
        $array["status"] = 1;
        $table = MasterLocation::insert($array);
        if ($table) {
            $output["status"] = "success";
            $output["message"] = "Data Added Successfully";
        } else {
            $output["status"] = "failed";
            $output['message'] = "Data Failed to Add";
        }
        return response()->json($output);
    }

    public function editlocationmaster(Request $req)
    {
        $output = [];
        $array = $req->all();
        $array['u_user_id'] = $req->user_id;
        $array['u_date'] = date('Y-m-d H:i:s');
        $table = MasterLocation::where('id', $req->id)->update($array);
        if ($table) {
            $output["status"] = "success";
            $output["message"] = "Data Updated Successfully";
        } else {
            $output["status"] = "failed";
            $output["message"] = "Data failed to update";
        }
        return response()->json($output);
    }
    public function showlocationmaster(Request $req)
    {
        $output = [];
        $table = MasterLocation::select(["master_location.id", 'master_location.name', 'country', 'km'])->where("master_location.id", $req->id)->where("master_location.status", 1)->where("master_location.user_id", $req->user_id)->get()->toArray();
        if ($table) {
            $output['data'] = $table;
        } else {
            $output['data'] = null;
        }
        return response()->json($output);
    }
    public function deletelocationmaster(Request $req)
    {
        $output = [];

        $table = MasterLocation::where("id", $req->id)->where("user_id", $req->user_id)->update(["status" => 0]);
        if ($table) {
            $output["status"] = "success";
            $output["message"] = "Deleted Successfully";
        } else {
            $output["status"] = "failure";
            $output["message"] = "Failed To Delete";
        }
        return response()->json($output);
    }
    public function trailermaster(Request $req)
    {
        $output = [];
        $table = MasterTrailer::select("id", "name", "model", "year", "axles", "tyre", "trailer", "weight", "gvm")->where("user_id", $req->user_id)->where("status", 1)->get()->toArray();
        if ($table) {
            $output["data"] = $table;
        } else {
            $output['data'] = null;
        }
        return response()->json($output);
    }
    public function addtrailermaster(Request $req)
    {
        $output = [];
        $array = $req->all();
        $array['user_id'] = $req->user_id;
        $array['l_date'] = date('Y-m-d H:i:s');
        $array['status'] = 1;
        $table = MasterTrailer::insert($array);
        if ($table) {
            $output["status"] = "success";
            $output["message"] = "Data Added Successfully";
        } else {
            $output["status"] = "failed";
            $output["message"] = "Data Failed To Add";
        }
        return response()->json($output);
    }
    public function deletetrailermaster(Request $req)
    {
        $output = [];
        $table = MasterTrailer::where("id", $req->id)->where("user_id", $req->user_id)->update(["status" => 0]);
        if ($table) {
            $output["status"] = "success";
            $output['message'] = "data deleted successfully";
        } else {
            $output["status"] = "failure";
            $output['message'] = "data failed to delete";
        }
        return response()->json($output);
    }
    public function showtrailermaster(Request $req)
    {
        $output = [];
        $table = MasterTrailer::select("id", "name", "model", "year", "axles", "tyre", "trailer", "weight", "gvm")->where("user_id", $req->user_id)->where("id", $req->id)->where("status", 1)->get()->toArray();
        if ($table) {
            $output["data"] = $table;
        } else {
            $output['data'] = null;
        }
        return response()->json($output);
    }
    public function edittrailermaster(Request $req)
    {
        $output = [];
        $array = $req->all();
        $array['u_user_id'] = $req->user_id;
        $array['u_date'] = date("Y-m-d H:i:s");
        $table = MasterTrailer::where("id", $req->id)->update($array);
        if ($table) {
            $output["status"] = "success";
            $output["message"] = "Data updated successfully";
        } else {
            $output["status"] = "failed";
            $output["message"] = "Failed to update data";
        }
        return response()->json($output);
    }

    public function bookingorder(Request $req) 
    {
        $output = [];
        $table = BookingOrder::select("b_date", "booking_order.name as dn", "a1.name as c_name", "truck_no", "a2.name as trail_no", "a3.name as destination", "invoice", "i_date")
            ->join("master_customer as a1", "booking_order.customer", "=", "a1.id")
            ->join("master_trailer as a2", "booking_order.trailer", "=", "a2.id")
            ->join("master_location as a3", "booking_order.destination", "=", "a3.id")
            ->where("booking_order.user_id", $req->user_id)
            ->where("booking_order.status", 1)
            ->get()->toArray();
        if ($table) {
            $output["data"] = $table;
        } else {
            $output["data"] = null;
        }
        return response()->json($output);
    }
    public function deletebookingorder(Request $req)
    {
        $output = [];
        $table = bookingorder::where("name", $req->dn)->update(["status" => 0]);
        if ($table) {
            $output["status"] = "success";
            $output["message"] = "Data deleted successfully";
        } else {
            $output["status"] = "failed";
            $output["message"] = "Data failed to deleted";
        }
        return response()->json($output);
    }
    public function showbookingorder(Request $req)
    {
        $output = [];
        $obj = [];
        $table1 = bookingorder::select("b_date", "name", "customer", "truck_no as number", "trailer", "destination", "invoice", "i_date", "loading_date", "truck", "return", "container", "description", "weight", "driver", "driver_cell", "driver_lic", "remarks", "narration", "others", "notes", "notes_1", "notes_2")
            ->where("user_id", $req->user_id)
            ->where("id", $req->dn)
            ->where("status", 1)->get()->toArray();
        $table2 = MasterTruck::select("id", "name", "number")->where("status", 1)->get()->toArray();
        $table3 = MasterDriver::select("id", "name", "number")->where("status", 1)->get()->toArray();
        $table4 = MasterCustomer::select("id", "name")->where("status", 1)->get()->toArray();
        $table5 = MasterLoadingPoint::select('id', 'name')->where("status", 1)->get()->toArray();
        $table6 = MasterLocation::select('id', 'name')->where('status', 1)->get()->toArray();
        $obj['bookingorder'] = $table1;
        $obj['truck'] = $table2;
        $obj['driver'] = $table3;
        $obj['customer'] = $table4;
        $obj['loading'] = $table5;
        $obj['location'] = $table6;
        if ($obj) {
            $output["data"] = $obj;
        } else {
            $output["data"] = null;
        }
        return response()->json($output);
    }
    public function editbookingorder(Request $req)
    {
        $output = [];
        $array = $req->all();
        $array["u_user_id"] = $req->user_id;
        $array["u_date"] = date("Y-m-d H:i:s");
        $table = bookingorder::where("user_id", $req->user_id)->where("id", $req->id)->update($array);
        if ($table) {
            $output["status"] = "success";
            $output["message"] = "Data updated successfully";
        } else {
            $output["status"] = "failed";
            $output["message"] = "Data failed to update";
        }
        return response()->json($output);
    }
    public function addbookingorder(Request $req)
    {
        $output = [];
        $obj = [];
        $array = $req->all();
        $array['user_id'] = $req->user_id;
        $array['l_date'] = date('Y-m-d H:i:s');
        $array['status'] = 1;
        bookingorder::insert($array);
        $table2 = MasterTruck::select("id", "name", "number")->where("status", 1)->get()->toArray();
        $table3 = MasterDriver::select("id", "name", "number")->where("status", 1)->get()->toArray();
        $table4 = MasterCustomer::select("id", "name")->where("status", 1)->get()->toArray();
        $table5 = MasterLoadingPoint::select('id', 'name')->where("status", 1)->get()->toArray();
        $table6 = MasterLocation::select('id', 'name')->where('status', 1)->get()->toArray();
        $obj['truck'] = $table2;
        $obj['driver'] = $table3;
        $obj['customer'] = $table4;
        $obj['loading'] = $table5;
        $obj['location'] = $table6;
        if ($obj) {
            $output['data'] = $obj;
            $output['message'] = "Data Saved Successfully";
            $output['status'] = "success";
            $output['data'] = $obj;
        } else {
            $output['message'] = "Data Failed To Save";
            $output['status'] = "failed";
        }
        return response()->json($output);
    }

    //****************** Fuel Purchase Order ************************
    public function fuelpurchaseorder(Request $req)
    {
        $output = [];
        $table = FuelPurchaseOrder::select("fuel_purchase_order.id", 'fuel_purchase_order.name as FPO No.', 'sup.name as Supplier', 'fuel_purchase_order.b_date as Date', 'mt.name as Truck', 'mtl.name as Trailer', 'loc.name as location', "qty_lts as Qty Lts")
            ->join("booking_order as bo", "bo.name", "=", "fuel_purchase_order.name")
            ->join("master_truck as mt", "mt.id", "=", "bo.truck")
            ->join("master_trailer as mtl", "mtl.id", "=", "bo.trailer")
            ->join("master_supplier as sup", "sup.id", "=", "fuel_purchase_order.supplier")
            ->join("master_location as loc", "loc.id", "=", "bo.loading")->where("fuel_purchase_order.user_id", $req->user_id)->where("fuel_purchase_order.status", 1)->get()->toArray();
        if ($table) {
            $output["data"] = $table;
        } else {
            $output["data"] = null;
        }
        return response()->json($output);
    }
    public function addfuelpurchaseorder(Request $req)
    {
        $output = [];
        $obj = [];
        $array = $req->all();
        $array["user_id"] = $req->user_id;
        $array["l_date"] = date("Y-m-d H:i:s");
        $array['status'] = 1;
        $table = FuelPurchaseOrder::insert($array);
        $obj["supplier"] = MasterSupplier::select("name")->where("status", 1)->get()->toArray();
        $obj["dn"] = bookingorder::select("name")->where("status", 1)->get()->toArray();
        $obj["from"] = MasterLoadingPoint::select("name")->where("status", 1)->get()->toArray();
        if ($table) {
            $output["status"] = "success";
            $output['message'] = "Data Added Successfully";
            $output['data'] = $obj;
        } else {
            $output["status"] = "failed";
            $output["message"] = "Data Failed To add";
        }
        return response()->json($output);
    }
    public function deletefuelpurchaseorder(Request $req)
    {
        $output = [];
        $table = fuelpurchaseorder::where("name", $req->name)->update(["status" => 0]);
        if ($table) {
            $output["status"] = "success";
            $output["message"] = "Data Deleted successfully";
        } else {
            $output["status"] = "failed";
            $output["message"] = "Data failed Deleted successfully";
        }
        return response()->json($output);
    }
    //@DPz0ne
    public function showfuelpurchaseorder(Request $req)
    {
        $output = [];
        $obj = [];
        $obj['fuelpurchase'] = fuelpurchaseorder::select("b_date", "name", "dn", "supplier", "from", "qty_lts", "price", "amount")->where("name", $req->fpono)->where("user_id", $req->user_id)->where("status", 1)->get()->toArray();
        $obj["supplier"] = MasterSupplier::select("name")->where("status", 1)->get()->toArray();
        $obj["dn"] = bookingorder::select("name")->where("status", 1)->get()->toArray();
        $obj["from"] = MasterLoadingPoint::select("name")->where("status", 1)->get()->toArray();
        if ($obj) {
            $output['data'] = $obj;
        } else {
            $output['data'] = null;
        }
        return response()->json($output);
    }
    public function editfuelpurchaseorder(Request $req)
    {
        $output = [];
        $array = $req->all();
        $array['u_date'] = date("Y-m-d H:i:s");
        $array['u_user_id'] = $req->user_id;
        $table = fuelpurchaseorder::where("name", $req->name)->where('user_id', $req->user_id)->update($array);
        if ($table) {
            $output["status"] = "success";
            $output['message'] = "Data Updated Successfully";
        } else {
            $output["status"] = "failed";
            $output["message"] = "Data Failed To Update";
        }
        return response()->json($output);
    }
    //@DPz0ne

    //****************** End Fuel Purchase Order ************************

    //****************** Start Delivery Note ************************
    //@DPz0ne
    public function showdeliverynote(Request $req)
    {
        $output = [];
        $table = bookingorder::select("booking_order.name", "b_date", "mc.name as customer", 'mlp.name as laoding', "ml.name as destination", "description", "weight")
            ->join("master_customer as mc", "booking_order.customer", "=", "mc.id")
            ->join("master_loading_point as mlp", "booking_order.loading", "=", "mlp.id")
            ->join("master_location as ml", "booking_order.destination", "=", "ml.id")
            ->where("booking_order.status", 1)->where("booking_order.user_id", $req->user_id)->get()->toArray();
        if ($table) {
            $output['data'] = $table;
        } else {
            $output['data'] = null;
        }
        return response()->json($output);
    }

    //****************** End Delivery Note ************************

    //****************** Start Shortfall ************************
    public function shortfallform(Request $req)
    {
        $output = [];
        $obj = [];
        $table1 = bookingorder::select("booking_order.name", "b_date", "md.name as driver", "mlp.name as laoding", "ml.name as destination", "booking_order.loading_date", "mt.name as trailer", "tm.name as truck", "truck_no")
            ->join("master_driver as md", "booking_order.driver", "=", "md.id")
            ->join("master_loading_point as mlp", "booking_order.loading", "=", "mlp.id")
            ->join("master_location as ml", "Booking_order.destination", "=", "ml.id")
            ->join("master_trailer as mt", "booking_order.trailer", "=", "mt.id")
            ->join("master_truck as tm", "booking_order.truck", "=", "tm.id")
            ->where("booking_order.status", 1)
            ->where("booking_order.user_id", $req->user_id)
            ->get()->toArray();
        $table2 = bookingorder::select("id", "name")->get()->toArray();
        $obj['bookingoerder'] = $table1;
        $obj['dropdownbooking'] = $table2;
        if ($obj) {
            $output['data'] = $obj;
        } else {
            $output['data'] = null;
        }
        return response()->json($output);
    }


    //****************** End Shortfall ************************


    //****************** Start Border Details ************************
    public function borderDetails(Request $req)
    {
        $output = [];
        $table = bookingorder::select("booking_order.name", "b_date", "md.name as customer", "mlp.name as laoding", "ml.name as destination", "booking_order.loading_date", "mt.name as trailer", "tm.name as truck", "truck_no")
            ->join("master_customer as md", "booking_order.customer", "=", "md.id")
            ->join("master_loading_point as mlp", "booking_order.loading", "=", "mlp.id")
            ->join("master_location as ml", "Booking_order.destination", "=", "ml.id")
            ->join("master_trailer as mt", "booking_order.trailer", "=", "mt.id")
            ->join("master_truck as tm", "booking_order.truck", "=", "tm.id")
            ->where("booking_order.status", 1)
            ->where("booking_order.user_id", $req->user_id)
            ->get()->toArray();
        if ($table) {
            $output["data"] = $table;
        } else {
            $output["data"] = null;
        }
        return response()->json($output);
    }
    public function showborderDetails(Request $req)
    {
        $output = [];
        $table = bookingorder::select("booking_order.name", "b_date", "md.name as customer", "mlp.name as laoding", "ml.name as destination", "booking_order.loading_date", "mt.name as trailer", "tm.name as truck", "truck_no")
            ->join("master_customer as md", "booking_order.customer", "=", "md.id")
            ->join("master_loading_point as mlp", "booking_order.loading", "=", "mlp.id")
            ->join("master_location as ml", "Booking_order.destination", "=", "ml.id")
            ->join("master_trailer as mt", "booking_order.trailer", "=", "mt.id")
            ->join("master_truck as tm", "booking_order.truck", "=", "tm.id")
            ->where("booking_order.status", 1)
            ->where("booking_order.user_id", $req->user_id)
            ->get()->toArray();
        $drop1 = MasterCustomer::select("id", "name")->where("status", 1)->get()->toArray();
        $drop2 = MasterTruck::select("id", "name")->where("status", 1)->get()->toArray();
        $drop3 = MasterTrailer::select("id", "name")->where("status", 1)->get()->toArray();
        $drop4 = MasterDriver::select("id", "name")->where("status", 1)->get()->toArray();
        $drop5 = MasterLoadingPoint::select("id", "name")->where("status", 1)->get()->toArray();
        $drop6 = MasterLocation::select("id", "name")->where("status", 1)->get()->toArray();
        if ($table) {
            $output["data"]["bookdetails"] = $table;
            $output["data"]["customer"] = $drop1;
            $output["data"]["truck"] = $drop2;
            $output["data"]["trailer"] = $drop3;
            $output["data"]["truck"] = $drop4;
            $output["data"]["loading_point"] = $drop5;
            $output["data"]["location"] = $drop6;
        } else {
            $output["data"] = null;
        }
        return $output;
    }
    //****************** End Border Details ************************



    //****************** Start Account ************************
    public function account(Request $req)
    {
        $output = [];
        $table = bookingorder::select("booking_order.name", "a1.name as customer", "booking_order.loading_date", "booking_order.weight", "a2.rate", "a2.i_num", "booking_order.i_date")
            ->join("master_customer as a1", "booking_order.customer", "a1.id")
            ->join("account as a2", "booking_order.name", "a2.name")
            ->where("booking_order.user_id", $req->user_id)
            ->where("booking_order.status", 1)
            ->where("a2.status", 1)
            ->get()->toArray();
        if ($table) {
            $output["data"] = $table;
        } else {
            $output['data'] = null;
        }
        return response()->json($output);
    }
    public function deleteborderDetails(Request $req)
    {
        $output = [];
        $table = bookingorder::where("name", $req->name)->where("user_id", $req->user_id)->update(["status" => 0]);
        // dd($table);
        if ($table) {
            $output["status"] = "success";
            $output["message"] = "Data deleted successfully";
        } else {
            $output["status"] = "failed";
            $output["message"] = "Data failed to deleted";
        }
        return $output;
    }
    public function editborderDetails(Request $req)
    {
        $output = [];
        $array = $req->all();
        $array['u_user_id'] = $req->user_id;
        $array['u_date'] = date('Y-m-d H:i:s');
        $table = bookingorder::where("name", $req->name)->where("user_id", $req->user_id)->update($array);
        if ($table) {
            $output["status"] = "success";
            $output['message'] = "Data Updated Successfully";
        } else {
            $output["status"] = "failed";
            $output["message"] = "Data Failed To Update";
        }
        return response()->json($output);
    }
    public function deliverynote(Request $req)
    {
        $output = [];
        $table = bookingorder::select("booking_order.name as dn", "a1.name as customer", "b_date", "a2.name as loading_form", "a3.name as destiantion", "description", "weight")
            ->leftJoin("master_customer as a1", "a1.id", "booking_order.name")
            ->leftJoin("master_loading_point as a2", "a2.id", "booking_order.name")
            ->leftJoin("master_location as a3", "a3.id", "booking_order.name")
            ->where("booking_order.user_id", $req->user_id)
            ->where("booking_order.status", 1)
            ->get()
            ->toArray();
        if ($table) {
            $output["data"] = $table;
        } else {
            $output["data"] = null;
        }
        return response()->json($output);
    }
    //****************** End Account ************************
    //****************** Start Daily Location ************************
    public function dailylocation(Request $req){
        $output = [];
        $table = BookingOrder::select(['cus.name as c_name','loading_date','booking_order.weight','a.rate','a.invoice_date','a.i_num','a1.name as truck','booking_order.truck_no','a2.dep_date','a3.name as from','a4.name as location','booking_order.remarks'])
        ->join("master_customer as cus", "booking_order.customer", "=", "cus.id")
        ->join("account as a","booking_order.name","=",'a.name')
        ->leftJoin("master_truck as a1","booking_order.name",'=','a1.id')
        ->leftJoin("daily_location as a2","booking_order.name",'a2.id')
        ->leftJoin("master_loading_point as a3","booking_order.name",'a3.id')
        ->leftJoin("master_location as a4","booking_order.name","a4.id")
        ->where("booking_order.user_id",$req->user_id)
        ->where("booking_order.status",1)->get()->toArray();
        // dd($table);
        if($table){
            $output['data']=$table;
        }
        else{
            $output['data']=null;
        }
        return response()->json($output);

    }

    //****************** End Daily Location ************************
    
    // public function logout(Request $req){
    //     $output = [];
    //     $token = $req->input("token");
    //     $id = $req->input("user_id");
    //     $table = Adminlogin::select("token")->where("token",$token)->where("id",$id)->first();
    //     if($table){
    //         // dd($table[0]['token']);
    //         $delete = Adminlogin::where("token",$token)->update(["token"=>null]);
    //         if($delete){
    //             $output["message"] = "Successfully Logout";
    //             $output["status"] = "success";
    //         }
    //     }
    //     else{
    //         $output["status"] = "failed";
    //     }
    //     return response()->json($output);
    // }

}