<?php
namespace crocodicstudio_voila\crudbooster\controllers;

use App;
use crocodicstudio_voila\crudbooster\helpers\CRUDBooster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// use McamaraLaravelLocalization\Facades\LaravelLocalization;

class AdminController extends CBController
{
    public function getIndex()
    {
        LaravelLocalization::setLocale(config('setting.LANG'));

        $data = [];
        $data['page_title'] = '<strong>Dashboard</strong>';

        return view('crudbooster::home', $data);
    }

    public function getLockscreen()
    {

        if (!CRUDBooster::myId()) {
            Session::flush();

            return redirect()->route('getLogin')->with('message', trans('crudbooster.alert_session_expired'));
        }

        Session::put('admin_lock', 1);

        return view('crudbooster::lockscreen');
    }

    public function postUnlockScreen()
    {
        $id = CRUDBooster::myId();
        $password = Request::input('password');
        $users = DB::table(config('crudbooster.USER_TABLE'))->where('id', $id)->first();

        if (Hash::check($password, $users->password)) {
            Session::put('admin_lock', 0);

            return redirect(CRUDBooster::adminPath());
        } else {
            echo "<script>alert('" . trans('crudbooster.alert_password_wrong') . "');history.go(-1);</script>";
        }
    }
    public function InitGetEngineerUser()
    {
        LaravelLocalization::setLocale(config('setting.LANG'));
        if (CRUDBooster::myId()) {
            return view('crudbooster::init_engineer_user');
        }
        return redirect('modules/login');
    }
    public function InitEngineerUser()
    {
        $email = Request::input("email");
        $password = Request::input("password");
        $confirm_password = Request::input("confirm_password");
        //--- Check Email
        $emailUsers = DB::table(config('crudbooster.USER_TABLE'))->where("email", $email)->where("id", "<>", CRUDBooster::myId())->get();
        if($emailUsers > 0){
            return redirect()->back()->with(['message' => "البريد الالكتروني موجود مسبقاً, الرجاء إدخال بريد الكتروني مختلف", 'message_type' => 'danger']);
        }
        //-----------------------------------//
        $users = DB::table(config('crudbooster.USER_TABLE'))->where("id", CRUDBooster::myId())->first();
        if (isset($users) && !empty($users)) {
            $new_password = Hash::make($password);
            DB::table(config('crudbooster.USER_TABLE'))->where("id", CRUDBooster::myId())->update([
                "email" => $email,
                'password' => $new_password,
            ]);
            Session::flush();
            @session_start();
            unset($_SESSION["cms_session"]);
            return redirect('modules/login');
        }
        return redirect('modules/login');
    }
    public function getLogin()
    {
        LaravelLocalization::setLocale(config('setting.LANG'));

        if (CRUDBooster::myId()) {
            return redirect(CRUDBooster::adminPath());
        }

        return view('crudbooster::login');
    }

    public function postLogin()
    {
        $validator = Validator::make(Request::all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $message = $validator->errors()->all();
            return redirect()->back()->withInput(Request::except('password'))->with(['message' => implode(', ', $message), 'message_type' => 'danger']);
        }
        $username = Request::input("username");
        $password = Request::input("password");
        $users = DB::table(config('crudbooster.USER_TABLE'))->where("username", $username)->first();
        if (Hash::check($password, $users->password)) {
            $priv = DB::table("cms_privileges")->where("id", $users->id_cms_privileges)->first();
            $roles = DB::table('cms_privileges_roles')->where('id_cms_privileges', $users->id_cms_privileges)->join('cms_moduls', 'cms_moduls.id', '=', 'id_cms_moduls')->select('cms_moduls.name', 'cms_moduls.path', 'is_visible', 'is_create', 'is_read', 'is_edit', 'is_delete')->get();
            @session_start();
            $_SESSION["cms_session"] = $users->id;
            $photo = ($users->photo) ? asset($users->photo) : asset('vendor/crudbooster/avatar.jpg');
            Session::put('admin_id', $users->id);
            Session::put('admin_is_superadmin', $priv->is_superadmin);
            Session::put('admin_name', $users->name);
            Session::put('admin_username', $users->username);
            if (empty($users->email)) {
                return redirect('modules/Init-Engineer-User');
            }
            Session::put('admin_photo', $photo);
            Session::put('admin_privileges_roles', $roles);
            Session::put("admin_privileges", $users->id_cms_privileges);
            Session::put('admin_privileges_name', $priv->name);
            Session::put('admin_lock', 0);
            Session::put('theme_color', $priv->theme_color);
            Session::put("appname", CRUDBooster::getSetting('appname'));
            CRUDBooster::insertLog(trans("crudbooster.log_login", ['email' => $users->email, 'ip' => Request::server('REMOTE_ADDR')]));
            $cb_hook_session = new \App\Http\Controllers\CBHook;
            $cb_hook_session->afterLogin();
            return redirect(CRUDBooster::adminPath());
        } else {
            return redirect()->route('getLogin')->with('message', trans('crudbooster.alert_password_wrong'));
        }
    }

    public function getForgot()
    {
        if (CRUDBooster::myId()) {
            return redirect(CRUDBooster::adminPath());
        }

        return view('crudbooster::forgot');
    }

    public function postForgot()
    {
        $validator = Validator::make(Request::all(), [
            'email' => 'required|email|exists:' . config('crudbooster.USER_TABLE'),
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->all();

            return redirect()->back()->with(['message' => implode(', ', $message), 'message_type' => 'danger']);
        }

        $rand_string = str_random(5);
        $password = Hash::make($rand_string);

        DB::table(config('crudbooster.USER_TABLE'))->where('email', Request::input('email'))->update(['password' => $password]);

        $appname = CRUDBooster::getSetting('appname');
        $user = CRUDBooster::first(config('crudbooster.USER_TABLE'), ['email' => g('email')]);
        $user->password = $rand_string;
        CRUDBooster::sendEmail(['to' => $user->email,
            'data' => [
                "pass" => $rand_string,
                "name" => $user->name,
            ],
            'template' => 'forgot_password_backend']);

        CRUDBooster::insertLog(trans("crudbooster.log_forgot", ['email' => g('email'), 'ip' => Request::server('REMOTE_ADDR')]));

        return redirect()->route('getLogin')->with('message', trans("crudbooster.message_forgot_password"));
    }

    public function getLogout()
    {

        $me = CRUDBooster::me();
        CRUDBooster::insertLog(trans("crudbooster.log_logout", ['email' => $me->email]));

        Session::flush();

        @session_start();
        unset($_SESSION["cms_session"]);

        return redirect()->route('getLogin')->with('message', trans("crudbooster.message_after_logout"));
    }
}