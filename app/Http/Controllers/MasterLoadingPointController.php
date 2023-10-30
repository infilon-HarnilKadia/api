<?php

namespace App\Http\Controllers;
use App\Models\MasterLoadingPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class MasterLoadingPointController extends Controller
{
    
    public function index()
    {
        $pc = parent__construct();
        $resp_data['th'] = $pc[0]['fields'];
        $resp_data['classname'] = Route::getFacadeRoot()->current()->uri();
        $resp_data['title'] = "Loading Point Master";
        
        $count = MasterLoadingPoint::where('status', '!=', '0')->count();

        $per_page = admin_setting(8);
        $current = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $offset = ($current - 1) * $per_page;

        $result = MasterLoadingPoint::select(['master_loading_point.*'])
            ->where('master_loading_point.status', '!=', 0)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
        
        $resp_data['data'] = $result;
        
        return view('master.master-loading-point-list',$resp_data);
    }
    
    public function add()
    {
        $resp_data['title'] = "Loading Point Master";
        return view('master.master-loading-point-add',$resp_data);
    }
    
    public function save(Request $request)
    {
        $array = $request->all();
        
        $c = MasterLoadingPoint::select('id')->where('name', $array['name'])->where('status', 1);
        if(isset($array['id'])){
            $id = enc(2,$array['id']);
            $c->where('id', '!=', $id)->count();
        }
        
        $query = $c->count();
        
        if ($query == 0) {
            if(isset($array['id'])){
                $id = enc(2, $array['id']);
                unset($array['_token']);
                unset($array['id']);
                $array['u_date'] = date('Y-m-d H:i:s');
                $array['u_user_id'] = getsession('id');
                $array['status'] = 1;
                $userid = MasterLoadingPoint::where('id', $id)->update($array);
                $msg = 'Updated Successfully.';
            }else{
                unset($array['_token']);
                $array['l_date'] = date('Y-m-d H:i:s');
                $array['user_id'] = getsession('id');
                $array['status'] = 1;
                $userid = MasterLoadingPoint::insertGetId($array);
                $msg = 'Saved successfully.';
            }

            if($userid){
                 return redirect('/master-loading-point')->with('success', $msg);
            }else{
                 return redirect('/master-loading-point')->with('error', 'Not saved successfully.');
            }
        }else{
            return redirect('/master-loading-point')->with('error', 'Already exist.');
        }
    }
    
    public function edit($id)
    {
        $result = MasterLoadingPoint::find(enc(2,$id))->toArray();   
        $resp_data['title'] = "Loading Point Master";
        $resp_data['result'] = $result;
        $resp_data['id'] = enc(1,$result['id']); 
        return view('master.master-loading-point-add',$resp_data);
    }
    
    public function delete($id)
    {
        $result = MasterLoadingPoint::where('id',enc(2,$id))->update(['u_date'=> date('Y-m-d H:i:s'),'status' => 0]);    
        if($result){
           return redirect('/master-loading-point')->with('success', 'Deleted successfully.'); 
        }
        
        return redirect('/master-loading-point')->with('error', 'Not deleted successfully.');
    }
}
