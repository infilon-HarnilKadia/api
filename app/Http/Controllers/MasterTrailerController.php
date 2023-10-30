<?php

namespace App\Http\Controllers;
use App\Models\MasterTrailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class MasterTrailerController extends Controller
{
    
    public function index()
    {
        $pc = parent__construct();
        $resp_data['th'] = $pc[0]['fields'];
        $resp_data['classname'] = Route::getFacadeRoot()->current()->uri();
        $resp_data['title'] = "Trailer Master";
        
        $count = MasterTrailer::where('status', '!=', '0')->count();

        $per_page = admin_setting(8);
        $current = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $offset = ($current - 1) * $per_page;

        $result = MasterTrailer::select(['master_trailer.*'])
            ->where('master_trailer.status', '!=', 0)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
        
        $resp_data['data'] = $result;
        
        return view('master.master-trailer-list',$resp_data);
    }
    
    public function add()
    {
        $resp_data['title'] = "Trailer Master";
        return view('master.master-trailer-add',$resp_data);
    }
    
    public function save(Request $request)
    {
        $array = $request->all();
        
        if(isset($array['id'])){
            $id = enc(2, $array['id']);
            unset($array['_token']);
            unset($array['id']);
            $array['u_date'] = date('Y-m-d H:i:s');
            $array['u_user_id'] = getsession('id');
            $array['status'] = 1;
            $userid = MasterTrailer::where('id', $id)->update($array);
            $msg = 'Updated Successfully.';
        }else{
            unset($array['_token']);
            $array['l_date'] = date('Y-m-d H:i:s');
            $array['user_id'] = getsession('id');
            $array['status'] = 1;
            $userid = MasterTrailer::insertGetId($array);
            $msg = 'Saved successfully.';
        }

        if($userid){
             return redirect('/master-trailer')->with('success', $msg);
        }else{
             return redirect('/master-trailer')->with('error', 'Not saved successfully.');
        }
    }
    
    public function edit($id)
    {
        $result = MasterTrailer::find(enc(2,$id))->toArray();   
        $resp_data['title'] = "Trailer Master";
        $resp_data['result'] = $result;
        $resp_data['id'] = enc(1,$result['id']); 
        return view('master.master-trailer-add',$resp_data);
    }
    
    public function delete($id)
    {
        $result = MasterTrailer::where('id',enc(2,$id))->update(['u_date'=> date('Y-m-d H:i:s'),'status' => 0]);    
        if($result){
           return redirect('/master-trailer')->with('success', 'Deleted successfully.'); 
        }
        
        return redirect('/master-trailer')->with('error', 'Not deleted successfully.');
    }
}
