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
        $validation = $request->validate([
            'p' => 'required'
        ]);
        $id=1;
        $maneger = Maneger::where('id', $id)->select()->first();
        if($maneger->password == $request->p){
            return $next($request);
        }else{
            return response()->json([
                'status' => 0 ,
                'message' => "It's forbidden"
            ]);
        }
    }
}
