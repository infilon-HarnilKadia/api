<?php

namespace App\Http\Controllers;
use App\Models\MasterDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class MasterDriverController extends Controller
{
    
    public function index()
    {
        $pc = parent__construct();
        $resp_data['th'] = $pc[0]['fields'];
        $resp_data['classname'] = Route::getFacadeRoot()->current()->uri();
        $resp_data['title'] = "Driver Master";
        
        $count = MasterDriver::where('status', '!=', '0')->count();

        $per_page = admin_setting(8);
        $current = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $offset = ($current - 1) * $per_page;

        $result = MasterDriver::select(['master_driver.*'])
            ->where('master_driver.status', '!=', 0)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
        
        $resp_data['data'] = $result;
        
        return view('master.master-driver-list',$resp_data);
    }
    
    public function add()
    {
        $resp_data['title'] = "Driver Master";
        return view('master.master-driver-add',$resp_data);
    }
    
    public function save(Request $request)
    {
        $array = $request->all();
        
        if(isset($array['id'])){
            $id = enc(2, $array['id']);
            unset($array['_token']);
            unset($array['id']);
            $array['passport_date'] = convertdate($array['passport_date']);
            $array['licence_date'] = convertdate($array['licence_date']);
            $array['u_date'] = date('Y-m-d H:i:s');
            $array['u_user_id'] = getsession('id');
            $array['status'] = 1;
            $userid = MasterDriver::where('id', $id)->update($array);
            $msg = 'Updated Successfully.';
        }else{
            unset($array['_token']);
            $array['l_date'] = date('Y-m-d H:i:s');
            $array['user_id'] = getsession('id');
            $array['passport_date'] = convertdate($array['passport_date']);
            $array['licence_date'] = convertdate($array['licence_date']);
            $array['status'] = 1;
            $userid = MasterDriver::insertGetId($array);
            $msg = 'Saved successfully.';
        }

        if($userid){
             return redirect('/master-driver')->with('success', $msg);
        }else{
             return redirect('/master-driver')->with('error', 'Not saved successfully.');
        }
    }
    
    public function edit($id)
    {
        $result = MasterDriver::find(enc(2,$id))->toArray();   
        $resp_data['title'] = "Driver Master";
        $resp_data['result'] = $result;
        $resp_data['id'] = enc(1,$result['id']); 
        return view('master.master-driver-add',$resp_data);
    }
    
    public function delete($id)
    {
        $result = MasterDriver::where('id',enc(2,$id))->update(['u_date'=> date('Y-m-d H:i:s'),'status' => 0]);    
        if($result){
           return redirect('/master-driver')->with('success', 'Deleted successfully.'); 
        }
        
        return redirect('/master-driver')->with('error', 'Not deleted successfully.');
    }
}
