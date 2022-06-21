<?php

namespace App\Http\Controllers;
use CRUDBooster;
use App\Account;
use App\Entry;
use App\EntryBase;
use App\Inventory;
use App\ItemCategory;
use App\ItemUnit;
use App\Item;
use App\ItemTracking;
use App\Person;
use App\Voucher;
use Session;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Redirect;

class ImportController extends Controller{

    //------------------------------- Import Data Function ---------------------------

    //upload excel file and save it in storage folder
    //return name of uploaded file
    public function uploadExcelDatafile(Request $request){
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
			$filePath = 'uploads/'.CRUDBooster::myId().'/'.date('Y-m');
			Storage::makeDirectory($filePath);
			
			//Move file to storage
			$filename = md5(str_random(5)).'.'.$ext;
			$url_filename = '';
			if (Storage::putFileAs($filePath, $file, $filename)) {
				$url_filename = $filePath.'/'.$filename;
			}
			//$url = CRUDBooster::mainpath('import-data').'?file='.base64_encode($url_filename);
			//return base64_encode($url_filename);
			//return redirect($url);
            return $url_filename; 
    }

    //read uploaded file and insert data to database
    public function importDataforModule($file){
        set_time_limit(1000000);
        $module= CRUDBooster::getCurrentModule();
        //dd($module);
        //D:\wamp64\www\accounting_system\storage\app/uploads/1/2022-03/c658e47c9fd0839b19411ca74ddd8cd2.xlsx
        $result = Excel::load(storage_path("app/".$file))->toObject();
        $result =collect($result);
        //dd($result);
        $module_path= $module->path;
        
        $reports = array();
        $import_status = "success";
        $errors_record_num = 0;
        if(count($result) > 0){
            array_push($reports,"عدد الكلي لعمليات الاستيراد هو ".count($result));
        }else{
            array_push($reports,"الملف لا يحوي بيانات !!!");
            $import_status = "failed";
        }
        
        if(count($result) > 0){ 

            if($module_path == 'inventories'){
                foreach ($result as $item) {
                        $inventory = new Inventory();
                        $inventory->name_en = $item["alasm_alenklyzy"];
                        $inventory->name_ar = $item["alasm_alaarby"];
                        $inventory->code = $item["alrmz"];

                        $delegate_account_id=Account::where('code',$item["rmz_hsab_sndok_almndob"])->first()->id;
                        $delegate_id=DB::table('cms_users')->where('account_id',$delegate_account_id)->first()->id;
                        $inventory->delegate_id = $delegate_id;

                        $inventory->major_classification = $item["hl_ho_reysy_naam_1_la_0"]?1:0;
            
                        if($item["rmz_almstodaa_alab"]){
                            $parent = Inventory::where('code',$item["rmz_almstodaa_alab"])->first()->id;
                            $inventory->parent_id = $parent;
                        }
                        $inventory->note = "";

                        $inventory->save();
                    }
            }else if($module_path == 'item_categories'){
                        foreach ($result as $index=>$item) {
                            if($item["alasm_alaarby"] != ""){
                                $item_cat = new ItemCategory();
                                $item_cat->name_en = $item["alasm_alenklyzy"];
                                $item_cat->name_ar = $item["alasm_alaarby"];
                                $item_cat->code = $item["alrmz"];
                                $item_cat->major_classification = 1;

                                if($item["rmz_altsnyf_alab"]){
                                    $parent = ItemCategory::where('code',$item["rmz_altsnyf_alab"])->first()->id;
                                    $item_cat->parent_id = $parent;
                                }
                            
                                $item_cat->save();
                            }else{
                                $record_num= $index+2;
                                array_push($reports,"السجل "."[$record_num]"." بعض الحقول لا تحوي قيم.");
                                $errors_record_num ++;
                            }
                        }
            }else if($module_path == 'items'){
                    foreach ($result as  $index=>$item) {
                        if($item["alasm_alaarby"] != ""){
                            $itemImport = new Item();
                            $itemImport->name_en = $item["alasm_alenklyzy"];
                            $itemImport->name_ar = $item["alasm_alaarby"];
                            $itemImport->code = $item["alrmz"];

                            $item_category = ItemCategory::where('code',$item["rmz_tsnyf_almoad"])->first()->id;
                            $itemImport->item_category_id = $item_category;

                            $item_unit = ItemUnit::where('name_ar',$item["alohd"])->first()->id;
                            if($item_unit){
                                $itemImport->item_unit_id = $item_unit;
                            }else{ //item unit don't found
                                $unit_id = DB::table('item_units')->insertGetId([
                                    'name_en' => "",
                                    'name_ar' => $item["alohd"],
                                    "active" => 1
                                ]);
                                $itemImport->item_unit_id = $unit_id;
                            }
                            

                            $itemImport->cost = $item["altklf"];
                            $itemImport->price = $item["alsaar"];
                            
                            $itemImport->timestamps = false; 
                        
                            $itemImport->save();
                        }else{
                            $record_num= $index+2;
                            array_push($reports,"السجل "."[$record_num]"." بعض الحقول لا تحوي قيم.");
                            $errors_record_num ++;
                        }

                    }
            
                }else if($module_path == 'item_tracking100'){
                    $code = 1;
                    foreach ($result as  $index=>$item) {
                        if($item["rmz_almad"] != ""){
                            $itemTrack = new ItemTracking();

                            $itemTrack->code = $code;
                            $itemTrack->p_code = "IB-". $code;
                            $code = $code +1 ;

                        
                            $item_name = Item::where('code',$item["rmz_almad"])->first()->id;
                            $itemTrack->item_id = $item_name;

                            $itemTrack->inventory_id_type_id = 5;

                            $inventory_name = Inventory::where('code',$item["rmz_almstodaa"])->first()->id;
                            $itemTrack->source = $inventory_name;

                            $itemTrack->quantity = $item["alaadd"];
                            $itemTrack->transaction_operation = "in";
                            $itemTrack->date = date('Y-m-d');
                            $itemTrack->timestamps = false; 
                        
                            $itemTrack->save();
                        }else{
                            $record_num= $index+2;
                            array_push($reports,"السجل "."[$record_num]"." بعض الحقول لا تحوي قيم.");
                            $errors_record_num ++;
                        }
                    }
                }else if($module_path == 'persons'){ //الزبائن
                    foreach ($result as  $index=>$item) {
                        if($item['rmz_hsab_zbaen_almndob']!=""){
                            $person = new Person();
                            $person->name_en = $item["alasm_alenklyzy"];
                            $person->name_ar = $item["alasm_alaarby"];
                            $parent_id = Account::where('code',$item['rmz_hsab_zbaen_almndob'])->first()->id;
                            if(!$parent_id){
                                $parent_id = 2; //id = 2 customers account id
                            }
                            //-----------
                            $code = Account::getCode($parent_id);
                            $person->code = $code;
                            $accountId = DB::table('accounts')->insertGetId([
                                'name_en' => $item['alasm_alenklyzy'],
                                'name_ar' => $item['alasm_alaarby'],
                                'code' => $code,
                                'parent_id' => $parent_id,
                                'major_classification' => 0,
                                "active" => 1
                            ]);
                            $person->timestamps = false; 
                            $person->account_id = $accountId;
                            $person->person_type_id = 1;
                        
                            $delegate_id=DB::table('cms_users')->where('customers_account_id',$parent_id)->first()->id;
                            if($delegate_id){
                                $person->delegate_id = $delegate_id;
                            }
                            
                            $person->save();
                        }else{
                            $record_num= $index+2;
                            array_push($reports,"السجل "."[$record_num]"." بعض الحقول لا تحوي قيم.");
                            $errors_record_num ++;
                        }
                    }
                
                }else if($module_path == 'initial_voucher'){ //الارصدة الافتتاحية
                    $code=1;
                    foreach ($result as  $index=>$item) {
                        if($item['rmz_alhsab']!=""){
                            $account_id = Account::where('code',$item["rmz_alhsab"])->first()->id;
                            if($account_id){
                                $voucher = new Voucher();

                                $voucher->code = $code;
                                $code = $code + 1;
                                $voucher->p_code = "IV-".$voucher->code;
                                
                                $voucher->debit = $account_id;
            
                                $voucher->voucher_type_id = 4;
            
            
                                $voucher->amount = ($item["alrsyd"]!=null)?$item["alrsyd"]:0;
                                $box_account_id = Account::where('code',$item["rmz_hsab_sndok_alaaml"])->first()->id;
                                $currency_id=0;
                                if($box_account_id){
                                    $currency_id = DB::table('currencies')->where('account_id',$box_account_id)->first()->id;
                                }
                                
                                $voucher->currency_id = $currency_id;
                                
                                $voucher->staff_id = 1;
                                $voucher->narration = "سند افتتاحي";
                                $voucher->date = date('Y-m-d');
                                $voucher->timestamps = false; 
                            
                                $voucher->save();

                                //add initial voucher to entry_base && entries table 
                                $max = DB::table('entry_base')->where('delete_by',0)->where('rotate_year',NULL)->max('entry_number');
                                $entry_number = $max + 1;

                                $entry_base_id = DB::table("entry_base")->insertGetId([
                                    'entry_number' => $entry_number,
                                    'narration' => $voucher->narration,
                                    'date' => $voucher->date,
                                    'voucher_id' => $voucher->id,
                                    'active' => 1,
                                    'create_by'=> 1
                                ]);

                                $entry = DB::table("entries")->insert([
                                    'entry_base_id' => $entry_base_id,
                                    'debit' => $voucher->amount,
                                    'account_id' => $voucher->debit,
                                    'credit' => null,
                                    'ex_rate' => null,
                                    'equalizer' => null,
                                    'opposite' => null,
                                    'currency_id' => $voucher->currency_id,
                                    'create_by'=> 1
                                ]);

                            }else{
                                $record_num= $index+2;
                                array_push($reports,"السجل"."[$record_num]"."يحوي رمز حساب خاطئ.");
                                $errors_record_num ++;
                            }
                        }else{
                            $record_num= $index+2;
                            array_push($reports,"السجل "."[$record_num]"." بعض الحقول لا تحوي قيم.");
                            $errors_record_num ++;
                        }
                        
                    }
                }   


            }//end main if

           if($errors_record_num > 0){
                array_push($reports,"عدد السجلات التي لم يتم استيرادها  هو ".$errors_record_num);    
           }
        
           
        return array(
                'import_status' => $import_status ,
                'reports' => $reports,
            );
    }

 
    public function resetDB(){
        ItemCategory::truncate();
        echo "Items Categories table is Empty now. done...<br/>";
        Inventory::truncate();
        echo "Inventories table is Empty now. done...<br/>";
        Item::truncate();
        echo "Items table is Empty now. done...<br/>";
        Voucher::truncate();
        echo "Vouchers table is Empty now. done...<br/>";
        ItemTracking::truncate();
        echo "Items Tracking table is Empty now. done...<br/>";
        Person::truncate();
        echo "Persons table is Empty now. done...<br/>";

        Entry::truncate();
        echo "entries table is Empty now. done...<br/>";

        EntryBase::truncate();
        echo "entry_base table is Empty now. done...<br/>";

        $BillsQuery = "TRUNCATE TABLE  bills;";
        DB::select(DB::raw($BillsQuery));
        echo "Bills table is Empty now. done...<br/>";

        $BillsQuery = "TRUNCATE TABLE  bills_files;";
        DB::select(DB::raw($BillsQuery));
        echo "bills_files table is Empty now. done...<br/>";

        $BillsQuery = "TRUNCATE TABLE  	bill_item;";
        DB::select(DB::raw($BillsQuery));
        echo "	bill_item table is Empty now. done...<br/>";

        //-----------------
        Account::truncate();
        echo "Accounts table is Empty now. done...<br/>";

        $accountsQuery = "
        INSERT INTO `accounts` (`id`, `name_en`, `name_ar`, `code`, `parent_id`, `major_classification`, `active`, `sorting`, `person_id`, `base`, `currency_id`, `closing_account_type`) VALUES
        (1, NULL, 'الأصول', 1, NULL, 1, 1, NULL, NULL, 0, NULL, NULL),
        (2, NULL, 'الزبائن', 101, 1, 1, 1, NULL, NULL, 0, NULL, 3),
        (3, NULL, 'حسابات النقدية', 102, 1, 1, 1, NULL, NULL, 0, NULL, 1),
        (4, NULL, 'صندوق ل.س', 1301, 3, 0, 1, NULL, NULL, 0, NULL, NULL),
        (5, NULL, 'حسابات نقدية المهندسين', 103, 1, 1, 1, NULL, NULL, 0, NULL, NULL),
        (6, NULL, 'المصروفات', 3, NULL, 1, 1, NULL, NULL, 0, NULL, 3),
        (7, NULL, 'مصروف سيارة المهندس', 301, 6, 0, 1, NULL, NULL, 0, NULL, NULL),
        (8, NULL, 'اجور شحن ومصروف مبيعات', 302, 6, 0, 1, NULL, NULL, 0, NULL, NULL),
        (9, NULL, 'مصاريف مختلفة ', 303, 6, 0, 1, NULL, NULL, 0, NULL, NULL),
        (10, NULL, 'حسابات المواد', 4, NULL, 1, 1, NULL, NULL, 0, NULL, NULL),
        (11, NULL, 'مشتريات', 401, 10, 0, 1, NULL, NULL, 0, NULL, NULL),
        (12, NULL, 'مردود مشتريات', 402, 10, 0, 1, NULL, NULL, 0, NULL, NULL),
        (13, NULL, 'مبيعات', 403, 10, 0, 1, NULL, NULL, 0, NULL, NULL),
        (14, NULL, 'مردود مبيعات', 404, 10, 0, 1, NULL, NULL, 0, NULL, NULL),
        (15, NULL, 'الحسم الممنوح', 405, 10, 0, 1, NULL, NULL, 0, NULL, NULL),
        (16, NULL, 'الحسم المكتسب', 406, 10, 0, 1, NULL, NULL, 0, NULL, NULL),
        (17, NULL, 'الإيرادات', 5, NULL, 1, 1, NULL, NULL, 0, NULL, NULL),
        (18, 'Supplers', 'الموردون', 104, 1, 1, 1, NULL, NULL, 0, NULL, NULL);
         ";
        //DB::raw($accountsQuery);
        DB::select(DB::raw($accountsQuery));
        echo "Insert main Accounts data to accounts table . done...<br/>";
        
        $currenciesQuery = "TRUNCATE TABLE  currencies;";
        DB::select(DB::raw($currenciesQuery));
        echo "Currencies table is Empty now. done...<br/>";

        $currenciesQuery = "TRUNCATE TABLE  currency_history;";
        DB::select(DB::raw($currenciesQuery));
        echo "currency_history table is Empty now. done...<br/>";


        $currenciesQuery = "
        INSERT INTO `currencies` (`id`, `name_en`, `name_ar`, `account_id`, `in_used`, `is_major`, `note`, `ex_rate`, `icon`, `color`, `active`, `sorting`) VALUES
        (1, 'S.P', 'ل.س', 4, 1, 1, 'العملة الاساسية', '1.00', 'fa fa-money', 'red', 1, 1);
         ";
        DB::select(DB::raw($currenciesQuery));
        echo "Insert main Currency data to currencies table . done...<br/>";

        DB::table('statistics_setting')->where('id',2)->update([
            "value"=>'4'
        ]);
        echo "change statistics setting done...<br/>";

        $cms_usersQuery = "TRUNCATE TABLE  cms_users;";
        DB::select(DB::raw($cms_usersQuery));
        echo "CMS Users table is Empty now. done...<br/>";

        $super_admin_pass='$2y$10$MyiovAMt9R8jmFl4vUUCVO2kw6.q76HWLQJvuUZ.zlZIJCzRpwvDy'; //pass1234
        $cms_usersQuery="
        INSERT INTO `cms_users` (`id`, `name`, `photo`, `email`, `password`, `id_cms_privileges`, `created_at`, `updated_at`, `status`, `inventory_id`, `account_id`, `customers_account_id`) VALUES
        (1, 'Super Admin', '/images/portfolio1_logo.png', 'superadmin@voila.digital', '$super_admin_pass', 1, '2019-05-30 06:06:58', '2020-07-21 12:55:23', 'Active', NULL, 0, 0);
        ";

        DB::select(DB::raw($cms_usersQuery));
        echo "Insert main Cms Users data to cms_users table . done...<br/>";

        /*$package_configQuery = "TRUNCATE TABLE  package_config;";
        DB::select(DB::raw($package_configQuery));
        echo "package_config table is Empty now. done...<br/>";
        */
        echo "<br>-------------------------------------------------<br/>";
        echo "DATABASE is Ready to import Data";
        echo "<br>-------------------------------------------------<br/>";

        return "";

    }
}

