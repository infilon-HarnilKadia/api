<?php

namespace App\Http\Middleware;

use App\Models\Adminlogin;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class ExpireToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $exptoken = Adminlogin::select("id", "expire_at", "token")->where("id", $request->user_id)->first();
        $now = Carbon::now();
        $diff = $now->diffInMilliseconds($exptoken->expire_at ?? 0);
        $output = [];
        $cmp = strcmp($exptoken->token ?? '', $request->header("Authorization"));
        if ($cmp != 0 ||  $diff > 900000) {
            $output['message'] = "Token Expired";
            $output["status"] = "failed";
            return response()->json($output);
        } else {
            return $next($request);
        }
    }
}
