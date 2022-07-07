<?php 
namespace App\Http\Controllers;

use App\Http\Models\Deal;
use App\Http\Models\DealDetail;
use crocodicstudio_voila\crudbooster\helpers\CRUDBooster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;
use TCPDF;
use TCPDF_FONTS;

class AdminDealsDetailsController extends \crocodicstudio_voila\crudbooster\controllers\CBController
{

    public function cbInit()
    {
        # START CONFIGURATION DO NOT REMOVE THIS LINE
        $this->table = "deals";
        $this->title_field = "owner_name";
        $this->limit = 20;
        $this->orderby = "id,desc";
        $this->show_numbering = false;
        $this->global_privilege = false;
        $this->button_table_action = true;
        $this->button_action_style = "button_icon";
        $this->button_action_width = "10px";
        $this->button_add = false;
        $this->button_delete = false;
        $this->button_edit = false;
        $this->button_detail = false;
        $this->button_show = true;
        $this->button_filter = false;
        $this->button_export = false;
        $this->button_import = false;
        $this->button_export = false;
        $this->show_numbering = true;
        $this->button_bulk_action = false;
        $this->sidebar_mode = "normal"; //normal,mini,collapse,collapse-mini
        # END CONFIGURATION DO NOT REMOVE THIS LINE

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = [];
        $this->col[] = array("label" => "رقم المعاملة", "name" => "file_num");
        $this->col[] = array("label" => "تاريخ المعاملة", "name" => "file_date");
        $this->col[] = array("label" => "نوع المعاملة", "name" => "file_type");
        $this->col[] = ["label" => "صاحب العلاقة", "name" => "deals.owner_name"];
        $this->col[] = ["label" => "نوع المعاملة", "name" => "deals.file_type"];
        $this->col[] = ["label" => "منطقة الحصر", "name" => "deals.confinement_area"];
        $this->col[] = ["label" => "المنطقة العقارية", "name" => "deals.real_estate_area"];
        $this->col[] = ["label" => "رقم العقار", "name" => "deals.real_estate_num", "visible" => false];
        $this->col[] = ["label" => "عدد الطوابق", "name" => "deals.floors_count", "visible" => false];
        $this->col[] = ["label" => "مجموع الرخصة", "name" => "deals.license_sum", "visible" => false];
        $this->col[] = ["label" => "المساحة الإجمالية", "name" => "deals.total_space", "visible" => false];

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
        $query->leftjoin("cms_users as file_user", "deals.file_engineer_id", "=", "file_user.id");
        //Your code here
        if (Crudbooster::me()->id_cms_privileges == 2) {
            $query->where("file_engineer_id", CrudBooster::me()->id);
        }
        //Your code here
        if (Request::get("month")) {
            $query->where("close_month", Request::get("month"));
        }
        if (Request::get("year")) {
            $query->where("close_year", Request::get("year"));
        }
        if (Request::get("file_engineer")) {
            $query->where("file_user.num", Request::get("file_engineer"));
        }
        if (!Request::get('year') && !Request::get('month') && !Request::get('file_engineer')) {
            $query->where("deals.id", "-1");
        } else if (!Request::get('year') && !Request::get('month') && CRUDBooster::me()->id_cms_privileges == 2) {
            $query->where("deals.id", "-1");
        }
        $query->orderby("deals.file_num", "asc");
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

    public function getExportIndex()
    {
        $this->cbLoader();

        $module = CRUDBooster::getCurrentModule();
        $data['limit'] = $limit = (Request::get('limit')) ? Request::get('limit') : $this->limit;
        $result = DB::table($this->table);
        $table = $this->table;
        $result->leftjoin("cms_users as file_user", "deals.file_engineer_id", "=", "file_user.id");
        $result->select(["deals.*", "file_user.num as file_user_num", "file_user.name as file_user_name"]);
        if (Request::get("file_engineer") && CRUDBooster::me()->id_cms_privileges == 1) {
            $study_engineer_id = DB::table("cms_users")->where("num", Request::get("study_engineer"))->first()->id;
            $dealIds = DB::table("deal_details")->where("study_engineer_id", $study_engineer_id)->pluck("deal_id")->toArray();
            $result->whereIn("deals.id", $dealIds);
        } else if (CRUDBooster::me()->id_cms_privileges == 2) {
            $file_engineer_id = CRUDBooster::me()->id;
            $dealIds = DB::table("deal_details")->where("file_engineer_id", $file_engineer_id)->pluck("deal_id")->toArray();
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
        return $data;
    }

    public function getExportPdf($id)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $deal = Deal::where("id", $id)->with("file_engineer")->first();
        $details = [];
        $deal_details = DealDetail::where("deal_id", $id)
            ->with("study_engineer")
            ->get();
        foreach ($deal_details as $item) {
            if (!$details[$item->study_name]) {
                $details[$item->study_name]["total_study"] = 0;
                $details[$item->study_name]["total_file"] = 0;
                $details[$item->study_name]["total_resident"] = 0;
            }
            $details[$item->study_name]["items"][] = $item;
			$details[$item->study_name]["total_study"] += $item->study_value;
			$details[$item->study_name]["total_file"] += $item->study_file_value;
			$details[$item->study_name]["total_resident"] += $item->study_resident_value;
        }
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
        // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetPrintHeader(false);

        // convert TTF font to TCPDF format and store it on the fonts folder
        $fontFile = $_SERVER["DOCUMENT_ROOT"] . "/fonts/arial.ttf";
        $fontname = TCPDF_FONTS::addTTFfont($fontFile, 'TrueTypeUnicode', '');
        // use the font
        $pdf->SetFont($fontname, '', 10, '', false);

        $pdf->AddPage();
        // $pdf->setPrintFooter(true);
        if (view()->exists(CrudBooster::getCurrentModule()->path . '.export')) {
            $html = view(CrudBooster::getCurrentModule()->path . '.export', compact("deal", "details"))->render();
        } else {
            $html = view('crudbooster::export', compact("deal", "details"))->render();
        }
        // return $html;
        $pdf->writeHTML($html, true, false, true, false, '');
        return $pdf->Output('example_006.pdf', 'I');
    }
}

class MYPDF extends TCPDF
{
    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('dejavusans', '', 8);
        // Page number
        $this->Cell(0, 10, $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . " " . 'صفحة', 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    public function Header(){
        $fontFile = $_SERVER["DOCUMENT_ROOT"] . "/fonts/arial.ttf";
        $fontname = TCPDF_FONTS::addTTFfont($fontFile, 'TrueTypeUnicode', '');
        // // use the font
        $this->SetFont($fontname, '', 10, '', false);
        // $image_file = "images/portfolio_logo.png";
        // $this->Image($image_file, 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Title
        // $this->Image($image_file, 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        // $this->Image($image_file, 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Title
        // $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'L', 0, '', 0, false, 'M', 'M');

        $this->Cell(0, 15, 'نقابة المـهـنـدسـيـن فرع ريف دمشق', 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}