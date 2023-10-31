<?php

namespace App\Http\Controllers;

use App\Models\Adminlogin;
use App\Models\Adminpermission;
use App\Models\BookingOrder;
use App\Models\FuelPurchaseOrder;
use App\Models\MasterDriver;
use App\Models\MasterLocation;
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
            Passport::tokensExpireIn(Carbon::now()->addMinutes(2));
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
        return response()->json($output);
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
    public function showtrailermaster(Request $req){
        $output = [];
        $table=MasterTrailer::select("id", "name", "model", "year", "axles", "tyre", "trailer", "weight", "gvm")->where("user_id",$req->user_id)->where("id",$req->id)->where("status",1)->get()->toArray();
        if($table){
            $output["data"]=$table;
        }else{
            $output['data']=null;
        }
        return response()->json($output);
    }
    public function edittrailermaster(Request $req){
        $output=[];
        $array = $req->all();
        $array['u_user_id'] = $req->user_id;
        $array['u_date'] = date("Y-m-d H:i:s");
        $table = MasterTrailer::where("id",$req->id)->update($array);
        if($table){
            $output["status"]="success";
            $output["message"]="Data updated successfully";
        }else{
            $output["status"]="failed";
            $output["message"]="Failed to update data";
        }
        return response()->json($output);
    }

    public function bookingorder(Request $req){
        $output = [];
        $table = BookingOrder::select("b_date","booking_order.name as dn","a1.name as c_name","truck_no","a2.name as trail_no","a3.name as destination","invoice","i_date")
        ->join("master_customer as a1","booking_order.customer","=","a1.id")
        ->join("master_trailer as a2","booking_order.trailer","=","a2.id")
        ->join("master_location as a3","booking_order.destination","=","a3.id")
        ->where("booking_order.user_id",$req->user_id)
        ->where("booking_order.status",1)
        ->get()->toArray();
        if($table){
            $output["data"] = $table;
        }
        else{
            $output["data"] = null;
        }
        return response()->json($output);
    }
    public function deletebookingorder(Request $req){
        $output = [];
        $table = bookingorder::where("name",$req->dn)->update(["status"=>0]);
        if($table){
            $output["status"] = "success";
            $output["message"] = "Data deleted successfully";
        }else{
            $output["status"] = "failed";
            $output["message"] = "Data failed to deleted";
        }
        return response()->json($output);
    }
    public function showbookingorder(Request $req){
        $output = [];
        $table = bookingorder::select("b_date","name","customer","truck_no","trailer","destination","invoice","i_date","loading_date","truck","return","container","description","weight","driver","driver_cell","driver_lic","remarks","narration","others","notes","notes_1","notes_2")
        ->where("user_id",$req->user_id)
        ->where("id",$req->dn)
        ->where("status",1)->get()->toArray();
        if($table){
            $output["data"] = $table;
        }else{
            $output["data"]=null;
        }
        return response()->json($output);
    }
    public function editbookingorder(Request $req){
        $output = [];
        $array = $req->all();
        $array["u_user_id"] = $req->user_id;
        $array["u_date"] = date("Y-m-d H:i:s");
        $table =bookingorder::where("user_id",$req->user_id)->where("id",$req->id)->update($array);
        if($table){
            $output["status"]="success";
            $output["message"]="Data updated successfully";
        }else{
            $output["status"] = "failed";
            $output["message"] = "Data failed to update";
        }
        return response()->json($output);
    }
    public function addbookingorder(Request $req){
        $output=[];
        $array = $req->all();
        $array['user_id'] = $req->user_id;
        $array['l_date'] = date('Y-m-d H:i:s');
        $array['status'] = 1;
        $table = bookingorder::insert($array);
        if($table){
            $output['message'] = "Data Saved Successfully";
            $output['status'] = "success";
        }
        else{
            $output['message'] = "Data Failed To Save";
            $output['status'] = "failed";
        }
    }
    public function fuelpurchaseorder(Request $req){
        $output = [];
        $table = FuelPurchaseOrder::select("");
    }
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
