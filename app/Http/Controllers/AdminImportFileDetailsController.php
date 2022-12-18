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
		$this->col[] = ["label" => "تاريخ الرفع", "name" => "created_at"];
		$this->col[] = ["label" => "تاريخ الاستيراد", "name" => "updated_at"];
		// $this->col[] = ["label"=>"active","name"=>"active"];
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = [];
		$this->form[] = ['label' => 'الملف', 'name' => 'path', 'type' => 'upload', 'validation' => 'required', 'width' => 'col-sm-9'];
		$this->form[] = ['label' => 'النوع', 'name' => 'type', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-9'];
		$this->form[] = ['label' => 'حالة الملف', 'name' => 'file_status', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-9'];
		$this->form[] = ['label' => 'عدد الأسطر الكلي', 'name' => 'total_file_records', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-9'];
		$this->form[] = ['label' => 'عدد الأسطر التي تم استيرادها بنجاح', 'name' => 'total_successfully', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-9'];
		$this->form[] = ['label' => 'عدد الأسطر الغير صالحة', 'name' => 'total_failed', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-9'];
		$this->form[] = ['label' => 'تاريخ الرفع', 'name' => 'created_at', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-9'];
		$this->form[] = ['label' => 'تاريخ الاستيراد', 'name' => 'updated_at', 'type' => 'text', 'validation' => 'required', 'width' => 'col-sm-9'];
		# END FORM DO NOT REMOVE THIS LINE

		# OLD START FORM
		//$this->form = [];
		//$this->form[] = ['label'=>'الملف','name'=>'path','type'=>'upload','validation'=>'required','width'=>'col-sm-9'];
		//$this->form[] = ['label'=>'النوع','name'=>'type','type'=>'text','validation'=>'required','width'=>'col-sm-9'];
		//$this->form[] = ['label'=>'حالة الملف','name'=>'file_status','type'=>'text','validation'=>'required','width'=>'col-sm-9'];
		//$this->form[] = ['label'=>'عدد الأسطر الكلي','name'=>'total_file_records','type'=>'text','validation'=>'required','width'=>'col-sm-9'];
		//$this->form[] = ['label'=>'عدد الأسطر التي تم استيرادها بنجاح','name'=>'total_successfully','type'=>'text','validation'=>'required','width'=>'col-sm-9'];
		//$this->form[] = ['label'=>'عدد الأسطر الغير صالحة','name'=>'total_failed','type'=>'text','validation'=>'required','width'=>'col-sm-9'];
		//$this->form[] = ['label'=>'تاريخ الرفع','name'=>'created_at','type'=>'datetime','validation'=>'required','width'=>'col-sm-9'];
		//$this->form[] = ['label'=>'تاريخ الاستيراد','name'=>'updated_at','type'=>'date','validation'=>'required','width'=>'col-sm-9'];
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


	public function hook_query_index(&$query)
	{
	}

	public function hook_row_index($column_index, &$column_value)
	{
	}

	public function hook_before_add(&$postdata)
	{
		$postdata['file_status'] = 'waiting';
	}

	public function hook_after_add($id)
	{
	}

	public function hook_before_edit(&$postdata, $id)
	{
	}


	public function hook_after_edit($id)
	{
	}


	public function hook_before_delete($id)
	{
	}

	public function hook_after_delete($id)
	{
	}



	//------ own functions

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

		$file = Request::file('name');
		$filePath = 'uploads/import-files/' . CB::myId() . '/' . date('Y-m');
		Storage::makeDirectory($filePath);
		$filename = md5(str_random(5)) . '.' . $ext;
		Storage::putFileAs($filePath, $file, $filename);
		$this->arr[$this->primary_key] = DB::table($this->table)->max($this->primary_key) + 1;
		$this->arr['file_status'] = trans('crudbooster.file_status.waiting');
		$this->arr['type'] = trans('crudbooster.file_type.' . $this->arr['type'] . '');
		$this->arr['name'] = $filename;
		$this->arr['created_at'] = Carbon::now();
		$this->arr['path'] = $filePath . '/' . $filename;
		DB::table($this->table)->insert($this->arr);
		$id = $this->arr[$this->primary_key];

		CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_add_data_success"), 'success');
	}
}
