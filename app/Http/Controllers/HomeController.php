<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{    
    public function index()
    {
        $re = [];
        return view('dashboard',$re);
    }
    
    public function vessels()
    {
        $q = DB::table('vessel_form_table')->where('status',1)->orderBy('id','DESC')->first();
        $q = object_to_array($q);
        return view('vessels-add',['data' => $q]);
    }
    
    public function vessels_save(Request $request)
    {
        $arr = $request->all();
        unset($arr['_token']);        
        $arr['status'] = 1;
        $arr['user_id'] = $request->session()->get('user.0.id');
        
        
        
        if($request->id)
        {
            unset($arr['id']);
            $arr['u_date'] = date('Y-m-d H:i:s');
            $id = DB::table('vessel_form_table')->where('id',$request->id)->update($arr);
            return redirect('/dashboard/vessels-form')->with('success', 'updated successfully. ');
        }
        else
        {
            $arr['l_date'] = date('Y-m-d H:i:s');
            $id = DB::table('vessel_form_table')->insert($arr);
            return redirect('/dashboard/vessels-form')->with('error', 'Saved successfully.');
        }
    }
    
    public function ins_acce_spares()
    {
        $q = DB::table('ins_acc_spares_form')->where('status',1)->orderBy('id','DESC')->first();
        $q = object_to_array($q);
        return view('insulation-accessories-spares',['data' => $q]);
    }
    
    public function ins_acce_spares_save(Request $request)
    {
        $arr = $request->all();
        unset($arr['_token']);
        $arr['status'] = 1;
        $arr['user_id'] = $request->session()->get('user.0.id');
        $arr['description'] = nl2br($arr['description']);
        
        
        if($request->id)
        {
            unset($arr['id']);
            $arr['u_date'] = date('Y-m-d H:i:s');
            $id = DB::table('ins_acc_spares_form')->where('id',$request->id)->update($arr);
            return redirect('/dashboard/ins-acce-spares')->with('success', 'Updated successfully. ');
        }
        else
        {
            $arr['l_date'] = date('Y-m-d H:i:s');
            $id = DB::table('ins_acc_spares_form')->insert($arr);
            return redirect('/dashboard/ins-acce-spares')->with('success', 'Saved successfully. ');
        }

    }    
    
    public function agitator_baffle_drive()
    {
        $q = DB::table('aggitator_baffle_drive_form')->where('status',1)->orderBy('id','DESC')->first();
        $q = object_to_array($q);
        return view('agitator-baffle-drive',['data' => $q]);
    }
    
    public function agitator_baffle_drive_save(Request $request)
    {
        $arr = $request->all();
        unset($arr['_token']);
        $arr['status'] = 1;
        $arr['user_id'] = $request->session()->get('user.0.id');
        
        if($request->id)
        {
            unset($arr['id']);
            $arr['u_date'] = date('Y-m-d H:i:s');
            $id = DB::table('aggitator_baffle_drive_form')->where('id',$request->id)->update($arr);
            return redirect('/dashboard/agitator-baffle-drive')->with('success', 'Updated successfully. ');
        }
        else
        {
            $arr['l_date'] = date('Y-m-d H:i:s');
            $id = DB::table('aggitator_baffle_drive_form')->insert($arr);
            return redirect('/dashboard/agitator-baffle-drive')->with('success', 'Saved successfully. ');
        }

    }   
    
    public function html_to_pdf()
    {
        return view('pdf/pdf');        
    }
    
    public function jacket_support_form()
    {
        $q = DB::table('jacket_support_form')->where('status',1)->orderBy('id','DESC')->first();
        $q = object_to_array($q);
        return view('jacket-support',['data' => $q]);
        
    }
    
    public function jacket_support_form_save(Request $request)
    {
        $arr = $request->all();
        unset($arr['_token']);
        $arr['status'] = 1;
        $arr['user_id'] = $request->session()->get('user.0.id');
        
        if($request->id)
        {
            unset($arr['id']);
            $arr['u_date'] = date('Y-m-d H:i:s');
            $id = DB::table('jacket_support_form')->where('id',$request->id)->update($arr);
            return redirect('/dashboard/jacket-support-form')->with('success', 'Updated successfully. ');
        }
        else
        {
            $arr['l_date'] = date('Y-m-d H:i:s');
            $id = DB::table('jacket_support_form')->insert($arr);
            return redirect('/dashboard/jacket-support-form')->with('success', 'Saved successfully. ');
        }

    } 
    
}
