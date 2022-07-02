<?php namespace App\Http\Controllers;

// use CRUDBooster;

use App\Http\Models\Deal;
use App\Http\Models\DealDetail;
use App\Http\Models\ImportOperation;
use Carbon\Carbon;
use crocodicstudio_voila\crudbooster\helpers\CB;
use crocodicstudio_voila\crudbooster\helpers\CRUDBooster;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use TCPDF;
use TCPDF_FONTS;

class AdminDealsController extends \crocodicstudio_voila\crudbooster\controllers\CBController
{

    public function cbInit()
    {

        # START CONFIGURATION DO NOT REMOVE THIS LINE
        $this->title_field = "deal_engineer_name";
        $this->limit = "20";
        $this->global_privilege = false;
        $this->button_table_action = true;
        $this->button_bulk_action = false;
        $this->button_table_action = false;
        $this->button_action_style = "button_icon";
        $this->button_action_width = "10px";
        $this->button_add = false;
        $this->button_edit = false;
        $this->button_delete = false;
        $this->button_detail = false;
        $this->button_show = true;
        $this->button_filter = false;
        $this->button_import = CRUDBooster::me()->id_cms_privileges == 1;
        // $this->button_import = true;
        $this->button_export = true;
        $this->show_numbering = true;
        $this->table = "deals";
        # END CONFIGURATION DO NOT REMOVE THIS LINE

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = [];
        $this->col[] = ["label" => "رقم المعاملة", "name" => "deals.file_num"];
        $this->col[] = ["label" => "تاريخ المعاملة", "name" => "deals.file_date"];
        $this->col[] = ["label" => "صاحب العلاقة", "name" => "deals.owner_name"];
        $this->col[] = ["label" => "حالة المعاملة", "name" => "deals.file_status"];
        $this->col[] = ["label" => "مهندس الدراسة", "name" => "study_user.name as study_user_name", "visible" => false];
        $this->col[] = ["label" => "رقم مهندس الدراسة", "name" => "study_user.num as study_user_num", "visible" => false];
        $this->col[] = ["label" => "رقم مهندس الدراسة", "name" => "study_user.cota as study_user_cota", "visible" => false];
        $this->col[] = ["label" => "نوع الدراسة", "name" => "deal_details.study_name"];
        $this->col[] = ["label" => "قيمة الدراسة", "name" => "deal_details.study_value"];
        $this->col[] = ["label" => "مهندس المعاملة", "name" => "deals.file_engineer_id", "join" => "cms_users,name", "visible" => false];
        $this->col[] = ["label" => "رقم المذكرة", "name" => "deals.note_num", "visible" => false];
        $this->col[] = ["label" => "تاريخ المذكرة", "name" => "deals.note_date", "visible" => false];
        $this->col[] = ["name" => "deals.close_month", "visible" => false];
        $this->col[] = ["name" => "deals.close_year", "visible" => false];
        $this->col[] = ["label" => "نوع المعاملة", "name" => "deals.file_type"];
        $this->col[] = ["label" => "منطقة الحصر", "name" => "deals.confinement_area", "visible" => false];
        $this->col[] = ["label" => "المنطقة العقارية", "name" => "deals.real_estate_area", "visible" => false];
        $this->col[] = ["label" => "رقم العقار", "name" => "deals.real_estate_num", "visible" => false];
        $this->col[] = ["label" => "المنطقة التنظيمية", "name" => "deals.organization_name", "visible" => false];
        # END COLUMNS DO NOT REMOVE THIS LINE

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = [];
        $this->form[] = ['label' => 'مهندس المعاملة', 'name' => 'file_engineer_id', 'type' => 'select2', 'width' => 'col-sm-9', 'datatable' => 'cms_users,name'];
        $this->form[] = ['label' => 'رقم المعاملة', 'name' => 'file_num', 'type' => 'number', 'width' => 'col-sm-9'];
        $this->form[] = ['label' => 'تاريخ المعاملة', 'name' => 'file_date', 'type' => 'date', 'width' => 'col-sm-9'];
        $this->form[] = ['label' => 'رقم المذكرة', 'name' => 'note_num', 'type' => 'text', 'width' => 'col-sm-9'];
        $this->form[] = ['label' => 'تاريخ المذكرة', 'name' => 'note_date', 'type' => 'date', 'width' => 'col-sm-9'];
        $this->form[] = ['label' => 'نوع المعاملة', 'name' => 'file_type', 'type' => 'text', 'width' => 'col-sm-9'];
        $this->form[] = ['label' => 'منطقة الحصر', 'name' => 'confinement_area', 'type' => 'text', 'width' => 'col-sm-9'];
        $this->form[] = ['label' => 'المنطقة العقارية', 'name' => 'real_estate_area', 'type' => 'text', 'width' => 'col-sm-9'];
        $this->form[] = ['label' => 'رقم العقار', 'name' => 'real_estate_num', 'type' => 'text', 'width' => 'col-sm-9'];
        $this->form[] = ['label' => 'صاحب العلاقة', 'name' => 'owner_name', 'type' => 'text', 'width' => 'col-sm-9'];
        $this->form[] = ['label' => 'حالة المعاملة', 'name' => 'file_status', 'type' => 'text', 'width' => 'col-sm-9'];
        $this->form[] = ['label' => 'المنطقة التنظيمية', 'name' => 'organization_name', 'type' => 'text', 'width' => 'col-sm-9'];

        $columns[] = ['label' => 'مهندس الدراسة', 'name' => 'study_engineer_id', 'type' => 'select', 'datatable' => 'cms_users,name', 'required' => true];
        $columns[] = ['label' => 'اسم الدراسة', 'name' => 'study_name', 'type' => 'text'];
        $columns[] = ['label' => 'قيمة الدراسة', 'name' => 'study_value', 'type' => 'number'];
        $columns[] = ['label' => 'مبلغ الإقامة مؤجل الصرف', 'name' => 'study_resident_value', 'type' => 'number'];

        $this->form[] = ['label' => 'دراسات المعاملة', 'name' => 'deal_details', 'type' => 'child', 'columns' => $columns, 'table' => 'deal_details', 'foreign_key' => 'deal_id'];

        # END FORM DO NOT REMOVE THIS LINE

        # OLD START FORM
        //$this->form = [];
        # OLD END FORM

        /*
        | ----------------------------------------------------------------------
        | Sub Module
        | ----------------------------------------------------------------------
        | @label          = Label of action
        | @path           = Path of sub module
        | @foreign_key       = foreign key of sub table/module
        | @button_color   = Bootstrap Class (primary,success,warning,danger)
        | @button_icon    = Font Awesome Class
        | @parent_columns = Sparate with comma, e.g : name,created_at
        |
         */
        $this->sub_module = array();
        // $this->sub_module[] = ['label'=>'دراسات المعاملة','path'=>'deal_details','parent_columns'=>'title','foreign_key'=>'service_id','button_color'=>'success'];

        /*
        | ----------------------------------------------------------------------
        | Add More Action Button / Menu
        | ----------------------------------------------------------------------
        | @label       = Label of action
        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
        | @icon        = Font awesome class icon. e.g : fa fa-bars
        | @color        = Default is primary. (primary, warning, succecss, info)
        | @showIf        = If condition when action show. Use field alias. e.g : [id] == 1
        |
         */
        $this->addaction = array();

        /*
        | ----------------------------------------------------------------------
        | Add More Button Selected
        | ----------------------------------------------------------------------
        | @label       = Label of action
        | @icon        = Icon from fontawesome
        | @name        = Name of button
        | Then about the action, you should code at actionButtonSelected method
        |
         */
        $this->button_selected = array();

        /*
        | ----------------------------------------------------------------------
        | Add alert message to this module at overheader
        | ----------------------------------------------------------------------
        | @message = Text of message
        | @type    = warning,success,danger,info
        |
         */
        $this->alert = array();

        /*
        | ----------------------------------------------------------------------
        | Add more button to header button
        | ----------------------------------------------------------------------
        | @label = Name of button
        | @url   = URL Target
        | @icon  = Icon from Awesome.
        |
         */
        $this->index_button = array();

        /*
        | ----------------------------------------------------------------------
        | Customize Table Row Color
        | ----------------------------------------------------------------------
        | @condition = If condition. You may use field alias. E.g : [id] == 1
        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.
        |
         */
        $this->table_row_color = array();

        /*
        | ----------------------------------------------------------------------
        | FESAL VOILA DONT REMOVE THIS LINE
        | ----------------------------------------------------------------------
        | IF NOT SUCCESS ADD  $this->col[] = ["label"=>"Active","name"=>"active"]; IN COLUMNS
        |
         */

        $this->table_row_color[] = ["condition" => "[active]==1", "color" => "success"];
        $this->table_row_color[] = ["condition" => "[active]==0", "color" => "danger"];

        /*
        | ----------------------------------------------------------------------
        | You may use this bellow array to add statistic at dashboard
        | ----------------------------------------------------------------------
        | @label, @count, @icon, @color
        |
         */
        $this->index_statistic = array();

        /*
        | ----------------------------------------------------------------------
        | Add javascript at body
        | ----------------------------------------------------------------------
        | javascript code in the variable
        | $this->script_js = "function() { ... }";
        |
         */
        $this->script_js = null;

        /*
        | ----------------------------------------------------------------------
        | Include HTML Code before index table
        | ----------------------------------------------------------------------
        | html code to display it before index table
        | $this->pre_index_html = "<p>test</p>";
        |
         */
        $this->pre_index_html = null;

        /*
        | ----------------------------------------------------------------------
        | Include HTML Code after index table
        | ----------------------------------------------------------------------
        | html code to display it after index table
        | $this->post_index_html = "<p>test</p>";
        |
         */
        $this->post_index_html = null;

        /*
        | ----------------------------------------------------------------------
        | Include Javascript File
        | ----------------------------------------------------------------------
        | URL of your javascript each array
        | $this->load_js[] = asset("myfile.js");
        |
         */
        $this->load_js = array();

        /*
        | ----------------------------------------------------------------------
        | Add css style at body
        | ----------------------------------------------------------------------
        | css code in the variable
        | $this->style_css = ".style{....}";
        |
         */
        $this->style_css = null;

        /*
        | ----------------------------------------------------------------------
        | Include css File
        | ----------------------------------------------------------------------
        | URL of your css each array
        | $this->load_css[] = asset("myfile.css");
        |
         */
        $this->load_css = array();

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for button selected
    | ----------------------------------------------------------------------
    | @id_selected = the id selected
    | @button_name = the name of button
    |
     */
    public function actionButtonSelected($id_selected, $button_name)
    {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate query of index result
    | ----------------------------------------------------------------------
    | @query = current sql query
    |
     */
    public function hook_query_index(&$query)
    {
        $query->leftjoin("deal_details", "deal_details.deal_id", "=", "deals.id");
        $query->leftjoin("cms_users as study_user", "deal_details.study_engineer_id", "=", "study_user.id");
        if (CrudBooster::me()->id_cms_privileges == 2) {
            $query->where("deal_details.study_engineer_id", CrudBooster::me()->id);
        }
        //Your code here
        if (Request::get("month")) {
            $query->where("close_month", Request::get("month"));
        }
        if (Request::get("year")) {
            $query->where("close_year", Request::get("year"));
        }
        if (Request::get("study_engineer")) {
            $query->where("study_user.num", Request::get("study_engineer"));
        }
        if (!Request::get('year') && !Request::get('month') && !Request::get('study_engineer')) {
            $query->where("deals.id", "-1");
        } else if (!Request::get('year') && !Request::get('month') && CrudBooster::me()->id_cms_privileges == 2) {
            $query->where("deals.id", "-1");
        }
        $query->orderby("deals.file_num", "asc");
        $query->orderby("deal_details.study_name", "asc");
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate row of index table html
    | ----------------------------------------------------------------------
    |
     */
    public function hook_row_index($column_index, &$column_value)
    {
        //Your code here
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate data input before add data is execute
    | ----------------------------------------------------------------------
    | @arr
    |
     */
    public function hook_before_add(&$postdata)
    {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after add public static function called
    | ----------------------------------------------------------------------
    | @id = last insert id
    |
     */
    public function hook_after_add($id)
    {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate data input before update data is execute
    | ----------------------------------------------------------------------
    | @postdata = input post data
    | @id       = current id
    |
     */
    public function hook_before_edit(&$postdata, $id)
    {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after edit public static function called
    | ----------------------------------------------------------------------
    | @id       = current id
    |
     */
    public function hook_after_edit($id)
    {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command before delete public static function called
    | ----------------------------------------------------------------------
    | @id       = current id
    |
     */
    public function hook_before_delete($id)
    {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after delete public static function called
    | ----------------------------------------------------------------------
    | @id       = current id
    |
     */
    public function hook_after_delete($id)
    {
        //Your code here
        DealDetail::where("deal_id", $id)->delete();
    }

    //By the way, you can still create your own method in here... :)

    public function postDoUploadImportData()
    {
        $this->cbLoader();
        if (Request::hasFile('userfile')) {
            $file = Request::file('userfile');
            session()->put("file_name", $file->originalName);
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
            $url = CRUDBooster::mainpath('import-data') . '?file=' . base64_encode($url_filename) . "&import=1";

            return redirect($url);
        } else {
            return redirect()->back();
        }
    }

    public function postDoImportChunk()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $this->cbLoader();
        $file_md5 = md5(Request::get('file'));

        if (Request::get('file') && Request::get('resume') == 1) {
            $total = Session::get('total_data_import');
            $prog = 0;
            if ($total > 0) {
                $prog = intval(Cache::get('success_' . $file_md5)) / $total * 100;
            }
            $prog = round($prog);
            if ($prog >= 100) {
                Cache::forget('success_' . $file_md5);
            }
            return response()->json(['progress' => $prog, 'last_error' => Cache::get('error_' . $file_md5)]);
        }

        $file = base64_decode(Request::get('file'));
        $file = storage_path('app/' . $file);

        $rows = Excel::load($file, function ($reader) {
        })->get();
        $total_file_records = 0;
        $total_successfully = 0;
        $total_failed = 0;
        $failedError = [];
        $operation = ImportOperation::create([
            "type" => "الأعمال الشهرية",
            "date" => Carbon::now(),
            "file_name" => session("file_name"),
            "total_studies_before" => DB::table('deal_details')->get()->count(),
        ]);
        $engineers = DB::table('cms_users')->where("id_cms_privileges", 2)->pluck("id", "num")->toArray();
        $deals = DB::table('deals')->pluck("id", "file_num")->toArray();
        foreach ($rows as $value) {
            dd($value);
            if (!$value["rkm_almaaaml"]) {
                $failedError[] = $value;
                $total_failed++;
                continue;
            }
            $study_engineer_id = $engineers[$value["rkm_mhnds_aldras"]];
            $file_engineer_id = $engineers[$value["rkm_mhnds_almaaaml"]];
            $real_estate_num = $value['rkm_alaakar'] == ":" ? "" : explode(":", $value['rkm_alaakar']);
            $noteData = explode(" : ", $value["rkm_otarykh_almthkr"]);
            $note_date = null;
            try {
                if (trim($noteData[1])) {
                    $note_date = Carbon::createFromFormat("d/m/Y", trim($noteData[1]));
                }
            } catch (Exception $e) {
                Log::log("error", "Error Note Date " . json_encode($value) . " " . $e->getMessage());
            }

            $dealData = [
                "operation_id" => $operation->id,
                "file_engineer_id" => $file_engineer_id,
                "close_year" => $value["aaam_aleghlak"],
                "close_month" => $value["shhr_aleghlak"],
                "file_num" => $value["rkm_almaaaml"],
                "file_date" => $value["tarykh_almaaaml"],
                "note_num" => trim($noteData[0]),
                "note_date" => $note_date,
                "file_type" => $value["noaa_almaaaml"],
                "confinement_area" => $value["mntk_alhsr"],
                "real_estate_area" => $value["almntk_alaakary"],
                "real_estate_num" => $real_estate_num ? implode(",", $real_estate_num) : "",
                "owner_name" => $value["sahb_alaalak"],
                "file_status" => $value["hal_almaaaml"],
                "organization_name" => $value["almntk_altnthymy_latthhr_fy_altkryr"],
                "total_space" => $value["almsah_alejmaly"],
                "license_sum" => $value["mjmoaa_alrkhs"],
                "floors_count" => $value["aadd_altoabk"],
            ];

            $dealDetailsData = [
                "operation_id" => $operation->id,
                "study_engineer_id" => $study_engineer_id,
                "study_name" => $value["noaa_aldras"],
                "study_value" => $value["kym_aldras"],
                "study_resident_value" => $value["mblgh_alekam_moejl_alsrf"],
                "study_file_value" => $value["mblgh_aledbar"],
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];
            try {
                $total_file_records++;
                if (!$deals[$value["rkm_almaaaml"]]) {
                    $deal = Deal::create($dealData);
                    $deals[$value["rkm_almaaaml"]] = $deal->id;
                    DealDetail::where("deal_id",$deal->id)->delete();
                } 
                else {
                    Deal::where("id",$deals[$value["rkm_almaaaml"]])->update($dealData);
                }
                $dealDetailsData["deal_id"] = $deals[$value["rkm_almaaaml"]];
                DB::table("deal_details")->insert($dealDetailsData);
                $total_successfully++;
                Cache::increment('success_' . $file_md5);
            } catch (\Exception $e) {
                Log::log("error", "Error Importing deals " . $e->getMessage());
                $total_failed++;
                $failedError[] = $value;
                $e = (string) $e;
                Cache::put('error_' . $file_md5, $e, 500);
            }
        }
        $operation->update([
            "total_file_records" => $total_file_records,
            "total_successfully" => $total_successfully,
            "total_failed" => $total_failed,
            "failed_errors" => json_encode($failedError),
        ]);
        return response()->json(['status' => true]);
    }

    public function getImportData()
    {
        $this->cbLoader();
        ini_set('memory_limit', '-1');
        $data['page_menu'] = Route::getCurrentRoute()->getActionName();
        $data['page_title'] = trans('crudbooster.import_page_title', ['module' => "deals"]);
        Session::put('select_column', Request::get('select_column'));

        if (view()->exists(CrudBooster::getCurrentModule()->path . '.import')) {
            return view(CrudBooster::getCurrentModule()->path . '.import', $data);
        } else {
            return view('crudbooster::import', $data);
        }

    }
    public function postDoneImport()
    {
        $this->cbLoader();
        $data['page_menu'] = Route::getCurrentRoute()->getActionName();
        $data['page_title'] = trans('crudbooster.import_page_title', ['module' => "deals"]);
        Session::put('select_column', Request::get('select_column'));

        if (view()->exists(CrudBooster::getCurrentModule()->path . '.import')) {
            return view(CrudBooster::getCurrentModule()->path . '.import', $data);
        } else {
            return view('crudbooster::import', $data);
        }
    }

    public function postExportData()
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $this->index_return = true;
        $filetype = Request::input('fileformat');
        $filename = Request::input('filename');
        $papersize = Request::input('page_size');
        $paperorientation = Request::input('page_orientation');
        Request::merge(['limit' => 1000]);
        $response = $this->getExportIndex();
        if (Request::input('default_paper_size')) {
            DB::table('cms_settings')->where('name', 'default_paper_size')->update(['content' => $papersize]);
        }

        switch ($filetype) {
            case "pdf":
                $pdf = new MYPDF("L", PDF_UNIT, "A4", true, 'UTF-8', false);
                $lg = array();
                $lg['a_meta_charset'] = 'UTF-8';
                $lg['a_meta_dir'] = 'rtl';
                $lg['a_meta_language'] = 'ar';
                $lg['w_page'] = 'page';

                // set some language-dependent strings (optional)
                $pdf->setLanguageArray($lg);

                //After Write
                $pdf->setRTL(true);
                //set margins
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                $pdf->SetPrintHeader(false);

                // convert TTF font to TCPDF format and store it on the fonts folder
                $fontFile = $_SERVER["DOCUMENT_ROOT"] . "/fonts/arial.ttf";
                $fontname = TCPDF_FONTS::addTTFfont($fontFile, 'TrueTypeUnicode', '');
                // use the font
                $pdf->SetFont($fontname, '', 10, '', false);

                $pdf->AddPage();
                $pdf->setPrintFooter(true);
                if (view()->exists(CrudBooster::getCurrentModule()->path . '.export')) {
                    $html = view(CrudBooster::getCurrentModule()->path . '.export', $response)->render();
                } else {
                    $html = view('crudbooster::export', $response)->render();
                }
                // return $html;
                $pdf->writeHTML($html, true, false, true, false, '');
                return $pdf->Output('example_006.pdf', 'I');
                break;
            case 'xls':
                Excel::create($filename, function ($excel) use ($response, $filename, $paperorientation) {
                    $excel->setTitle($filename)->setCreator("crudbooster.com")->setCompany(CRUDBooster::getSetting('appname'));
                    $excel->sheet($filename, function ($sheet) use ($response, $paperorientation) {
                        $sheet->setOrientation($paperorientation);
                        if (view()->exists(CrudBooster::getCurrentModule()->path . '.export')) {
                            $sheet->loadview(CrudBooster::getCurrentModule()->path . '.export', $response);
                        } else {
                            $sheet->loadview('crudbooster::export', $response);
                        }
                    });
                })->export('xls');
                break;
            case 'csv':
                Excel::create($filename, function ($excel) use ($response, $filename, $paperorientation) {
                    $excel->setTitle($filename)->setCreator("crudbooster.com")->setCompany(CRUDBooster::getSetting('appname'));
                    $excel->sheet($filename, function ($sheet) use ($response, $paperorientation) {
                        $sheet->setOrientation($paperorientation);
                        if (view()->exists(CrudBooster::getCurrentModule()->path . '.export')) {
                            $sheet->loadview(CrudBooster::getCurrentModule()->path . '.export', $response);
                        } else {
                            $sheet->loadview('crudbooster::export', $response);
                        }
                    });
                })->export('csv');
                break;
        }
    }

    public function getExportIndex()
    {
        $this->cbLoader();

        $module = CRUDBooster::getCurrentModule();
        $data['limit'] = $limit = (Request::get('limit')) ? Request::get('limit') : $this->limit;
        $result = DB::table($this->table);
        $table = $this->table;
        $result->leftjoin("cms_users as file_user", "deals.file_engineer_id", "=", "file_user.id");
        $result->select(["deals.*", "file_user.num as file_user_num", "file_user.name as file_user_name"]);
        if (Request::get("study_engineer") && CRUDBooster::me()->id_cms_privileges == 1) {
            $study_engineer_id = DB::table("cms_users")->where("num", Request::get("study_engineer"))->first()->id;
            $dealIds = DB::table("deal_details")->where("study_engineer_id", $study_engineer_id)->pluck("deal_id")->toArray();
            $result->whereIn("deals.id", $dealIds);
        } else if (CRUDBooster::me()->id_cms_privileges == 2) {
            $study_engineer_id = CRUDBooster::me()->id;
            $dealIds = DB::table("deal_details")->where("study_engineer_id", $study_engineer_id)->pluck("deal_id")->toArray();
            $result->whereIn("deals.id", $dealIds);
        }
        if (Request::get("month")) {
            $result->where("deals.close_month", Request::get("month"));
        }
        if (Request::get("year")) {
            $result->where("deals.close_year", Request::get("year"));
        }
        $result->orderby("file_num", "asc");
        $data['result'] = $result->paginate($limit);

        $total_study_sum = 0;
        $total_file_sum = 0;
        $total_resident_sum = 0;
        foreach ($data['result'] as $row) {
            $deal_details = DB::table("deal_details")
                ->where("deal_id", $row->id)
                ->where("study_engineer_id", $study_engineer_id)
                ->leftjoin("cms_users as study_user", "deal_details.study_engineer_id", "=", "study_user.id")
                ->orderby("study_name", "asc")
                ->get();
            $row->deal_details = $deal_details;
            $row->file_study_sum = 0;
            $row->file_file_sum = 0;
            $row->file_resident_sum = 0;
            foreach ($row->deal_details as $row1) {
                $total_study_sum += $row1->study_value ?: 0;
                $row->file_study_sum += $row1->study_value ?: 0;
                $total_file_sum += $row1->study_file_value ?: 0;
                $row->file_file_sum += $row1->study_file_value ?: 0;
                $total_resident_sum += $row1->study_resident_value ?: 0;
                $row->file_resident_sum += $row1->study_resident_value ?: 0;
            }
        }
        $data['total'] = [
            "total_study_sum" => $total_study_sum,
            "total_file_sum" => $total_file_sum,
            "total_resident_sum" => $total_resident_sum,
        ];
        // dd($data);
        return $data;
    }
}
