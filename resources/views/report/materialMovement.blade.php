@extends('crudbooster::admin_template')
@section('content')
<form method="get" action="{{url('modules/item_tracking102')}}" class="print_display_none">
<div class="col-lg-12">
<div class="col-lg-4">
المستودع:
    <select class="form-control" name="inventory_id" id="select-inventory">
    <option value="-1"> جميع المستودعات</option>
    @foreach($inventories as $item)
        <option value="{{$item->id}}" {{$inventory_id==$item->id?'selected':''}} >{{$item->name_ar}}</option>
    @endforeach
    </select>
</div>

<div class="col-lg-4">
المادة:
    <select class="form-control" name="item_id" id="select-item">
    <option value="-1">أختر المادة</option>
    @foreach($items as $item)
        <option value="{{$item->id}}" {{$item_id==$item->id?'selected':''}}>{{$item->name_ar}}</option>
    @endforeach
    </select>
</div>
    <div class="col-lg-4">
    العملية:
        <select class="form-control" name="type_id" id="select-operation">
            <option value="-1">  جميع العمليات</option>
            @foreach($types as $item)
                <option value="{{$item->id}}" {{$type_id==$item->id?'selected':''}} >{{$item->name_ar}}</option>
            @endforeach
        </select>
    </div>
    <br>
    <br>
    <br>
        <div class="col-lg-4">

        {{trans('crudbooster.From')}} : <input type="date" name="from_date" id="date" class="form-control" value="{{$from_date}}">
        </div>

    <div class="col-lg-4">
    {{trans('crudbooster.To')}} :   <input type="date" name="to_date" id="date" class="form-control" value="{{$to_date}}">
    </div>
<br>
    <div class="col-lg-4">
    <button type="submit" class="btn btn-primary" id="search"> {{trans('crudbooster.Search')}} <i class="fa fa-search"></i></button>
    <a  name='reset' class="btn btn-warning" href="{{url('/modules/item_tracking102')}}" > تهيئة <i class="fa fa-refresh"></i></a>
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
<br>
</div>

@if($item_id == null || $item_id == -1)
<div class="col-sm-12">
<div class="col-sm-12">
    <div class="callout callout-info">
        رجاءا اختر المادة لعرض تفصيل التقرير
    </div>
</div>
</div>
@else
<!---- start Report ------>

<div class="tableFixHead">
<table id="tableId" class="table table-hover table-striped table-bordered print_margn-rght-20">
    <caption> <b> <center>حركة المواد</center> </b></caption>
    <thead>
    <tr class="active">
        <th style="display:none;"></th>
        <th style="display:none;"></th>
        <th style="display:none;"></th>
        <th width="auto"><a href="" title="Click to sort ascending">نوع الوثيقة</a></th>
        <th width="auto"><a href="" title="Click to sort ascending">الرمز</a></th>
        <th width="auto"><a href="" title="Click to sort ascending">العميل</a></th>
        <th width="auto"><a href="" title="Click to sort ascending">التاريخ</a></th>
        <th width="auto"><a href="" title="Click to sort ascending">المستودع</a></th>
        <th width="auto"><a href="" title="Click to sort ascending">اسم المادة</a></th>
        <th width="auto"><a href="" title="Click to sort ascending">الوحدة</a></th>

        <th width="auto"><a href="" title="Click to sort ascending">داخل</a></th>
        <th width="auto"><a href="" title="Click to sort ascending">خارج</a></th>
        <th width="auto"><a href="" title="Click to sort ascending">رصيد</a></th>
  
        <!--th width="auto"><a href="" title="Click to sort ascending">الرصيد</a></th-->
        <th width="auto" class="print_display_none"><a href="" title="Click to sort ascending" >المصدر</a></th>
      
    </tr>
    </thead>
    <tbody class="ui-sortable">
    @php
        $allQuantity = 0;
    @endphp
    @foreach($data as $item)
        
        <tr id="{{$item->billId}}">
            <td style="display:none;">{{$item->typeEn}}</td>
            <td style="display:none;">{{$item->trackingId}}</td>
            <td style="display:none;">{{$item->billId}}</td>
            <td>
                {{$item->typeName}}
            </td>
            <td>
                {{$item->trackingName}}
            </td>
            <td>
               @php
                    $account_name = '';
                   if($item->typeId == 1 || $item->typeId == 3){
                        $account_name = $item->creditName;
                   }else{
                        $account_name = $item->debitName;
                   }
               @endphp
               {{$account_name}}
            </td>
            <td>
                {{$item->trackingDate}}
            </td>
            <td>
                {{$item->sourceInventory}}
            </td>

            <td>
                {{$item->itemNameAr}}
            </td>
            <td>
                {{$item->itemUnitNameAr}}

            </td>
            @php
              $in = 0;
              $out = 0;
              if($item->trackingOperation == 'in'){
                $in = $item->trackingQuantity;
                $allQuantity +=$item->trackingQuantity;
              }else{
                $out = $item->trackingQuantity;
                $allQuantity -=$item->trackingQuantity;
              }
            @endphp
            <td>
                {{number_format($in)}}
            </td>

            <td>
                {{number_format($out)}}
            </td>

            <td>
               {{number_format($allQuantity)}}
            </td>

            <td class="print_display_none">
                <button type="button" class="btn btn-light-blue btn-md btn-edit" id="edit" data-id="{{$item->trackingId}}" >الذهاب إلى المصدر</button>
            </td>

        </tr>

    @endforeach

    </tbody>
    <tfoot>
    @if(count($data) > 0)
    <tr>
        <td colspan="8" bgcolor="#00ff7f">الرصيد النهائي </td>
        <td bgcolor="#00ff7f"></td>
        <td bgcolor="#7fff00">{{number_format($allQuantity)}}</td>
        <td bgcolor="#00ff7f"></td>
    </tr>
    @endif

    </tfoot>   


</table>
</div>

<!---- end Report ------>
@endif
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
    <script   type="text/javascript">
        $('#select-inventory').select2();
        $('#select-item').select2();
        $('#select-operation').select2();

        $('#tableId').delegate('.btn-edit','click',function() {

           // var value = $(this).data('id');
            var currentRow=$(this).closest("tr");
            var type=currentRow.find("td:eq(0)").text(); // get current row 1st TD value
            var trackingId=currentRow.find("td:eq(1)").text(); // get current row 1st TD value
            var billId=currentRow.find("td:eq(2)").text(); // get current row 1st TD value

            var base_url = window.location.origin;

            if(type=="bill")
            {
                window.location.href=base_url +"/modules/bills_purchase_invoice/detail/"+billId;

            }
            if(type == "inventory beginning")
            {
                window.location.href=base_url +"/modules/item_tracking100/detail/"+trackingId;

            }
            if(type == "invoice")
            {
                window.location.href=base_url +"/modules/bills_sales_invoice/detail/"+billId;

            }
            if(type=="Purchase return")
            {
                window.location.href=base_url +"/modules/bills_purchase_return_invoice/detail/"+billId;

            }
            if(type=="Sales return")
            {
                window.location.href=base_url +"/modules/bills_sales_return_invoice/detail/"+billId;

            }
            if(type == "Transfer")
            {
                window.location.href=base_url +"/modules/item_tracking101/detail/"+trackingId;

            }

        })

        $(document).ready(function(){
            $('#export').click(function(){
                let data =$('form').serializeArray();
                let data_json = JSON.stringify(data);
                window.open("/modules/material_movement/export/"+data_json, '_blank');
            });

            $("#search").click(function(){
                $("#search i").removeClass('fa-search');
                $("#search i").addClass('fa-spinner fa-spin');
            });
        });
    </script>
@stop
