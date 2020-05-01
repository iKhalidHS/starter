<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckAge
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $age = Auth::user() -> age;   // Auth::user() is an auth method used in bringing all the current user date from db, so here you are selecting id from the user db
        //Auth::id();   // Note you can use method id() in Auth class to select the id directly instead of [ Auth::user()->id ] as above from the user db

        if($age < 15){
            return redirect()->route('not.adult');
        }
        return $next($request); // means continue
    }
}
