<?php

namespace App\Http\Middleware;

use App\Models\Maneger;
use Closure;
use Illuminate\Http\Request;

class Check
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
        $pass = $request->header('password');
        $id=1;
        $maneger = Maneger::where('id', $id)->select()->first();
        if($maneger->password == $pass){
            if($maneger->status == true){
                return $next($request);
            }else{
                return response()->json([
                    'message' => 'Your session has expired, please log in again.'
                ],401);
            }
        }else{
            return response()->json([
                'message' => 'Forbidden'
            ],403);
        }
    }
}
