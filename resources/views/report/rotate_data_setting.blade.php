@extends('crudbooster::admin_template')
@section('content')

<br/>
<br/>
<br/>
<br/>
<div class="row">
<div class="col-md-12">
    <div class="callout callout-info waiting-msg hidden">
                    <h4>     رجاءاً ، انتظر</h4>
                    <p>العملية تأخذ بعض الوقت . <i class="fa fa-refresh fa-spin"></i></p>
                    
    </div>
</div>
<div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">أعدادات تدوير الحسابات</a></li>

            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
               
            <div class="box">
                    <!--div class="box-header">
                        <h3 class="box-title"></h3>
                    </div-->
                    <!-- /.box-header -->
                <div class="box-body">
                    <div id="form-table" class="dataTables_wrapper form-inline dt-bootstrap">
                                 
                        <div class="row">
                            <div class="col-sm-12">
                                <form id="rotate_data_setting" method="post" action="{{url('/continue_rotate_data')}}" class="print_display_none">
                                   {{ csrf_field() }}
                                  <div class="col-lg-12">
                                      <table class="table ">
                                        
                                        @foreach($currencies_not_major as $curr)
                                        <tr>
                                          <td>سعر الصرف  {{$curr->name_ar}} الحالي :</td>
                                          <td>
                                            <input type="text" name="ex_rate_{{$curr->id}}" required value="{{$curr->ex_rate}}" style="width:160px;"/>
                                          </td>
                                        </tr>
                                        @endforeach
                                        
                                        <tr>
                                          <td>تاريخ  الإقفال : </td>
                                          <td><input type="date" name="rotate_date" id="rotate_date" class="form-control" value="{{$rotate_date}}" style="width:160px; text-align:right;"></td>
                                        </tr>
                                        <tr>
                                          <td> تكلفة المادة:</td>
                                          <td>
                                            <input type="radio" name="item_cost_type" value="1" checked /> وسطي التكلفة </br>
                                            <input type="radio" name="item_cost_type" value="0"  />  أخر شراء 
                                          </td>
                                        </tr>
                                        
                                        <tr>
                                          <td width="30%"> حساب فرق سعر الصرف :</td>
                                          <td> 
                                            <select class="form-control" name="account_id" id="select-account" required='true' >
                                              <!--option > اختر حساب</option-->
                                              @foreach($accounts as $item)
                                                  <option value="{{$item->id}}" >{{$item->name_ar}}</option>
                                              @endforeach
                                            </select></td>
                                        </tr>
                                        <tr>
                                          <td></td>
                                          <td>
                                          <button id="continue" class="btn btn-primary" type="submit" > متابعة <i class="fa fa-chevron-left "></i></button>
                                          </td>
                                        </tr>
                                      </table>
                                       
                                    </div>
                                </form>
                            </div>
                        </div>
            
                    </div>
                </div>
                            <!-- /.box-body -->
            </div>
                
              </div>

            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>


</div>



       <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

    <script type="text/javascript">
       
       $('#select-account').select2();

       $('form').submit(function () {
            let elem = $('#continue i');
            elem.removeClass('fa-chevron-left');
            elem.addClass('fa-spinner fa-spin');
        });

      
        
    </script>
@endsection
