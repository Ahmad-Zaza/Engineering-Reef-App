@extends('crudbooster::admin_template')
@section('content')
<form method="get" action="{{url('modules/inventory_accounting')}}" class="print_display_none">
<div class="col-lg-12">
    <div class="col-lg-4">
         المادة:
        <select class="form-control" name="item_id" id="select-item">
            <option value="-1"> جميع المواد</option>
            @foreach($items as $item)
                <option value="{{$item->id}}" {{$item_id==$item->id?'selected':''}}>{{$item->name_ar}}</option>
            @endforeach
        </select>
    </div>
<div class="col-lg-4">
 المستودع:
    <select class="form-control" name="inventory_id" id="select-inventory">
    <option value="-1"> جميع المستودعات</option>
    @foreach($inventories as $item)
        <option value="{{$item->id}}" {{ ( $inventory_id==$item->id or count($inventories)== 1 ) ?'selected':''}} >{{$item->name_ar}}</option>
    @endforeach
    </select>
</div>

        <div class="col-lg-4"> {{trans('crudbooster.Date')}} : {{$date}}

          <input type="date" name="date" id="date" class="form-control" value="{{$date}}">
        </div>

<br>
<br>
<br>

    <div class="col-lg-4">
    <button type="submit" class="btn btn-primary" id="search"> {{trans('crudbooster.Search')}} <i class="fa fa-search"></i></button>
    <a  name='reset' class="btn btn-warning" href="{{url('/modules/inventory_accounting')}}" > تهيئة <i class="fa fa-refresh"></i></a>
    <button id="PrintReport" class="btn btn-success" onclick="window.print();" > طباعة <i class="fa fa-print"></i></button>
    <a  id="export" name='export' class="btn btn-info" href="javascript:void(0)" > تصدير <i class="fa fa-file-excel-o"></i></a>
</div>
</div>
</form>
<hr>
<br>
<br>
<br>
<div class="tableFixHead">
<table id="tableId" class="table table-hover table-striped table-bordered">
    <caption> <b> <center>أرصدة المواد</center> </b></caption>
    <thead>
    <tr class="active">
        <th width="auto"><a href="" title="Click to sort ascending">أسم المادة</a></th>
        <th width="auto"><a href="" title="Click to sort ascending">المستودع</a></th>
        <th width="auto"><a href="" title="Click to sort ascending">داخل</a></th>
        <th width="auto"><a href="" title="Click to sort ascending">خارج</a></th>
        <th width="auto"><a href="" title="Click to sort ascending">الرصيد</a></th>

    </tr>
    </thead>
    <tbody class="ui-sortable">
    @php   
        $allIn=0;
        $allOut=0;
        $total=0; 
    @endphp
    @foreach($data as $item)
        @php   
            $item_in = $item->item_in?$item->item_in:0;
            $item_out= $item->item_out?$item->item_out:0;
            $allIn +=$item_in;
            $allOut +=$item_out;
            $total += ($item_in - $item_out);
        @endphp
        <tr>

            <td>
                {{$item->nameAr}}
            </td>
            <td>
                {{$item->sourceInventory}}
            </td>
            <td>
                {{$item->item_in?number_format($item->item_in):0}}
            </td>
            <td>
                {{$item->item_out?number_format($item->item_out):0}}
            </td>
            <td>
                {{ number_format((($item->item_in?$item->item_in:0)) - (($item->item_out?$item->item_out:0)))}}

            </td>
        </tr>

    @endforeach

    </tbody>
    <tfoot>
        @if(count($data)>0)
        <tr>
            <td colspan="2" bgcolor="#00ff7f">الرصيد النهائي </td>
            <td bgcolor="#7fff00">{{number_format($allIn,2)}}</td>
            <td bgcolor="#7fff00">{{number_format($allOut,2)}}</td>
            <td bgcolor="#7fff00">{{number_format($total,2)}}</td>
        </tr>
        @endif
    </tfoot>


</table>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
    <script   type="text/javascript">

        $('#select-item').select2();
        $('#select-inventory').select2();

       

        $('#tableId').delegate('.btn-edit','click',function() {

           var value = $(this).data('id');
            var currentRow=$(this).closest("tr");
            var type=currentRow.find("td:eq(0)").text(); // get current row 1st TD value
            var trackingId=currentRow.find("td:eq(1)").text(); // get current row 1st TD value
            // alert(type);
            console.log(type);
            console.log(value);
            var base_url = window.location.origin;

            if(type=="bill")
            {
                window.location.href=base_url +"/modules/bills_purchase_invoice/detail/"+value;

            }
            if(type == "inventory beginning")
            {
                window.location.href=base_url +"/modules/item_tracking100/detail/"+trackingId;

            }
            if(type == "invoice")
            {
                window.location.href=base_url +"/modules/bills_sales_invoice/detail/"+value;

            }
            if(type=="Purchase return")
            {
                window.location.href=base_url +"/modules/bills_purchase_return_invoice/detail/"+value;

            }
            if(type=="Sales return")
            {
                window.location.href=base_url +"/modules/bills_sales_return_invoice/detail/"+value;

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
                window.open("/modules/inventory_accounting/export/"+data_json, '_blank');
            });

            $("#search").click(function(){
                $("#search i").removeClass('fa-search');
                $("#search i").addClass('fa-spinner fa-spin');
            });
        });
    </script>
@stop
