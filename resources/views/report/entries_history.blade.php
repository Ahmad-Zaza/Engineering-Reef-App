@extends('crudbooster::admin_template')
@section('content')
<form id="filter-form" method="get" action="{{url('modules/entries_history')}}" class="print_display_none" >
<div class="col-lg-12">
<div class="col-lg-3">
الحساب :
    <select class="form-control" name="account_id" id="select-account">
    <option value="-1">جميع الحسابات </option>
    @foreach($accounts as $item)
        <option value="{{$item->id}}" {{$account_id==$item->id?'selected':''}} >{{$item->name_ar}}</option>
    @endforeach
    </select>
</div>

    <div class="col-lg-3">
    المهندس :
        <select class="form-control" name="delegate_id" id="select-delegate">

            <option value="-1">جميع المهندسين </option>
            @foreach($delegates as $item)
                <option value="{{$item->id}}" {{$delegate_id==$item->id?'selected':''}} >{{$item->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-3">
    العملة :
        <select class="form-control" name="currency_id" id="select-currency">
            <option value="-1">جميع العملات </option>
            @foreach($currencies as $item)
                <option value="{{$item->id}}" {{$currency_id==$item->id?'selected':''}}>{{$item->name_ar}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-3">
    الإجراء :
        <select class="form-control" name="action" id="select-action">
            <option value="-1">جميع الإجراءات </option>
            <option value="1" {{$action==1?'selected':''}}>تعديل</option>
            <option value="2" {{$action==2?'selected':''}}>حذف</option>
            
        </select>
    </div>
    <div class="col-lg-3">{{trans('crudbooster.From')}} : {{$from_date}}

        <input type="date" name="from_date" id="from_date" class="form-control" value="{{$from_date}}">
    </div>

    <div class="col-lg-3">{{trans('crudbooster.To')}} : {{$to_date}}

        <input type="date" name="to_date" id="to_date" class="form-control" value="{{$to_date}}">
    </div>
    


<div class="col-lg-6">
    <br>
    <button type="submit" class="btn btn-primary" id="search"> {{trans('crudbooster.Search')}} <i class="fa fa-search"></i></button>
    <a  name='reset' class="btn btn-warning" href="{{url('/modules/entries_history')}}" > تهيئة <i class="fa fa-refresh"></i></a>
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
<div class="tableFixHead">
<table id="tableId" class="table table-hover table-striped table-bordered">
    <caption> <b> <center> أرشيف القيود المعدلة أو المحزوفة</center> </b></caption>
    <thead>
        <tr class="active">
        <th width="auto"><a href="" > رقم القيد </a></th>
            <th width="auto"><a href="" >الأسم </a></th>
            <th width="auto"><a href="" >مدين </a></th>
            <th width="auto"><a href="" >دائن </a></th>
            <th width="auto"><a href="" >البيان </a></th>
            <th width="auto"><a href="" >العملة </a></th>
            <th width="auto"><a href="" >تاريخ الإجراء</a></th>
            <th width="auto"><a href="" >الموظف </a></th>
            <th width="auto"><a href="" >الإجراء </a></th>
            <th width="auto" class="print_display_none"><a href="" ><center>المصدر</center></a></th>
        </tr>
    </thead>
    <tbody class="ui-sortable">
    @foreach($data as $item)
    <tr>
    <td >{{$item->entryBaseId}}</td>

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
            {{$item->narration}}      
        </td>
        <td>
            {{$item->currency}}
        </td>
            @php
                $temp_id='';
                $temp_type='';
                $is_bill = '';
                $action = '';
                if($item->bill_id !=null){
                    $temp_id=$item->bill_id;
                    $temp_type=$item->bill_type;
                    $is_bill = 'yes';
                    $action = $item->bill_delete_action=='delete'?'حذف':'تعديل';
                }else{
                    $temp_id=$item->voucher_id;
                    $temp_type=$item->voucher_type;
                    $is_bill = 'no';
                    $action = $item->vouchers_delete_action=='delete'?'حذف':'تعديل';
                }
            @endphp
        <td style="direction: ltr;">
            {{$item->delete_date}}      
        </td>
        <td>
            {{$item->employee_name}}
        </td>
        <td>
            {{$action}}
        </td>
        <td class="print_display_none">
           
            <button type="button" class="btn btn-light-blue btn-md btn-edit print_display_none" id="edit" data-entry_base_id="{{$item->entryBaseId}}" data-is_bill="{{$is_bill}}" data-id="{{$temp_id}}" data-type="{{$temp_type}}" >الذهاب إلى المصدر</button>
        </td>
    </tr>

    @endforeach


        
    </tbody>
</table>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $('#select-account').select2();
        $('#select-delegate').select2();
        $('#select-currency').select2();
        $('#select-action').select2();
       

   
        $('#tableId').delegate('.btn-edit','click',function() {

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
                window.open("/modules/entries_history/export/"+data_json, '_blank');
            });

            $("#search").click(function(){
                $("#search i").removeClass('fa-search');
                $("#search i").addClass('fa-spinner fa-spin');
            });
        });

    </script>
@endsection
