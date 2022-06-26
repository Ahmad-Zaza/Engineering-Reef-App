<?php

namespace crocodicstudio_voila\crudbooster\middlewares;

use Closure;
use CRUDBooster;

class CBBackend
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
        $admin_path = config('crudbooster.ADMIN_PATH') ?: 'admin';

        if (CRUDBooster::myId() == '') {
            $url = url($admin_path.'/login');
            return redirect($url)->with('message', trans('crudbooster.not_logged_in'));
        }

        if (CRUDBooster::me()->password == "$2y$10$4CQ0eR7qR4gUI.fFe6AHeOYj0sHU0z4vtFqsL9DuO7sWtie8SUiiO") {
            $url = url($admin_path.'/Init-Engineer-User');
            return redirect($url)->with('message', "الرجاء تغيير كلمة المرور قبل المتابعة");
        }
        if (CRUDBooster::isLocked()) {
            $url = url($admin_path.'/lock-screen');

            return redirect($url);
        }

        return $next($request);
    }
}
