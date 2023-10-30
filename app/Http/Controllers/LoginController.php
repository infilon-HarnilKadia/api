<?php

namespace App\Http\Controllers;

use App\Models\Adminlogin;
use App\Models\Adminloginhistory;
use App\Models\Adminpermission;

use Illuminate\Http\Request;


class LoginController extends Controller
{
    public function auth(Request $request)
    {
        $request->session()->flush();
        $r = $request->all();

        $username = $r['user'];
        $password = $r['password'];

        if (!$password or !$username) {
            header("HTTP/1.1 404 Not Found");
        }

        $checkLogin = Adminlogin::select('*')->where('username', $username)->where('password', $password)->where('status', 1)->get();

        if (count($checkLogin)) {
            $data = array(
                'status' => 'success',
                'message' => 'You are successfully logged in.',
            );
            $token = date('Y-m-d H:i:s') . ' - ' . $r['_token'];
            $checkLogin[0]['token'] = $token;
            $request->session()->push('user', $checkLogin[0]);

            $history = array();
            $history[] = os_info();
            $browser = getBrowser();
            $history[] = $browser['platform'];
            $history[] = $browser['name'];
            $history[] = $browser['version'];
            $history[] = $request->header('X-Width');
            $history[] = $request->header('X-Height');

            Adminloginhistory::insert(array(
                "user_id" => $checkLogin[0]['id'],
                "l_date" => date('Y-m-d H:i:s'),
                "useragent" => implode('|', $history),
                "ip" => get_client_ip(),
            ));

            Adminlogin::where('id', '=', $checkLogin[0]['id'])
                ->update(['token' => $token,'ip'=>get_client_ip()]);
            return true;
        } else {
            return abort(401);
        }
    }

    public function verify(Request $request)
    {
        $id = getsession('id');
        if ($id) {
            $checkpermission = Adminpermission::where('user_id', '=', $id)
                ->get()->toArray();
            $request->session()->push('permission', $checkpermission);
            return redirect('/' . getsession('firstpage'))->with('success', 'Login successfully.');
        }
        return redirect('/')->with('error', 'Something went wrong');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/')->with('error', 'Logout');
    }

    public function icons()
    {
        $ards = file_get_contents(url('assets/vendors/iconfonts/mdi/css/materialdesignicons.css'));
        $rd = explode('.mdi-', $ards);
        unset($rd[0]);
        unset($rd[1]);
        $names = array();
        $rd = array_chunk($rd, 3595);
        foreach ($rd[0] as $i) {
            $vr = explode(':before', $i);
            if (isset($vr[0])) {
                array_push($names, $vr[0]);
            }
        }
        $resp_data['icons'] = $names;
        return view('icons', $resp_data);
    }

    public function password()
    {
        return view('password');
    }

    public function password_update(Request $request)
    {
        $array = $request->all();
        $old = trim($array['old']);
        $new = $array['new'];
        $repeat = $array['repeat'];

        if (!$old or !$new or !$repeat) {
            return redirect('/password')->with('error', 'All field are mandatory...');
        }
        if ($new != $repeat) {
            return redirect('/password')->with('error', 'New password and repeat password does not match...');
        }


        $query = Adminlogin::select('id')
            ->where('id', getsession('id'))
            ->where('password', $old)
            ->where('status', 1)
            ->limit(1)
            ->get();

        $r = $query->toArray();
        if ($r) {
            $data = array(
                'password' => $new
            );


            Adminlogin::where('id', '=', $r[0]['id'])
                ->update($data);

            return redirect('/?reset=updated');
        } else {
            return redirect('/password')->with('error', 'Old password does not match...');
        }
    }
}
