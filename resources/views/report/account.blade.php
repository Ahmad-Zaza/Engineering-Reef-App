@extends('crudbooster::admin_template')
@section('content')
@php
$column_num = count($active_currencies);
if(count($active_currencies) > 6){
    $column_num = 3;
}
@endphp
<form method="get" action="{{url('modules/reports99')}}" class="print_display_none">
<div class="col-lg-12">
<div class="col-lg-4">
الحساب :
    <select class="form-control" name="account_id" id="select-account">
    <option value="-1">أختر الحساب</option>
    @foreach($accounts as $item)
        <option value="{{$item->id}}" {{$account_id==$item->id?'selected':''}} >{{$item->name_ar}}</option>
    @endforeach
    </select>
</div>

    <div class="col-lg-4">
    المهندس :
        <select class="form-control" name="delegate_id" id="select-delegate">

            <option value="-1">أختر المهندس</option>
            @foreach($delegates as $item)
                <option value="{{$item->id}}" {{$delegate_id==$item->id?'selected':''}} >{{$item->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-4">{{trans('crudbooster.From')}} : {{$from_date}}

        <input type="date" name="from_date" id="from_date" class="form-control" value="{{$from_date}}">
    </div>

    <div class="col-lg-4">{{trans('crudbooster.To')}} : {{$to_date}}

        <input type="date" name="to_date" id="to_date" class="form-control" value="{{$to_date}}">
    </div>
    <div class="col-lg-4">
    العملة :
        <select class="form-control" name="currency_id" id="select-currency">
            <option value="-1"> جميع العملات</option>
            @foreach($currencies as $item)
                <option value="{{$item->id}}" {{$currency_id==$item->id?'selected':''}}>{{$item->name_ar}}</option>
            @endforeach
        </select>
    </div>


<div class="col-lg-4">
    <br>
    <button type="submit" class="btn btn-primary" id="search"> {{trans('crudbooster.Search')}} <i class="fa fa-search"></i></button>
    <a  name='reset' class="btn btn-warning" href="{{url('/modules/reports99')}}" > تهيئة <i class="fa fa-refresh"></i></a>
    <button id="PrintReport" class="btn btn-success" onclick="window.print();" > طباعة <i class="fa fa-print"></i></button>
    <a  id="export" name='export' class="btn btn-info" href="javascript:void(0)" > تصدير <i class="fa fa-file-excel-o"></i></a>
</div>
</div>
</form>
<hr>
<div class="print_display_none">
<br>
<br>
<br>
<br>
</div>
@if($type_display == 1)
<!---- start Display type 1 ---->
<!----  عرض خاص بالحسابات الحقيقية  ---->
@if($currency_id != -1)
<div class="tableFixHead">
<table id="tableId" class="table table-hover table-striped table-bordered tableId">
    <caption> <b> <center> تفاصيل الحساب</center> </b></caption>
    <thead>
        <tr class="active">
            <th width="auto"><a href="" >الأسم </a></th>
            <th width="auto"><a href="" >مدين </a></th>
            <th width="auto"><a href="" >دائن </a></th>
            <th width="auto"><a href="" >التاريخ</a></th>
            <th width="auto"><a href="" >البيان </a></th>
            <th width="auto"><a href="" >العملة </a></th>
            <th width="auto"><a href="" >الرصيد</a></th>
            <th width="auto"><a href="" >المصدر</a></th>
        </tr>
    </thead>
    <tbody class="ui-sortable">
    @php 
    $prevBalance = 0;
    @endphp    
    @foreach($data as $item)
        @php
        $paid = $item->paid_amount;
        $received = $item->received_amount;
        $prevBalance = $prevBalance + ($received - $paid);
        @endphp
    <tr>

        <td>
            {{$item->name}}
        </td>

        <td>
        {{($received)?number_format($received,2):0}}      

        </td>
        <td>
            {{($paid)?number_format($paid,2):0}}      
        </td>
       
        <td>
            {{$item->date}}      
        </td>
        <td>
            {{$item->narration}}      
        </td>
        <td>
            {{$item->currency}}
        </td>
        <td >
            {{number_format($prevBalance,2)}}
        </td>
        <td>   
            @php
            $temp_id='';
            $temp_type='';
            $is_bill = '';
            if($item->billId !=null){
                $temp_id=$item->billId;
                $temp_type=$item->billTypeId;
                $is_bill = 'yes';
            }else{
                $temp_id=$item->voucherId;
                $temp_type=$item->voucherTypeId;
                $is_bill = 'no';
            }
            @endphp  
          <button type="button" class="btn btn-light-blue btn-md btn-edit print_display_none" id="edit" data-entry_base_id="{{$item->entryBaseId}}" data-is_bill="{{$is_bill}}" data-id="{{$temp_id}}" data-type="{{$temp_type}}">الذهاب إلى المصدر</button>
        </td>
    </tr>

    @endforeach


        
    </tbody>
</table>
</div>
@else
<div class="tableFixHead">
<table id="tableId2" class="table table-hover table-striped table-bordered tableId">
    <caption> <b> <center> تفاصيل الحساب</center> </b></caption>
    <thead>
        <tr class="active">
            <th width="auto"><a href="" >الأسم </a></th>
            <th width="auto"><a href="" >مدين </a></th>
            <th width="auto"><a href="" >دائن </a></th>
            <th width="auto"><a href="" >التاريخ</a></th>
            <th width="auto"><a href="" >البيان </a></th>
            <th width="auto"><a href="" >العملة </a></th>
            @foreach($active_currencies as $curr)
                <th width="auto"><a href="" > الرصيد ({{$curr->name_ar}}) </a></th>
            @endforeach
            <th width="auto"><a href="" > المصدر</a></th>
        </tr>
    </thead>
    
    <tbody class="ui-sortable">
    @php
        $final_balance = array();
        foreach($active_currencies as $curr){
            $final_balance['curr_balance_'.$curr->id] = 0;
        }
    @endphp    
   
    @foreach($data as $item)
        @php
            $paid = $item->paid_amount;
            $received = $item->received_amount;
            $final_balance['curr_balance_'.$item->currency_id] = $final_balance['curr_balance_'.$item->currency_id] + ($received - $paid) ;
            
        @endphp
    <tr data-currency="{{$item->currency_id}}">
    

        <td>
            {{$item->name}}
        </td>

        <td>
        {{($item->received_amount)?number_format($item->received_amount,2):0}}      

        </td>

        <td>
            {{($item->paid_amount)?number_format($item->paid_amount,2):0}}      
        </td>

        <td>
            {{$item->date}}      
        </td>
        <td>
            {{$item->narration}}      
        </td>
        <td>
            {{$item->currency}}
        </td>
        @foreach($active_currencies as $curr)
        <td >
            {{number_format($final_balance['curr_balance_'.$curr->id],2)}}
        </td>
        @endforeach
        
        <td>
        @php
            $temp_id='';
            $temp_type='';
            $is_bill = '';
            if($item->billId !=null){
                $temp_id=$item->billId;
                $temp_type=$item->billTypeId;
                $is_bill = 'yes';
            }else{
                $temp_id=$item->voucherId;
                $temp_type=$item->voucherTypeId;
                $is_bill = 'no';
            }
            @endphp  
          <button type="button" class="btn btn-light-blue btn-md btn-edit print_display_none" id="edit" data-entry_base_id="{{$item->entryBaseId}}" data-is_bill="{{$is_bill}}" data-id="{{$temp_id}}" data-type="{{$temp_type}}">الذهاب إلى المصدر</button>
        </td>
    </tr>

    @endforeach


        
    </tbody>
</table>
</div>
<div class='row'>

    @foreach($active_currencies as $curr)
        @php
            $icon = $curr->icon!=null?$curr->icon:'fa fa-money';
            $bg_color =  $curr->color!=null?'bg-'.$curr->color:'bg-red';
        @endphp
        
        <div class="col-md-{{12/$column_num}} col-sm-{{12/$column_num}} col-xs-12 "  >
            <div class="border-box">
                <div class="small-box {{$bg_color}}	">
                    <div class="inner inner-box">
                        <h3 id="final_SP_balance"> {{number_format($final_balance['curr_balance_'.$curr->id],2)}}	</h3>
                        <p>رصيد الحساب  {{$curr->name_ar}}	</p>
                    </div>
                    <div class="icon">
                        <i class="{{$icon}}"></i>
                    </div>
                    <a href="/modules/reports99?account_id=<?=$_REQUEST['account_id']?>&delegate_id=<?=$_REQUEST['delegate_id']?>&from_date=<?=$_REQUEST['from_date']?>&to_date=<?=$_REQUEST['to_date']?>&currency_id={{$curr->id}}" class="small-box-footer">شاهد التفاصيل <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    @endforeach
       
</div>
@endif
<!---- end Display type 1 ---->
@else
<!---- start Display type 2 ---->
<!---- عرض خاص بالحسابات التجميعية ---->
<div class="tableFixHead">
<table id="tableId3" class="table table-hover table-striped table-bordered">
    <caption> <b> <center> تفاصيل الحساب</center> </b></caption>
    <thead>
        <tr class="active">
            <th width="auto"><a href="" > اسم الحساب  </a></th>
            @foreach($active_currencies as $curr)
                <th width="auto"><a href="" > الرصيد ({{$curr->name_ar}}) </a></th>
            @endforeach
            
        </tr>
    </thead>
   
    <tbody class="ui-sortable">
    @php
        $final_balance = array();
        foreach($active_currencies as $curr){
            $final_balance['curr_balance_'.$curr->id]=0;
        }
    @endphp    

    @foreach($data as $item)
    @php
        foreach($active_currencies as $curr){
            $final_balance['curr_balance_'.$curr->id] += $item['curr_balance_'.$curr->id];
        }
    @endphp
    <tr>
        <td>
            {{$item['name']}}
        </td>
        @foreach($active_currencies as $curr)
            <td >
                {{number_format($item['curr_balance_'.$curr->id],2)}}
            </td>
        @endforeach
    </tr>

    @endforeach
        
    </tbody>
</table>
</div>

<div class='row'>
   
    @foreach($active_currencies as $curr)
        @php
            $icon = $curr->icon!=null?$curr->icon:'fa fa-money';
            $bg_color =  $curr->color!=null?'bg-'.$curr->color:'bg-red';
        @endphp
        <div class="col-md-{{12/$column_num}} col-sm-{{12/$column_num}} col-xs-12 "  >
            <div class="border-box">
                <div class="small-box {{$bg_color}}	">
                    <div class="inner inner-box">
                        <h3 > {{number_format($final_balance['curr_balance_'.$curr->id],2)}}	</h3>
                        <p>رصيد الحساب  {{$curr->name_ar}}	</p>
                    </div>
                    <div class="icon">
                        <i class="{{$icon}}	"></i>
                    </div>
                    <a href="javascript:void(0)" class="small-box-footer">شاهد التفاصيل <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    @endforeach
    
</div>

<!---- end Display type 2  ---->
@endif
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $('#select-account').select2();
        $('#select-delegate').select2();
        $('#select-currency').select2();

        $('.tableId').delegate('.btn-edit','click',function() {

            var is_bill = $(this).data('is_bill');
            var value = $(this).data('id');

            var type= $(this).data('type');

            var entryBaseId=$(this).data('entry_base_id');


            var base_url = window.location.origin;
            if(is_bill == 'yes'){
                if(type==1)
                {
                    window.location.href=base_url +"/modules/bills_purchase_invoice/detail/"+value;
                }

                if(type == 2)
                {
                    window.location.href=base_url +"/modules/bills_sales_invoice/detail/"+value;

                }
                if(type==3)
                {
                    window.location.href=base_url +"/modules/bills_purchase_return_invoice/detail/"+value;

                }
                if(type==4)
                {
                    window.location.href=base_url +"/modules/bills_sales_return_invoice/detail/"+value;

                }
            }else{
                if(type==1)
                {
                    window.location.href=base_url +"/modules/receipt_voucher/detail/"+value;
                }
                if(type==2)
                {
                    window.location.href=base_url +"/modules/payment_voucher/detail/"+value;
                }
                if(type==3)
                {
                    window.location.href=base_url +"/modules/transfer_vouchers/detail/"+value;
                }
                if(type==4)
                {
                    window.location.href=base_url +"/modules/initial_voucher/detail/"+value;
                }
            
                
            }


            if (value == 0) {
                window.location.href = base_url + "/modules/entry_base/detail/" + entryBaseId;

            }




            })

        $(document).ready(function(){
            $('#export').click(function(){
                let data =$('form').serializeArray();
                let data_json = JSON.stringify(data);
                window.open("/modules/account/export/"+data_json, '_blank');
            });

            $("#search").click(function(){
                $("#search i").removeClass('fa-search');
                $("#search i").addClass('fa-spinner fa-spin');
            });
        });

    </script>
@endsection
