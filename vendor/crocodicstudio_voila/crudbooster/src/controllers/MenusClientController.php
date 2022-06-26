<?php namespace crocodicstudio_voila\crudbooster\controllers;

use CRUDBooster;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

class MenusClientController extends CBController
{
    public function cbInit()
    {
        $this->table = "cms_menus";
        $this->primary_key = "id";
        $this->title_field = "name";
        $this->limit = 20;
        $this->orderby = ["id" => "desc"];

        $this->button_table_action = true;
        $this->button_action_style = "FALSE";
        $this->button_add = false;
        $this->button_delete = true;
        $this->button_edit = true;
        $this->button_detail = true;
        $this->button_show = false;
        $this->button_filter = true;
        $this->button_export = false;
        $this->button_import = false;
        $this->selected=null;
        $id = CRUDBooster::getCurrentId();
        if (Request::segment(3) == 'edit') {
            $id = Request::segment(4);
            Session::put('current_row_id', $id);
        }
        $row = CRUDBooster::first($this->table, $id);
        $row = (Request::segment(3) == 'edit') ? $row : null;

        $id_module = $id_statistic = 0;

        if ($row->type == 'Module') {
            $arr = explode('/', $row->path);

            $m = CRUDBooster::first('cms_moduls', ['table_name' => $arr[0]]);
            // dd($m);
            $id_module = $m->id;
            $list=DB::table($arr[0])->get();
            $items = $arr[1];
            // dd( $this->selected);
            //fesal

        } elseif ($row->type == 'Statistic') {
            $row->path = str_replace('statistic_builder/show/', '', $row->path);
            $m = CRUDBooster::first('cms_statistics', ['slug' => $row->path]);
            $id_statistic = $m->id;
        }

        $this->script_js = "
			$(function() {
				var current_id = '$id';
                var current_type = '$row->type';

                //ajax
                var val =$('#module_slug').val();

                $.ajax({
                    url: '/admin/getModulesItem/'+val,
                    type:'GET',
                    success: function(data){
                        $('#items').empty();
                        var selected='$items';
                        selected=selected==''?-1:selected;
                        $('#items').append('<option ('+selected+'==-1)? selected :  value=>All</option>');

                        
                        data.data.forEach(function(e){
                            var sel=(selected==e.id)? 'selected' : '';
                            debugger;
                            $('#items').append('<option value='+e.id+' '+sel+' >'+e[data.main_field]+'</option>');
                        })
                    },
                    dataType: 'json'
                  });


				var type_menu = $('input[name=type]').val();
				type_menu = (current_type)?current_type:type_menu;
				if(type_menu == 'Module') {
					$('#form-group-module_slug').show();
					$('#form-group-statistic_slug,#form-group-path').hide();
					$('#module_slug').prop('required',true);
					$('#form-group-module_slug label').append('<span class=\"text-danger\" title=\"" . trans('crudbooster.this_field_is_required') . "\">*</span>');
				}else if(type_menu == 'Statistic') {
					$('#form-group-statistic_slug').show();
					$('#module_slug').prop('required',false);
					$('#form-group-module_slug,#form-group-path').hide();
					$('#statistic_slug').prop('required',true);
					$('#form-group-statistic_slug label').append('<span class=\"text-danger\" title=\"" . trans('crudbooster.this_field_is_required') . "\">*</span>');
				}else{
					$('#module_slug').prop('required',false);
					$('#form-group-module_slug,#form-group-statistic_slug').hide();
					$('#form-group-path').show();
				}


				function format(icon) {
	                  var originalOption = icon.element;
	                  var label = $(originalOption).text();
	                  var val = $(originalOption).val();
	                  if(!val) return label;
	                  var \$resp = $('<span><i style=\"margin-top:5px\" class=\"pull-right ' + $(originalOption).val() + '\"></i> ' + $(originalOption).data('label') + '</span>');
	                  return \$resp;
	              }


				$('input[name=type]').click(function() {
					var default_placeholder_path = 'NameController@methodName';
					var n = $(this).val();
					var isCheck = $(this).prop('checked');
					console.log('Click the module type '+n);
					$('#module_slug').prop('required',false);
					$('input[name=path]').attr('placeholder',default_placeholder_path);
					if(n == 'Module') {
						$('#form-group-path').hide();
						$('#form-group-statistic_slug').hide();
						$('#statistic_slug,#path').prop('required',false);

                        $('#form-group-module_slug').show();
                        $('#form-group-items').show();


						$('#module_slug').prop('required',true);
						$('#form-group-module_slug label .text-danger').remove();
					}else if (n == 'Statistic') {
						$('#form-group-path').hide();
						$('#form-group-module_slug').hide();
						$('#module_slug,#path').prop('required',false);

						$('#form-group-statistic_slug').show();
						$('#statistic_slug').prop('required',true);
						$('#form-group-statistic_slug label .text-danger').remove();
						$('#form-group-statistic_slug label').append('<span class=\"text-danger\" title=\"" . trans('crudbooster.this_field_is_required') . "\">*</span>');
					}else if (n == 'URL') {
						$('input[name=path]').attr('placeholder','Please enter your URL');

						$('#path').prop('required',true);
						$('#form-group-path label .text-danger').remove();
						$('#form-group-path label').append('<span class=\"text-danger\" title=\"" . trans('crudbooster.this_field_is_required') . "\">*</span>');

						$('#form-group-path').show();
						$('#form-group-module_slug,#form-group-statistic_slug,#form-group-items').hide();
					}else if (n == 'Route') {
						$('input[name=path]').attr('placeholder','Please enter the Route');

						$('#path').prop('required',true);
						$('#form-group-path label .text-danger').remove();
						$('#form-group-path label').append('<span class=\"text-danger\" title=\"" . trans('crudbooster.this_field_is_required') . "\">*</span>');

						$('#form-group-path').show();
						$('#form-group-module_slug,#form-group-statistic_slug').hide();
					}else {
						$('#module_slug,#statistic_slug').prop('required',false);

						$('#path').prop('required',true);
						$('#form-group-path label .text-danger').remove();
						$('#form-group-path label').append('<span class=\"text-danger\" title=\"" . trans('crudbooster.this_field_is_required') . "\">*</span>');

						$('#form-group-path').show();
						$('#form-group-module_slug,#form-group-statistic_slug').hide();
					}
                })

                $('#module_slug').change(function(){


                    //ajax
                    var val =$('#module_slug').val();
                    $.ajax({
                        url: '/admin/getModulesItem/'+val,
                        type:'GET',
                        success: function(data){
                            debugger
                            
                            $('#items').empty();
                           $('#items').append('<option value=>All</option>');
                            data.data.forEach(function(e){
                                    $('#items').append('<option value='+e.id+'>'+e[data.main_field]+'</option>');

                            })
                        },
                        dataType: 'json'
                      });

                    $('#form-group-items').css('display','block');
                });
			})
			";

        $this->col = [];
        $this->col[] = ["label" => "Name", "name" => "name"];
        $this->col[] = ["label" => "Is Active", "name" => "is_active"];
        $this->col[] = ["label" => "Privileges", "name" => "id_cms_privileges", "join" => "cms_privileges,name"];

        $this->form = [];

        $this->form[] = [
            "label" => "Name",
            "name" => "name",
            "type" => "text",
            "required" => true,
            "validation" => "required|min:3|max:255",
        ];

        // $this->form[] = [
        //     "label" => "Name ar",
        //     "name" => "name_ar",
        //     "type" => "text",
        // ];
        $this->form[] = [
            "label" => "Type",
            "name" => "type",
            "type" => "radio",
            "required" => true,
            'dataenum' => ['Module', 'URL'],
            'value' => 'Module',
        ];

    
        $this->form[] = [
            "label" => "Module",
            "name" => "module_slug",
            "type" => "select",
            "datatable" => "cms_moduls,name",
            "datatable_where" => "has_page_client = 1",
            "value" => $id_module,
        ];

        $this->form[] = [
            "label" => "items",
            "name" => "items",
            "type" => "select",
            "value" => $items,

        ];

        $this->form[] = [
            "label" => "Statistic",
            "name" => "statistic_slug",
            "type" => "select",
            "datatable" => "cms_statistics,name",
            "style" => "display:none",
            "value" => $id_statistic,
        ];


        

        $this->form[] = [
            "label" => "Value",
            "name" => "path",
            "type" => "text",
            'help' => 'If you select type controller, you can fill this field with controller name, you may include the method also',
            "style" => "display:none",
        ];

        $this->form[] = [
            "label" => "Active",
            "name" => "is_active",
            "type" => "radio",
            "required" => true,
            "validation" => "required|integer",
            "dataenum" => ['1|Active', '0|InActive'],
            'value' => '1',
        ];

        $this->form[] = [
            "label" => "Front client",
            "name" => "is_front",
            "type" => "radio",
            "required" => false,
            "dataenum" => ['1|Yes'],
            'value' => '1',
             "style" => "display:none",

        ];

    }

    public function getIndex()
    {
        $this->cbLoader();
        $lang=1;
        if (Input::has('lang')) {
            //
            $lang=Input::get('lang');
        }

        // $module = CRUDBooster::getCurrentModule();
        // if (! CRUDBooster::isView() && $this->global_privilege == false) {
        //     CRUDBooster::insertLog(trans('crudbooster.log_try_view', ['module' => $module->name]));
        //     CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
        // }

        $privileges = DB::table('cms_privileges')->get();

        $id_cms_privileges = Request::get('id_cms_privileges');
        $id_cms_privileges = ($id_cms_privileges) ?: CRUDBooster::myPrivilegeId();

        $menu_active = DB::table('cms_menus')->where('parent_id', 0)->where('lang_id',$lang)->where('is_active', 1)->where('is_front', 1)->orderby('sorting', 'asc')->get();

        foreach ($menu_active as &$menu) {
            $child = DB::table('cms_menus')->where('is_active', 1)->where('lang_id',$lang)->where('parent_id', $menu->id)->where('is_front', 1)->orderby('sorting', 'asc')->get();
            if (count($child)) {
                $menu->children = $child;
            }
        }

        $menu_inactive = DB::table('cms_menus')->where('parent_id', 0)->where('is_active', 0)->where('is_front', 1)->orderby('sorting', 'asc')->get();

        foreach ($menu_inactive as &$menu) {
            $child = DB::table('cms_menus')->where('is_active', 1)->where('parent_id', $menu->id)->where('is_front', 1)->orderby('sorting', 'asc')->get();
            if (count($child)) {
                $menu->children = $child;
            }
        }

        $return_url = Request::fullUrl();

        $page_title = 'Menu Management Front Page';
        $languages=DB::table("languages")->where('active',1)->get();
        return view('crudbooster::menus_managementClient', compact('languages','menu_active', 'menu_inactive', 'privileges', 'id_cms_privileges', 'return_url', 'page_title'));
    }

    public function hook_before_add(&$postdata)
    {


        $postdata["lang_id"]=(Input::get('lang_id'))?:1;
        if (!$postdata['id_cms_privileges']) {
            $postdata['id_cms_privileges'] = CRUDBooster::myPrivilegeId();
        }
        $postdata['parent_id'] = 0;
        if ($postdata['type'] == 'Statistic') {
            $stat = CRUDBooster::first('cms_statistics', ['id' => $postdata['statistic_slug']]);
            $postdata['path'] = 'statistic_builder/show/' . $stat->slug;
        } elseif ($postdata['type'] == 'Module') {
            $stat = CRUDBooster::first('cms_moduls', ['id' => $postdata['module_slug']]);
            $postdata['path'] = $stat->table_name . '/' . $postdata["items"];
        }

        unset($postdata['module_slug']);
        unset($postdata['statistic_slug']);
        unset($postdata['items']);

        if ($postdata['is_dashboard'] == 1) {
            //If set dashboard, so unset for first all dashboard
            DB::table('cms_menus')->where('id_cms_privileges', $postdata['id_cms_privileges'])->where('is_dashboard', 1)->update(['is_dashboard' => 0]);
            Cache::forget('sidebarDashboard' . CRUDBooster::myPrivilegeId());
        }
    }

    public function hook_before_edit(&$postdata, $id)
    {

        $postdata["lang_id"]=(Input::get('lang_id'))?:1;

        if ($postdata['is_dashboard'] == 1) {
            //If set dashboard, so unset for first all dashboard
            DB::table('cms_menus')->where('id_cms_privileges', $postdata['id_cms_privileges'])->where('is_dashboard', 1)->update(['is_dashboard' => 0]);
            Cache::forget('sidebarDashboard' . CRUDBooster::myPrivilegeId());
        }

        if ($postdata['type'] == 'Statistic') {
            $stat = CRUDBooster::first('cms_statistics', ['id' => $postdata['statistic_slug']]);
            $postdata['path'] = 'statistic_builder/show/' . $stat->slug;
        } elseif ($postdata['type'] == 'Module') {
            $stat = CRUDBooster::first('cms_moduls', ['id' => $postdata['module_slug']]);
            // $arr = explode(',', $stat->path);
            $postdata['path'] = $stat->table_name . '/' . $postdata["items"];

        }

        unset($postdata['module_slug']);
        unset($postdata['statistic_slug']);
        unset($postdata['items']);
    }

    public function hook_after_delete($id)
    {
        DB::table('cms_menus')->where('parent_id', $id)->delete();
    }

    public function postSaveMenu()
    {
        $post = Request::input('menus');
        $isActive = Request::input('isActive');
        $post = json_decode($post, true);

        $i = 1;
        foreach ($post[0] as $ro) {
            $pid = $ro['id'];
            if ($ro['children'][0]) {
                $ci = 1;
                foreach ($ro['children'][0] as $c) {
                    $id = $c['id'];
                    DB::table('cms_menus')->where('id', $id)->update(['sorting' => $ci, 'parent_id' => $pid, 'is_active' => $isActive]);
                    $ci++;
                }
            }
            DB::table('cms_menus')->where('id', $pid)->update(['sorting' => $i, 'parent_id' => 0, 'is_active' => $isActive]);
            $i++;
        }

        return response()->json(['success' => true]);
    }

    public function getModulesItem($val)
    {
        $module = DB::table('cms_moduls')->find($val);

        if ($module) {

            $items = DB::table($module->table_name)->get();
            return response()->json(["data" => $items,"main_field"=>$module->main_field], 200);
        }

        return response()->json(["data" => false], 200);

    }
}