<?php namespace App\Http\Controllers;

use App\Http\Models\FinancialDeal;
use App\Http\Models\ImportOperation;
use Carbon\Carbon;
use crocodicstudio_voila\crudbooster\helpers\CB;
use crocodicstudio_voila\crudbooster\helpers\CRUDBooster;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use TCPDF_FONTS;

class AdminMonthlyAmountsController extends \crocodicstudio_voila\crudbooster\controllers\CBController
{

    public function cbInit()
    {

        # START CONFIGURATION DO NOT REMOVE THIS LINE
        $this->title_field = "id";
        $this->limit = "20";
        $this->orderby = "id,desc";
        $this->global_privilege = false;
        $this->button_table_action = true;
        $this->button_bulk_action = true;
        $this->button_action_style = "button_icon";
        $this->button_add = false;
        $this->button_edit = false;
        $this->button_delete = false;
        $this->button_detail = false;
        $this->button_show = true;
        $this->button_filter = true;
        $this->button_import = CRUDBooster::me()->id_cms_privileges == 1;
        $this->button_export = true;
        $this->table = "financial_deals";
        # END CONFIGURATION DO NOT REMOVE THIS LINE

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = [];
        $this->col[] = ["label" => "المهندس", "name" => "engineer_id", "join" => "cms_users,name"];
        $this->col[] = ["label" => "السنة", "name" => "financial_year"];
        $this->col[] = ["label" => "الشهر", "name" => "financial_month"];
        $this->col[] = ["label" => "العامل", "name" => "factor"];
        $this->col[] = ["label" => "الأتعاب", "name" => "effort"];
        $this->col[] = ["label" => "النظام المالي", "name" => "financial_system"];
        $this->col[] = ["label" => "النسبة", "name" => "percent", "visible" => false];
        $this->col[] = ["label" => "حصة الأتعاب", "name" => "effort_percent", "visible" => false];
        $this->col[] = ["label" => "مورد المشترك", "name" => "share_in", "visible" => false];
        $this->col[] = ["label" => "مقبوض المشترك", "name" => "share_out", "visible" => false];
        $this->col[] = ["label" => "مقبوض التدقيق", "name" => "veri_out", "visible" => false];
        $this->col[] = ["label" => "مقبوض المقيم", "name" => "resident_out", "visible" => false];
        $this->col[] = ["label" => "مقبوض الإضبارة", "name" => "folder_out", "visible" => false];
        $this->col[] = ["label" => "الإشراف", "name" => "supervision", "visible" => false];
        $this->col[] = ["label" => "الحسومات", "name" => "discount", "visible" => false];
        $this->col[] = ["label" => "التعويضات", "name" => "compensation", "visible" => false];
        $this->col[] = ["label" => "المبلغ الكلي", "name" => "total_amount", "visible" => false];
        $this->col[] = ["label" => "ملاحظات", "name" => "notes", "visible" => false];
        # END COLUMNS DO NOT REMOVE THIS LINE

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = [];
        $this->form[] = ["label" => "المهندس", "name" => "engineer_id", "type" => "select2", "required" => true, "validation" => "required|integer|min:0", "datatable" => "cms_users,name"];
        $this->form[] = ["label" => "العامل", "name" => "factor", "type" => "text", "required" => true, "validation" => "required|min:1|max:255"];
        $this->form[] = ["label" => "الأتعاب", "name" => "effort", "type" => "text", "required" => true, "validation" => "required|min:1|max:255"];
        $this->form[] = ["label" => "النظام المالي", "name" => "financial_system", "type" => "text", "required" => true, "validation" => "required|min:1|max:255"];
        $this->form[] = ["label" => "النسبة", "name" => "percent", "type" => "text", "required" => true, "validation" => "required|min:1|max:255"];
        $this->form[] = ["label" => "حصة الأتعاب", "name" => "effort_percent", "type" => "text", "required" => true, "validation" => "required|min:1|max:255"];
        $this->form[] = ["label" => "مورد المشترك", "name" => "share_in", "type" => "text", "required" => true, "validation" => "required|min:1|max:255"];
        $this->form[] = ["label" => "مقبوض المشترك", "name" => "share_out", "type" => "text", "required" => true, "validation" => "required|min:1|max:255"];
        $this->form[] = ["label" => "مقبوض التدقيق", "name" => "veri_out", "type" => "text", "required" => true, "validation" => "required|min:1|max:255"];
        $this->form[] = ["label" => "مقبوض المقيم", "name" => "resident_out", "type" => "text", "required" => true, "validation" => "required|min:1|max:255"];
        $this->form[] = ["label" => "مقبوض الإضبارة", "name" => "folder_out", "type" => "text", "required" => true, "validation" => "required|min:1|max:255"];
        $this->form[] = ["label" => "الإشراف", "name" => "supervision", "type" => "text", "required" => true, "validation" => "required|min:1|max:255"];
        $this->form[] = ["label" => "الحسومات", "name" => "discount", "type" => "text", "required" => true, "validation" => "required|min:1|max:255"];
        $this->form[] = ["label" => "التعويضات", "name" => "compensation", "type" => "text", "required" => true, "validation" => "required|min:1|max:255"];
        $this->form[] = ["label" => "المبلغ الكلي", "name" => "total_amount", "type" => "text", "required" => true, "validation" => "required|min:1|max:255"];
        $this->form[] = ["label" => "ملاحظات", "name" => "notes", "type" => "text", "required" => true, "validation" => "required|min:1|max:255"];

        # END FORM DO NOT REMOVE THIS LINE

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

    public function getIndex()
    {
        if (!Request::get("month") && !Request::get("year")) {
            $month = Db::table('financial_deals')
                ->distinct('financial_month')
                ->select('financial_month')
                ->orderby('financial_month', "desc")
                ->first();
            $year = Db::table('financial_deals')
                ->distinct('financial_year')
                ->select('financial_year')
                ->orderby('financial_year', "desc")
                ->first();
            return redirect(CrudBooster::adminPath('monthly_amounts') . "?month=" . $month->financial_month . "&year=" . $year->financial_year);
        }
        return parent::getIndex();
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
        //Your code here
        $query->leftjoin("cms_users as engineer", "financial_deals.engineer_id", "=", "engineer.id");
        if (CrudBooster::me()->id_cms_privileges == 2) {
            $query->where("financial_deals.engineer_id", CrudBooster::me()->id);
        }
        //Your code here
        if (Request::get("month")) {
            $query->where("financial_month", Request::get("month"));
        }
        if (Request::get("year")) {
            $query->where("financial_year", Request::get("year"));
        }
        if (Request::get("engineer")) {
            $query->where("engineer.num", Request::get("engineer"));
        }
        if ((!Request::get('year') || !Request::get('month') || !Request::get('engineer')) &&  CrudBooster::me()->id_cms_privileges == 1) {
            $query->where("financial_deals.id", "-1");
        }
        else if ((!Request::get('year') || !Request::get('month')) &&  CrudBooster::me()->id_cms_privileges == 2) {
            $query->where("financial_deals.id", "-1");
        }
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
            "type" => "القبض الشهري",
            "date" => Carbon::now(),
            "file_name" => session("file_name"),
            "total_studies_before" => FinancialDeal::get()->count(),
        ]);
        $engineers = DB::table('cms_users')->where("id_cms_privileges", 2)->pluck("id", "num")->toArray();
        foreach ($rows as $value) {
            if (!$value["rkm_almhnds"]) {
                $failedError[] = $value;
                $total_failed++;
                continue;
            }
            $engineer_id = $engineers[$value["rkm_almhnds"]];

            $financialDealData = [
                "operation_id" => $operation->id,
                "engineer_id" => $engineer_id,
                "financial_year" => $value["alsn"],
                "financial_month" => $value["alshhr"],
                "factor" => $value["aaaml_alzmyl"],
                "effort" => $value["alataaab"],
                "financial_system" => $value["alntham_almaly"],
                "percent" => $value["alnsb"],
                "effort_percent" => $value["alhs"],
                "share_out" => $value["mkbod_mshtrk"],
                "share_in" => $value["mord_llmshtrk"],
                "veri_out" => $value["mkbod_tdkyk"],
                "resident_out" => $value["rdyat_mkym"],
                "folder_out" => $value["rdyat_edbar"],
                "supervision" => $value["ashraf"],
                "discount" => $value["hsmyat"],
                "compensation" => $value["taaoydat"],
                "total_amount" => $value["almkbod_alkly"],
                "notes" => $value["mlahthat"],
            ];

            try {
                $total_file_records++;
                $deal = FinancialDeal::create($financialDealData);
                $total_successfully++;
                Cache::increment('success_' . $file_md5);
            } catch (\Exception $e) {
                Log::log("error", "Error Importing FinancialDeals " . $e->getMessage());
                $total_failed++;
                $failedError[] = $value;
                $e = (string) $e;
                Cache::put('error_' . $file_md5, $e, 500);
                Log::log('error',"Error importing monthly paid $e");
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
        $data['page_title'] = trans('crudbooster.import_page_title', ['module' => "القبض الشهري"]);
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
        $data['page_title'] = trans('crudbooster.import_page_title', ['module' => "القبض الشهري"]);
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
        $response = $this->getIndex()["result"][0];
        if (Request::input('default_paper_size')) {
            DB::table('cms_settings')->where('name', 'default_paper_size')->update(['content' => $papersize]);
        }
        switch ($filetype) {
            case "pdf":
                $pdf = new MYPDF("P", PDF_UNIT, "A4", true, 'UTF-8', false);
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
                $pdf->SetPrintHeader(true);

                // convert TTF font to TCPDF format and store it on the fonts folder
                $fontFile = $_SERVER["DOCUMENT_ROOT"] . "/fonts/arial.ttf";
                $fontname = TCPDF_FONTS::addTTFfont($fontFile, 'TrueTypeUnicode', '');
                // use the font
                $pdf->SetFont($fontname, '', 10, '', false);

                $pdf->AddPage();
                $pdf->setPrintFooter(true);
                // dd((array) $response);
                if (view()->exists(CrudBooster::getCurrentModule()->path . '.export')) {
                    $html = view(CrudBooster::getCurrentModule()->path . '.export',(array)  $response)->render();
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
        $result->leftjoin("cms_users as engineer", "financial_deals.engineer_id", "=", "engineer.id");
        $result->select(["financial_deals.*", "engineer.num as engineer_num", "engineer.name as engineer_name"]);
        if (Request::get("engineer") && CRUDBooster::me()->id_cms_privileges == 1) {
            $engineer = DB::table("cms_users")->where("num", Request::get("engineer"))->first();
            $engineer_num = $engineer->num;
            $engineer_name = $engineer->name;
            $result->where("engineer.num", Request::get("engineer"));
        } else if (CRUDBooster::me()->id_cms_privileges == 2) {
            $engineer_num = CRUDBooster::me()->num;
            $engineer_name = CRUDBooster::me()->name;
            $result->whereIn("financial_deals.engineer_id", CRUDBooster::me()->id);
        }
        if (Request::get("month")) {
            $result->where("financial_deals.financial_month", Request::get("month"));
        }
        if (Request::get("year")) {
            $result->where("financial_deals.financial_year", Request::get("year"));
        }
        $result->orderby("id", "desc");
        $data['result'] = $result->paginate($limit);
        $data['engineer_num'] = $engineer_num;
        $data['engineer_name'] = $engineer_name;
        return $data;
    }
}
