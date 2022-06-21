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
              <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">الأرصدة الإفتتاحية للسنة المالية الجديدة</a></li>
              <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">بضاعة أول المدة للسنة المالية الجديدة</a></li>
              <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">أرباح وخسائر</a></li>
              
              <li class="pull-left">
                  @php $site = config('setting._SITE') @endphp
                  <button  class="btn btn-warning" onclick="location.href = '{{$site}}modules/reports_rotate_data'">  تراجع <i class="fa fa-chevron-circle-right"></i></button>
                  <button id="rotate_data" class="btn btn-primary" > تدوير الحسابات <i class="fa fa-gear "></i></button>
                </li>
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
                            <div class="tableFixHead">
                            @if($data != null)
                                <table id="tableId" class="table table-bordered table-striped dataTable text-center" role="grid" aria-describedby="tableId_info">
                                    <thead>
                                    <tr role="row">
                                        <th style="text-align:right" > اسم الحساب</th>
                                        @foreach($activeCurrencies as $curr)
                                        <th style="text-align:center" > رصيد  {{$curr->name_ar}}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $item)
                                        <tr>
                                            <td style="text-align:right">{{$item['account_name']}}</td>
                                            @foreach($activeCurrencies as $curr)
                                                <td>{{number_format($item['curr_balance_'.$curr->id],2)}}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                       
                                   
                                    </tbody>
                                    <tfoot>
                                        @if(count($data) > 0)   
                                            <tr id="trTotal">
                                                <td style="text-align:right" bgcolor="#00ff7f" >
                                                    اجمالي الرصيد
                                                </td>
                                                @foreach($activeCurrencies as $curr)
                                                <td bgcolor="#7fff00">{{number_format($final_balances['final_balance_'.$curr->id],2)}}</td>
                                                @endforeach
                                            </tr>
                                            @else
                                            <tr id="trTotal">
                                                <td colspan='{{count($activeCurrencies)}}' > لا توجد  نتائج</td>
                                            </tr>
                                        @endif
                                    </tfoot>
                                </table>
                                @endif
                            </div>
                        </div>
                    </div>
            
                    </div>
                </div>
                            <!-- /.box-body -->
            </div>
                
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <!-- start Table -->
                    <div class="box">
                        <!--div class="box-header">
                            <h3 class="box-title"></h3>
                        </div-->
                        <!-- /.box-header -->
                    <div class="box-body">
                        <div id="form-table" class="dataTables_wrapper form-inline dt-bootstrap">
                                    
                            <div class="row">
                                <div class="col-sm-12">
                                <div class="tableFixHead">
                                    <table id="tableId2" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="tableId2_info">
                                        <thead>
                                            <tr role="row">
                                                <th >المادة</th>
                                                <th >المستودع</th>
                                                <th >العدد</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if($inventories_data != null)
                                            @foreach($inventories_data as $inv_data)
                                                <tr>
                                                    <td>{{$inv_data->nameAr}}</td>
                                                    <td>{{$inv_data->sourceInventory}}</td>
                                                    <td>{{($inv_data->item_in - ($inv_data->item_out == null?0:$inv_data->item_out))}}</td>
                                                    
                                                </tr>
                                            @endforeach
                                                @if(count($inventories_data) < 0)   
                                                    <tr id="trTotal">
                                                        <td colspan='3' > لا توجد  نتائج</td>
                                                    </tr>
                                                @endif
                                        @endif
                                        </tbody>
                                        <tfoot>
                                            
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                
                        </div>
                    </div>
                                <!-- /.box-body -->
                </div>
                <!-- end Table -->
                
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
                    الأرباح والخسائر :
                 
                 
             <table class='table table-bordered table-striped dataTable'>
             <thead>
             <tr>
             <th>#</th>
             <th>مدين</th>
             <th>دائن</th>
             <th>الناتج</th>
             </tr>
             </thead>
             <tbody>
             <tr>
             <td>مجمل الربح</td>
             <td>{{number_format($profits_and_loss->gross_profit_debit,2)}}</td>
             <td>{{number_format($profits_and_loss->gross_profit_credit,2)}}</td>
             <td>{{number_format($profits_and_loss->gross_profit,2)}}</td>
             </tr>
             <tr>
             <td>صافي الربح</td>
             <td>{{number_format($profits_and_loss->net_profit_debit,2)}}</td>
             <td>{{number_format($profits_and_loss->net_profit_credit,2)}}</td>
             <td>{{number_format($profits_and_loss->net_profit,2)}}</td>
             </tr>
             </tbody>
             </table>
        
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>


</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <script type="text/javascript">
       
        
        $('#rotate_data').click(function(){
            
        swal({
                title: 'هل أنت متأكد؟',
                text: "خلال تدوير الحسابات للسنة مالية جديدة ستفقد إمكانية تعديل على  الفواتير والسندات السابقة، قم بالضغط على متابعة للقيام بعملية التدوير",
                type:'info',
                showCancelButton:true,
                allowOutsideClick:true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'متابعة',
                cancelButtonText: 'إلغاء',
                closeOnConfirm: false
                }, function(){
                    $('#rotate_data i').addClass('fa-spin');
                    $('#rotate_data').addClass('disabled');
                    $('.waiting-msg').removeClass('hidden');
                    swal.close();
                    $.get('/rotate_data',function(res){
                        let json_res =JSON.parse(res);
                        console.log(res);                      
                      $('#rotate_data i').removeClass('fa-spin');
                      $('.waiting-msg').addClass('hidden');
                      $('#rotate_data').removeClass('disabled');
                      if(json_res.status == 'error'){
                            alert(json_res.massege);
                      }else{
                         location.href = "{{config('setting._SITE')}}modules";
                      }
                      
		            })
                
            });

            
            
        });
        
    </script>
@endsection
