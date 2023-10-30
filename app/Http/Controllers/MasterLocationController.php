<?php

namespace App\Http\Controllers;
use App\Models\MasterLocation;
use App\Models\MasterCountry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class MasterLocationController extends Controller
{
    
    public function index()
    {
        $pc = parent__construct();
        $resp_data['th'] = $pc[0]['fields'];
        $resp_data['classname'] = Route::getFacadeRoot()->current()->uri();
        $resp_data['title'] = "Location Master";
        
        $count = MasterLocation::where('status', '!=', '0')->count();

        $per_page = admin_setting(8);
        $current = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $offset = ($current - 1) * $per_page;

        $result = MasterLocation::select(['master_location.id','master_location.name','master_location.km','master_country.name as country'])
            ->join('master_country as master_country','master_country.id','=','master_location.country','left outer')
            ->where('master_location.status', '!=', 0)
            ->orderBy('master_location.id', 'desc')
            ->get()
            ->toArray();
        
        $resp_data['data'] = $result;
        
        return view('master.master-location-list',$resp_data);
    }
    
    public function add()
    {
        $resp_data['title'] = "Location Master";
        $resp_data['country'] = MasterCountry::select(['id','name'])->where('status',1)->get();
        return view('master.master-location-add',$resp_data);
    }
    
    public function save(Request $request)
    {
        $array = $request->all();
        
        $c = MasterLocation::select('id')->where('name', $array['name'])->where('country', $array['country'])->where('status', 1);
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
                $userid = MasterLocation::where('id', $id)->update($array);
                $msg = 'Updated Successfully.';
            }else{
                unset($array['_token']);
                $array['l_date'] = date('Y-m-d H:i:s');
                $array['user_id'] = getsession('id');
                $array['status'] = 1;
                $userid = MasterLocation::insertGetId($array);
                $msg = 'Saved successfully.';
            }

            if($userid){
                 return redirect('/master-location')->with('success', $msg);
            }else{
                 return redirect('/master-location')->with('error', 'Not saved successfully.');
            }
        }else{
            return redirect('/master-location')->with('error', 'Already exist.');
        }
    }
    
    public function edit($id)
    {
        $result = MasterLocation::find(enc(2,$id))->toArray();   
        $resp_data['title'] = "Location Master";
        $resp_data['result'] = $result;
        $resp_data['id'] = enc(1,$result['id']); 
        $resp_data['country'] = MasterCountry::select(['id','name'])->where('status',1)->get();
        return view('master.master-location-add',$resp_data);
    }
    
    public function delete($id)
    {
        $result = MasterLocation::where('id',enc(2,$id))->update(['u_date'=> date('Y-m-d H:i:s'),'status' => 0]);    
        if($result){
           return redirect('/master-location')->with('success', 'Deleted successfully.'); 
        }
        
        return redirect('/master-location')->with('error', 'Not deleted successfully.');
    }
}
