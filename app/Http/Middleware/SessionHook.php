<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Adminlogin;
use App\Models\Home;

class SessionHook
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // $id = getsession('id');
        // $token = getsession('token');

        // if ($id) {
        //     $count = Adminlogin::where('status', '=', '1')->where('id', '=', $id)->where('token', '=', $token)->count();
        //     if (!$count) $id = 0;
        // }
        // if (!$id) {
        //     $request->session()->flush();
        //     return redirect()->to('/');
        // }


        // $maintenance = Home::where('id', '=', 1)->get(['value']);
        // if ($maintenance[0]['value'] == 1 && $request->path() != 'maintenance') {
        //     return redirect()->to('maintenance');
        // }

        // if ($maintenance[0]['value'] == 0 && $request->path() == 'maintenance') {
        //     return redirect()->to('/');
        // }


        // $md_page_keys = get_user_permission();
        // // $md_url = explode('/', $_SERVER['REQUEST_URI']);
        // $md_url = explode('/', $request->path());
        // $md_page = $md_url[0];
        // $md_id = s_result('admin_page', 'url', 'id', $md_page);

        // if (!isset($md_page_keys[$md_id])) {
        //     if ($request->path() != 'permission') {
        //         return redirect()->to('/permission');
        //     }
        // }

        return $next($request);
    }
}
