<?php namespace App\Http\Controllers;

use Carbon\Carbon;
use crocodicstudio_voila\crudbooster\helpers\CB;
use crocodicstudio_voila\crudbooster\helpers\CRUDBooster;
// use DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

// use Session;

class AdminCmsUsersController extends \crocodicstudio_voila\crudbooster\controllers\CBController
{

    public function cbInit()
    {
        # START CONFIGURATION DO NOT REMOVE THIS LINE
        $this->table = 'cms_users';
        $this->primary_key = 'id';
        $this->title_field = "name";
        $this->button_action_style = 'button_icon';
        // $this->button_import = CRUDBooster::me()->id_cms_privileges == 1;
        $this->button_import = false;
        $this->button_export = false;
        $this->button_delete = false;
        $this->button_filter = false;
        $this->button_bulk_action = true;
        # END CONFIGURATION DO NOT REMOVE THIS LINE

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = array();
        $this->col[] = array("label" => "اسم المستخدم", "name" => "username");
        $this->col[] = array("label" => "رقم المهندس", "name" => "num");
        $this->col[] = array("label" => trans('crudbooster.Name'), "name" => "name");
        $this->col[] = array("label" => trans('crudbooster.Email'), "name" => "email");
        $this->col[] = array("label" => "حالة المكتب", "name" => "office_status");
        # END COLUMNS DO NOT REMOVE THIS LINE

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = array();
        if (CRUDBooster::me()->id_cms_privileges == 1) {
            $this->form[] = array("label" => "اسم المستخدم", "name" => "username", 'required' => true, 'validation' => 'required|unique:cms_users,username,' . CRUDBooster::getCurrentId());
            $this->form[] = array("label" => "رقم المهندس", "name" => "num", "type" => "number", 'validation' => 'required|unique:cms_users,num,' . CRUDBooster::getCurrentId());
            $this->form[] = array("label" => "الكوتا", "name" => "cota", "type" => "number");
            $this->form[] = array("label" => "اسم المهندس", "name" => "name", 'required' => true, 'validation' => 'required|alpha_spaces|min:3');
            $this->form[] = array("label" => "حالة المكتب", "name" => "office_status", 'type' => 'text');
        } else {
            $this->form[] = array("label" => "اسم المستخدم", "name" => "username", 'readonly' => true);
            $this->form[] = array("label" => "رقم المهندس", "name" => "num", 'readonly' => true);
            $this->form[] = array("label" => "الكوتا", "name" => "cota", 'readonly' => true);
            $this->form[] = array("label" => "اسم المهندس", "name" => "name", 'readonly' => true);
            $this->form[] = array("label" => "حالة المكتب", "name" => "office_status", 'readonly' => true, 'type' => 'text');

        }

        $this->form[] = array("label" => "البريد الإلكتروني", "name" => "email", 'type' => 'email', 'validation' => 'email|unique:cms_users,email,' . CRUDBooster::getCurrentId());

        if (CRUDBooster::getCurrentMethod() == 'getEdit') {
            $this->form[] = array("label" => "كلمة المرور", "name" => "password", "validation" => "string|confirmed", "type" => "password", "help" => "من فضلك اتركه فارغ إن لم ترد تغيير كلمة المرور");
            $this->form[] = array("label" => "تأكيد كلمة المرور", "name" => "password_confirmation", "type" => "password", "help" => "من فضلك اتركه فارغ إن لم ترد تغيير كلمة المرور");
        } else {
            $this->form[] = array("label" => "كلمة المرور", "name" => "password", "validation" => "string|confirmed", "type" => "password", "help" => "من فضلك أدخل كلمة المرور");
            $this->form[] = array("label" => "تأكيد كلمة المرور", "name" => "password_confirmation", "type" => "password", "help" => "من فضلك أدخل كلمة المرور مرة أخرى");
        }

        $id = CRUDBooster::getCurrentId();
        $method = CRUDBooster::getCurrentMethod();
        //   $custom_select = view('custom.multi_inventories_select', ['id'=>$id,'method'=>$method])->render();
        // $this->form[] = ["label"=>trans('crudbooster.Inventories'),"name"=>"inventories","type"=>"custom","html"=>$custom_select];

        # END FORM DO NOT REMOVE THIS LINE

        $this->load_js[] = asset("vendor/crudbooster/assets/select2/dist/js/select2.full.min.js");

        $this->style_css = "";

        $this->load_css[] = asset("vendor/crudbooster/assets/select2/dist/css/select2.min.css");

    }

    public function getProfile()
    {

        $this->button_addmore = false;
        $this->button_cancel = false;
        $this->button_show = false;
        $this->button_add = false;
        $this->button_delete = true;
        $this->hide_form = ['id_cms_privileges'];

        $data['page_title'] = trans("crudbooster.label_button_profile");
        $data['row'] = CRUDBooster::first('cms_users', CRUDBooster::myId());
        $this->cbView('crudbooster::default.form', $data);
    }

    public function hook_before_add(&$postdata)
    {
        unset($postdata["password_confirmation"]);

        $postdata["id_cms_privileges"] = 2;
        $postdata["photo"] = "/images/portfolio1_logo.png";
    }

    public function hook_after_add($id)
    {

    }

    public function hook_before_edit(&$postdata, $id)
    {
        unset($postdata["password_confirmation"]);
    }

    public function hook_query_index(&$query)
    {
        $query->where("id_cms_privileges", 2);
    }

    public function hook_before_delete($id)
    {
    }

    public function postDoUploadImportData()
    {
        $this->cbLoader();
        if (Request::hasFile('userfile')) {
            $file = Request::file('userfile');
            $ext = $file->getClientOriginalExtension();

            $validator = Validator::make([
                'extension' => $ext,
            ], [
                'extension' => 'in:xls,xlsx,csv',
            ]);

            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return redirect()->back()->with(['message' => implode('<br/>', $message), 'message_type' => 'warning']);
            }

            //Create Directory Monthly
            $filePath = 'uploads/' . CB::myId() . '/' . date('Y-m');
            Storage::makeDirectory($filePath);

            //Move file to storage
            $filename = md5(str_random(5)) . '.' . $ext;
            $url_filename = '';
            if (Storage::putFileAs($filePath, $file, $filename)) {
                $url_filename = $filePath . '/' . $filename;
            }
            $url = CRUDBooster::mainpath('import-data') . '?file=' . base64_encode($url_filename);

            return redirect($url);
        } else {
            return redirect()->back();
        }
    }

    public function postDoImportChunk()
    {
        set_time_limit(0);
        $this->cbLoader();
        $file_md5 = md5(Request::get('file'));

        if (Request::get('file') && Request::get('resume') == 1) {
            $total = Session::get('total_data_import');
            $prog = intval(Cache::get('success_' . $file_md5)) / $total * 100;
            $prog = round($prog);
            if ($prog >= 100) {
                Cache::forget('success_' . $file_md5);
            }

            return response()->json(['progress' => $prog, 'last_error' => Cache::get('error_' . $file_md5)]);
        }

        $select_column = Session::get('select_column');
        $select_column = array_filter($select_column);
        $table_columns = DB::getSchemaBuilder()->getColumnListing($this->table);

        $file = base64_decode(Request::get('file'));
        $file = storage_path('app/' . $file);

        $rows = Excel::load($file, function ($reader) {
        })->get();

        $has_created_at = false;
        if (CRUDBooster::isColumnExists($this->table, 'created_at')) {
            $has_created_at = true;
        }
        $password = password_hash(123455, PASSWORD_DEFAULT);
        $data_import_column = [];
        foreach ($rows as $value) {
            $a = [];
            foreach ($select_column as $sk => $s) {
                $colname = $table_columns[$sk];
                $a[$colname] = $value->$s;
                $a["photo"] = "/images/portfolio1_logo.png";
                $a["id_cms_privileges"] = "2";
                if ($colname == "password" && $value->$s) {
                    $a[$colname] = password_hash($value->$s, PASSWORD_DEFAULT);
                }
            }
            if (!$a["username"]) {
                $a["username"] = $a["num"];
            }
            $user = DB::table($this->table)->where("num", $a["num"])->first();
            if (!$a["password"] && !$user) {
                $a["password"] = $password;
            }
            //----------------------------------//
            try {

                if ($has_created_at) {
                    $a['created_at'] = date('Y-m-d H:i:s');
                }
                $a['username'] = $a['num'];
                if ($user) {
                    DB::table($this->table)->where("id", $user->id)->update([
                        "office_status" => $a["office_status"],
                        "cota" => $a["cota"],
                        "updated_at" => Carbon::now(),
                    ]);
                } else {
                    DB::table($this->table)->insert($a);
                }
                Cache::increment('success_' . $file_md5);
            } catch (\Exception $e) {
                $e = (string) $e;
                Cache::put('error_' . $file_md5, $e, 500);
                Log::log("error","Error importing users files $e");
            }
        }

        return response()->json(['status' => true]);
    }

}