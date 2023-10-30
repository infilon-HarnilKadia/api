<?php

namespace App\Http\Controllers;
use App\Models\MasterTruck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class MasterTruckController extends Controller
{
    
    public function index()
    {
        $pc = parent__construct();
        $resp_data['th'] = $pc[0]['fields'];
        $resp_data['classname'] = Route::getFacadeRoot()->current()->uri();
        $resp_data['title'] = "Truck Master";
        
        $count = MasterTruck::where('status', '!=', '0')->count();

        $per_page = admin_setting(8);
        $current = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $offset = ($current - 1) * $per_page;

        $result = MasterTruck::select(['master_truck.*'])
            ->where('master_truck.status', '!=', 0)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
        
        $resp_data['data'] = $result;
        
        return view('master.master-truck-list',$resp_data);
    }
    
    public function add()
    {
        $resp_data['title'] = "Truck Master";
        return view('master.master-truck-add',$resp_data);
    }
    
    public function save(Request $request)
    {
        $array = $request->all();
        
        if(isset($array['id'])){
            $id = enc(2,$array['id']);
            $query = MasterTruck::select('id')->where('name', $request->name)->where('status', 1)->where('id', '!=', $id) ->count();
        }else{
            $query = MasterTruck::select('id')->where('name', $request->name)->where('status', 1) ->count();
        }
        
        if ($query == 0) {    
            if(isset($array['id'])){
                $id = enc(2, $array['id']);
                unset($array['_token']);
                unset($array['id']);
                $array['u_date'] = date('Y-m-d H:i:s');
                $array['u_user_id'] = getsession('id');
                $array['status'] = 1;
                $userid = MasterTruck::where('id', $id)->update($array);
                $msg = 'Updated Successfully.';
            }else{
                unset($array['_token']);
                $array['l_date'] = date('Y-m-d H:i:s');
                $array['user_id'] = getsession('id');
                $array['status'] = 1;
                $userid = MasterTruck::insertGetId($array);
                $msg = 'Saved successfully.';
            }
            
            if($userid){
                 return redirect('/master-truck')->with('success', $msg);
            }else{
                 return redirect('/master-truck')->with('error', 'Not saved successfully.');
            }
        }else{
            return redirect('/master-truck')->with('error', 'Already exist.');
        }
        
    }
    
    public function edit($id)
    {
        $result = MasterTruck::find(enc(2,$id))->toArray();   
        $resp_data['title'] = "Truck Master";
        $resp_data['result'] = $result;
        $resp_data['id'] = enc(1,$result['id']); 
        return view('master.master-truck-add',$resp_data);
    }
    
    public function delete($id)
    {
        $result = MasterTruck::where('id',enc(2,$id))->update(['u_date'=> date('Y-m-d H:i:s'),'status' => 0]);    
        if($result){
           return redirect('/master-truck')->with('success', 'Deleted successfully.'); 
        }
        
        return redirect('/master-truck')->with('error', 'Not deleted successfully.');
    }
}
