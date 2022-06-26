<?php

namespace crocodicstudio_voila\crudbooster\middlewares;

use Closure;
use crocodicstudio_voila\crudbooster\helpers\CRUDBooster;

// use CRUDBooster;

class CBAuthAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {


        CRUDBooster::authAPI();

        return $next($request);
    }
}
