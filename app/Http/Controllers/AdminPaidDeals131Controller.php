<?php namespace App\Http\Controllers;

use App\Http\Models\Deal;
use App\Http\Models\ImportOperation;
use App\Http\Models\PaidDeal;
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

class AdminPaidDeals131Controller extends \crocodicstudio_voila\crudbooster\controllers\CBController
{

    public function cbInit()
    {
        # START CONFIGURATION DO NOT REMOVE THIS LINE
        $this->table = "deals";
        $this->title_field = "id";
        $this->limit = 20;
        $this->orderby = "id,desc";
        $this->show_numbering = false;
        $this->global_privilege = false;
        $this->button_table_action = true;
        $this->button_action_style = "button_icon";
        $this->button_add = false;
        $this->button_delete = false;
        $this->button_edit = false;
        $this->button_detail = false;
        $this->button_show = false;
        $this->button_filter = true;
        $this->button_export = true;
        $this->button_import = false;
        $this->button_bulk_action = true;
        $this->sidebar_mode = "normal"; //normal,mini,collapse,collapse-mini
        # END CONFIGURATION DO NOT REMOVE THIS LINE

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = [];
        $this->col[] = array("label" => "رقم المعاملة", "name" => "deals.file_num");
        $this->col[] = array("label" => "تاريخ المعاملة", "name" => "deals.file_date");
        $this->col[] = array("label" => "صاحب العلاقة", "name" => "deals.owner_name");
        $this->col[] = array("label" => "المنطقة العقارية", "name" => "deals.real_estate_area");
        $this->col[] = array("label" => "أرقام العقارات", "name" => "deals.real_estate_num");

        # END COLUMNS DO NOT REMOVE THIS LINE
        # START FORM DO NOT REMOVE THIS LINE
        $this->form = [];

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
        $this->addaction[] = ['label' => 'طباعة PDF', 'title' => 'طباعة PDF', 'url' => CRUDBooster::adminPath('deal_details/export-pdf/[id]'), 'icon' => 'fa fa-pdf', 'color' => 'success', 'target' => "_blank"];

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
            $month = Db::table('paid_deals')
                ->whereNull("deleted_at")
                ->distinct('month')
                ->select('month')
                ->orderby('month', "desc")
                ->first();
            $year = Db::table('paid_deals')
                ->whereNull("deleted_at")
                ->distinct('year')
                ->select('year')
                ->orderby('year', "desc")
                ->first();
            if ($month->month && $year->year) {
                return redirect(CrudBooster::adminPath('paid_deals131') . "?month=" . $month->month . "&year=" . $year->year);
            }
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
        $query->leftjoin("cms_users as deal_engineer", "deals.file_engineer_id", "=", "deal_engineer.id");

        if (CrudBooster::me()->id_cms_privileges == 2) {
            $query->where("deals.file_engineer_id", CrudBooster::me()->id);
        }
        if (Request::get("engineer")) {
            $query->where("deal_engineer.num", Request::get("engineer"));
        }
        $query->whereNotNull("deals.file_num");
        if ((!Request::get('year') || !Request::get('month') || !Request::get('engineer')) && CrudBooster::me()->id_cms_privileges == 1) {
            $query->where("deals.id", "-1");
        } else if ((!Request::get('year') || !Request::get('month')) && CrudBooster::me()->id_cms_privileges == 2) {
            $query->where("paid_deals.id", "-1");
        }
        //-------------------------//
        if (Request::get('year')) {
            $query->whereRaw("(deals.paid_year > ".Request::get('year')." or deals.paid_year is null)");
            // $query->where("deals.paid_year", "<=", Request::get('year'));
        }
        if (Request::get('month')) {
            $query->whereRaw("(deals.paid_month > ".Request::get('month')." or deals.paid_month is null)");
            // $query->where("deals.paid_month", "<=", Request::get('month'));
        }
        // dd($query->toSql());
        //-------------------------//
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
        $response = $this->getIndex();
        // dd($response);
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
                $pdf->SetPrintHeader(true);
                $pdf->SetMargins(10, 18, 10);

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

}
