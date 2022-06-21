@extends('crudbooster::admin_template')
@section('content')
    <form method="get" action="{{url('modules/accountstatement')}}" class="print_display_none">
        <div class="col-lg-12">
            <div class="col-lg-4">
            الحساب :
                <select class="form-control" name="person_id" id="select_customer">
                    <option value="-1">أختر الحساب</option>
                    @foreach($persons as $item)
                        <option value="{{$item->id}}" {{$person_id==$item->id?'selected':''}} >{{$item->name_ar}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4">
            العملة:
                <select class="form-control" name="currency_id" id="select_currency">
                    <option value="-1">اختر العملة</option>
                    @foreach($currencies as $item)
                        <option value="{{$item->id}}" {{$currency_id==$item->id?'selected':''}}>{{$item->name_ar}}</option>
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
                <br>
                <button type="submit" class="btn btn-primary" id="search"> {{trans('crudbooster.Search')}} <i class="fa fa-search"></i></button>
                <a  name='reset' class="btn btn-warning" href="{{url('/modules/accountstatement')}}" > تهيئة <i class="fa fa-refresh"></i></a>
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
    <table id="tableId"  class="table table-hover table-striped table-bordered" >
        <caption> <b> <center> تفاصيل الحساب</center> </b></caption>
    <thead >
    <tr class="active">
        <th width="auto" ><a href="" title="Click to sort ascending"><center> التاريخ</center></a></th>
        <th width="auto"><a href="" title="Click to sort ascending"><center> البيان</center></a></th>
        <th width="auto"><a href="" title="Click to sort ascending"><center> مدين</center></a></th>
        <th width="auto"><a href="" title="Click to sort ascending"><center> دائن</center></a></th>
        <th width="auto"><a href="" title="Click to sort ascending"><center>المصدر</center></a></th>
        <th width="20px" class="print_display_none" ><center> <button class="btn btn-sm btn-warning" id="show-all" data-action="show"><i class='fa fa-plus'></i></button> </center></th>
    </tr>
    </thead>
    <tbody class="ui-sortable"  align="center">
@if($_REQUEST['currency_id'] != '-1')

    @if($data != null)
    @php
    $debitval = 0;
    $creditval = 0;
    $total=0;
    $index = 0;
    @endphp

    @foreach($data as $item)
    @php
    $index = $index + 1;
    @endphp
        <tr  data-index="{{$index}}">
            
            <td>
                {{$item->entryDate}}
            </td>
            <td>
                @php
                if($item->billId !=null){
                    $type_name=$item->billTypeName;
                    $code=$item->billCode;
                }else{
                    $type_name=$item->VoucherTypeName;
                    $code=$item->VoucherCode;
                }
                @endphp
                {{$item->entryNarration}} / {{$type_name}} / {{$code}}
            </td>
            <td>
            @php 
                $debitval+= ($item->debit?$item->debit:0);
                $creditval+= ($item->credit?$item->credit:0);
            @endphp
                {{$item->debit?number_format($item->debit,2):0}}
            </td>
            <td>
                {{$item->credit?number_format($item->credit,2):0}}
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
                    $temp_id=$item->VoucherId;
                    $temp_type=$item->VoucherTypeId;
                    $is_bill = 'no';
                }
                @endphp
                <button type="button" class="btn btn-light-blue btn-md btn-edit print_display_none" id="edit" data-entry_base_id="{{$item->entryBaseId}}" data-is_bill="{{$is_bill}}" data-id="{{$temp_id}}" data-type="{{$temp_type}}">الذهاب إلى المصدر</button>

            </td>
            <td class="print_display_none">
                @if($is_bill == 'yes')
                <button type="button" style="float:left" class="btn btn-sm btn-primary btn-md btn-bill-details  print_display_none" id="show" data-action="show" data-entry_base_id="{{$item->entryBaseId}}" data-is_bill="{{$is_bill}}" data-id="{{$temp_id}}" data-type="{{$temp_type}}"> <i class="fa fa-plus"></i> </button>
                @endif
            </td>    
        </tr>

    @endforeach
       
    </tbody>
    <tfoot>
        @if(count($data) > 0)   
            <tr id="trTotal">
                <td colspan="2" align="center" >
                    الرصيد
                </td>
                <td id="debit">
                    <span id="debitval">{{number_format($debitval,2)}}</span>
                    
                </td>
                <td id="credit">
                    <span id="creditval">{{number_format($creditval,2)}}</span>
                    
                </td>
                
                <td id="total">
                    <span class="badge btn-primary" style="padding:10px;" id="totalval" title="مدين للشركة بهذا المبلغ">{{number_format($debitval-$creditval,2)}}</span>
                   
                </td>
                <td class="print_display_none"></td>
            </tr>
            @else
            <tr id="trTotal">
                <td colspan='6' style="text-align:center;"> لا توجد  مناقلات مالية خاصة بهذا الحساب</td>
            </tr>
        @endif
    @endif
@else
    <tr id="trTotal">
        <td colspan='6' style="text-align:center;"> يجب اختيار عملة لعرض المناقلات المالية الخاصة بالحساب</td>
    </tr>
@endif
    </tfoot>
</table>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
    <script   type="text/javascript">
        $('#select_customer').select2();
        $('#select_currency').select2();

            

        $('#select_customer').change(function(){

            var id = $(this).val();

            document.getElementById('select_currency').options.length = 0;
            $.post('/reports/getDealCurrencies/'+id,function(res){
                console.log(res);
                if (res.length != 0) {
                    res.forEach(element => {
                        $('#select_currency').append(new Option(element.name_ar, element.id));


                    });
                }
                else {
                    $('#select_currency').append(new Option('لا يوجد عملات', '-1'));

                }
            })
        })


        // $('#val').val(sumVal);
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
                    window.location.href=base_url +"/modules/receipt voucher/detail/"+value;
                }
                if(type==2)
                {
                    window.location.href=base_url +"/modules/payment voucher/detail/"+value;
                }
                if(type==3)
                {
                    window.location.href=base_url +"/modules/transfer vouchers/detail/"+value;
                }
                if(type==4)
                {
                    window.location.href=base_url +"/modules/initial voucher/detail/"+value;
                }
               
                
            }


            if (value == 0) {
                window.location.href = base_url + "/modules/entry_base/detail/" + entryBaseId;

            }




            })

            $('#tableId').delegate('.btn-bill-details','click',function() {
                var is_bill = $(this).data('is_bill');
                var value = $(this).data('id');
                var type= $(this).data('type');
                var entryBaseId=$(this).data('entry_base_id');
                var action= $(this).data('action');
              
                var curr_row = $(this).parent().parent();
                var index = curr_row.data('index');

                if(action == 'show'){
                    
                    $(this).find('i').addClass('fa-spin');
                    $.get('/bill-items/'+value,function(res){
                     console.log($(this));
                         var elem = curr_row.find('.btn-bill-details');
                         elem.find('i').removeClass('fa-plus fa-spin');
                         elem.find('i').addClass('fa-minus');
                         elem.data('action','hidden');
                        var new_elem = $("<tr class='details-"+index+"'><td colspan='6' style='padding:0px'> "+res+" </td></tr>");
                        new_elem.insertAfter(curr_row.closest('tr'));
                    });
                }else{
                    $(this).find('i').removeClass('fa-minus');
                    $(this).find('i').addClass('fa-plus');
                    $(this).data('action','show');
                    $('tr.details-'+index).remove();
                }
            });

            $('#show-all').click(function(){
                var action= $(this).data('action');
                if(action == 'show'){
                    $(this).find('i').removeClass('fa-plus');
                    $(this).find('i').addClass('fa-minus');
                    $(this).data('action','hidden');
                }else{
                    $(this).find('i').removeClass('fa-minus');
                    $(this).find('i').addClass('fa-plus');
                    $(this).data('action','show');
                }

                $(".btn-bill-details").each(function( index ) {
                    hisaction = $(this).data('action');
                    if(hisaction == action){
                        $(this).click();
                    }
                });
                
            });

        $(document).ready(function(){
            $('#export').click(function(){
                let data =$('form').serializeArray();
                let data_json = JSON.stringify(data);
                window.open("/modules/account_statement/export/"+data_json, '_blank');
            });

            $("#search").click(function(){
                $("#search i").removeClass('fa-search');
                $("#search i").addClass('fa-spinner fa-spin');
            });
        });
    </script>
@stop
