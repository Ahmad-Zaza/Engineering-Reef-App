<?php

namespace App\Http\Controllers;

use crocodicstudio_voila\crudbooster\helpers\CRUDBooster as HelpersCRUDBooster;
use App\Http\Models\FinancialDeal;
use App\Http\Models\ImportOperation;
use App\ImportFileDetails;
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
use Schema;
use TCPDF;
use TCPDF_FONTS;

class AdminImportFileDetailsController extends \crocodicstudio_voila\crudbooster\controllers\CBController
{

	public function cbInit()
	{

		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->title_field = "name";
		$this->limit = "20";
		$this->orderby = "sorting,asc";
		$this->global_privilege = false;
		$this->button_table_action = true;
		$this->button_bulk_action = true;
		$this->button_action_style = "button_icon";
		$this->button_add = true;
		$this->button_edit = false;
		$this->button_delete = false;
		$this->button_detail = true;
		$this->button_show = true;
		$this->button_filter = true;
		$this->button_import = false;
		$this->button_export = false;
		$this->table = "import_file_details";
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = [];
		$this->col[] = ["label" => "النوع", "name" => "type"];
		$this->col[] = ["label" => "عدد الأسطر الكلي", "name" => "total_file_records"];
		$this->col[] = ["label" => "عدد الأسطر المستوردة بنجاح", "name" => "total_successfully"];
		$this->col[] = ["label" => "عدد الأسطر المستوردة الفاشلة", "name" => "total_failed"];
		$this->col[] = ["label" => "الحالة", "name" => "file_status"];
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = [];
		$this->form[] = ['label' => 'الملف', 'name' => 'path', 'type' => 'upload', 'validation' => 'required', 'width' => 'col-sm-9'];
		$this->form[] = ['label' => 'النوع', 'name' => 'type', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-9'];
		# END FORM DO NOT REMOVE THIS LINE

		# OLD START FORM
		//$this->form = [];
		//$this->form[] = ['label'=>'الملف','name'=>'path','type'=>'upload','validation'=>'required','width'=>'col-sm-9'];
		//$this->form[] = ['label'=>'النوع','name'=>'type','type'=>'select','validation'=>'required','width'=>'col-sm-9'];
		# OLD END FORM

		/*
	        | ----------------------------------------------------------------------
	        | Sub Module
	        | ----------------------------------------------------------------------
			| @label          = Label of action
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
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
	        | @color 	   = Default is primary. (primary, warning, succecss, info)
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        |
	        */
		$this->addaction = array();


		/*
	        | ----------------------------------------------------------------------
	        | Add More Button Selected
	        | ----------------------------------------------------------------------
	        | @label       = Label of action
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button
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
		$this->alert        = array();



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
            | IF NOT SUCCESS ADD  $this->col[] = ["label"=>"active","name"=>"active"]; IN COLUMNS
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
		$this->script_js = NULL;


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
		$this->style_css = "
			.file-example {
			display: flex !important;
			justify-content: space-between;
			max-width: 400px !important;
		 }";



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
		//Your code here

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
		$postdata['file_status'] = 'waiting';
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
	public function getAdd()
	{
		//Create an Auth
		if (!CRUDBooster::isCreate() && $this->global_privilege == FALSE || $this->button_add == FALSE) {
			CRUDBooster::redirect(CRUDBooster::adminPath(), trans("crudbooster.denied_access"));
		}

		$data = [];
		$data['page_menu'] = Route::getCurrentRoute()->getActionName();
		$data['page_title'] = trans('crudbooster.import_page_title', ['module' => "عمليات استيراد الملفات"]);
		if (view()->exists(CrudBooster::getCurrentModule()->path . '.import')) {
			return view(CrudBooster::getCurrentModule()->path . '.import', $data);
		} else {
			return view('crudbooster::import', $data);
		}
	}

	public function postAddSave()
	{
		$this->cbLoader();

		if (Request::hasFile('name')) {
			$file = Request::file('name');
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
		}


		if (!CRUDBooster::isCreate() && $this->global_privilege == false) {
			CRUDBooster::insertLog(trans('crudbooster.log_try_add_save', [
				'name' => Request::input($this->title_field),
				'module' => CRUDBooster::getCurrentModule()->name,
			]));
			CRUDBooster::redirect(CRUDBooster::adminPath(), trans("crudbooster.denied_access"));
		}

		$this->validation($request);
		$this->input_assignment($request);


		$reader = Excel::load(Request::file('name'))->first();
		if (is_null($reader))
			return redirect()->back()->with(['message' => 'لا يمكن ارفاق ملف فارغ !!', 'message_type' => 'warning']);
		$headerRow = $reader->keys()->toArray();
		$checkCurrectFileType = $this->checkTypeValidate($this->arr['type'], $headerRow);
		if (!$checkCurrectFileType)
			return redirect()->back()->with(['message' => 'الملف غير متطابق مع النوع !!', 'message_type' => 'warning']);
		//---------------------------
		$file = Request::file('name');
		$filePath = 'import-files/' . CB::myId() . '/' . date('Y-m');
		Storage::makeDirectory($filePath);
		$filename = md5(str_random(5)) . '.' . $ext;
		Storage::putFileAs($filePath, $file, $filename);
		$this->arr[$this->primary_key] = DB::table($this->table)->max($this->primary_key) + 1;
		$this->arr['file_status'] = trans('crudbooster.file_status.waiting');
		$this->arr['type'] = trans('crudbooster.file_type.' . $this->arr['type'] . '');
		$this->arr['name'] = $filename;
		$this->arr['path'] = $filePath . '/' . $filename;
		DB::table($this->table)->insert($this->arr);
		$id = $this->arr[$this->primary_key];

		CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_add_data_success"), 'success');
	}

	public function checkTypeValidate($type, $headerRow)
	{
		$checkMap = null;
		switch ($type) {
			case 'deals':
				$dealsMap = ['rkm_mhnds_aldras' => 0, 'asm_mhnds_aldras' => 0, 'rkm_almaaaml' => 0, 'tarykh_almaaaml' => 0, 'rkm_otarykh_almthkr' => 0, 'noaa_almaaaml' => 0, 'mntk_alhsr' => 0, 'almntk_alaakary' => 0, 'sahb_alaalak' => 0, 'rkm_mhnds_almaaaml' => 0, 'asm_mhnds_almaaaml' => 0, 'hal_almaaaml' => 0, 'kym_aldras' => 0, 'noaa_aldras' => 0, 'rkm_alaakar' => 0, 'mblgh_aledbar' => 0, 'mblgh_alekam_moejl_alsrf' => 0, 'almntk_altnthymy' => 0, 'shhr_aleghlak' => 0, 'aaam_aleghlak' => 0, 'aadd_altoabk' => 0, 'mjmoaa_alrkhs' => 0, 'almsah_alejmaly' => 0];
				$checkMap = $dealsMap;
				break;
			case 'paid_stays':
				$paidstaysMap = ['tarykh_alttbyk' => 0, 'tarykh_almthkr' => 0, 'rkm_almhnds' => 0, 'asm_almhnds' => 0, 'tarykh_almaaaml' => 0, 'rkm_almaaaml' => 0, 'almblgh' => 0, 'alshhr' => 0, 'alaaam' => 0, 'sahb_alaalak' => 0, 'mntk_alhsr' => 0, 'arkam_alaakarat' => 0, 'rkm_mhnds_almaaaml' => 0, 'asm_mhnds_almaaaml' => 0];
				$checkMap = $paidstaysMap;
				break;
			case 'monthly_ammounts':
				$monthlyMap = ['alshhr' => 0, 'alsn' => 0, 'asm_almhnds' => 0, 'rkm_almhnds' => 0, 'aaaml_alzmyl' => 0, 'alataaab' => 0, 'mkbod_tdkyk' => 0, 'alntham_almaly' => 0, 'alhs' => 0, 'alnsb' => 0, 'ashraf' => 0, 'rdyat_mkym' => 0, 'rdyat_edbar' => 0, 'mkbod_mshtrk' => 0, 'mlahthat' => 0, 'hsmyat' => 0, 'taaoydat' => 0, 'mord_llmshtrk' => 0, 'almkbod_alkly' => 0];
				$checkMap = $monthlyMap;
				break;
			default: // personal
				$personalMap = ['number' => 0, 'name' => 0, 'status' => 0, 'cota' => 0];
				$checkMap = $personalMap;
				break;
		}
		foreach ($checkMap as $key => $header) {
			if (!in_array($key, $headerRow)) {
				return 0;
			}
		}
		return 1;
	}
}
