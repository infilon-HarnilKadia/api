<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Home;
use App\Models\Adminlogin;

class MaintenanceHook
{

    public function handle(Request $request, Closure $next)
    {
//        $response = $next($request);
//        $buffer = $response->getContent();
//        if (strpos($buffer, '<pre>') !== false) {
//            $replace = array(
//                '/<!--[^\[](.*?)[^\]]-->/s' => '',
//                "/<\?php/" => '<?php ',
//                "/\r/" => '',
//                "/>\n</" => '><',
//                "/>\s+\n</" => '><',
//                "/>\n\s+</" => '><',
//            );
//        } else {
//            $replace = array(
//                '/<!--[^\[](.*?)[^\]]-->/s' => '',
//                "/<\?php/" => '<?php ',
//                "/\n([\S])/" => '$1',
//                "/\r/" => '',
//                "/\n/" => '',
//                "/\t/" => '',
//                "/ +/" => ' ',
//            );
//        }
//        $buffer = preg_replace(array_keys($replace), array_values($replace), $buffer);
//        $response->setContent($buffer);
//        ini_set('zlib.output_compression', 'On'); // If you like to enable GZip, too!


        $maintenance = Home::where('id', '=', 1)->get(['value']);
        if ($maintenance[0]['value'] == 1 && $request->path() != 'maintenance') {
            return redirect()->to('maintenance');
        }

        if ($maintenance[0]['value'] == 0 && $request->path() == 'maintenance') {
            return redirect()->to('/');
        }

        $id = getsession('id');
        $token = getsession('token');

        if ($id) {
            $count = Adminlogin::where('status', '=', '1')->where('id', '=', $id)->where('token', '=', $token)->count();
            if (!$count) $id = 0;
        }
        if (!$id) {
            $request->session()->flush();
            return redirect()->to('/');
        }

        $response = $next($request);
        return $response;
    }
}
