@extends('crudbooster::admin_template')
@section('content')
<form method="get" action="{{url('modules/reports117')}}" class="print_display_none" >
<div class="col-lg-12">
<div class="col-lg-4">
اختر حساب الإقفال : 
    <select class="form-control" name="closing_account_type" id="select-closing_type" onchange="form.submit()">
    @foreach($closing_types as $type)
        <option value="{{$type->id}}" {{$closing_account_type==$type->id?'selected':''}} >{{$type->name_ar}}</option>
    @endforeach
    </select>
</div>
<div class="col-lg-3">
             <p  style="padding-top:7px;"></p>
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
<div class="tableFixHead">
    <table id="tableId"  class="table table-hover table-striped table-bordered" >
        <caption> 
            <b> <center>  تفاصيل حساب الإقفال <br/> ({{$closing_account_type_name}}) </center> </b>
        </caption>
    <thead >
    <tr class="active">
        <th width="auto" ><a href=""><center> اسم الحساب</center></a></th>
        @foreach($activeCurrencies as $curr)
        <th width="auto"><a href="" ><center> رصيد  {{$curr->name_ar}}</center></a></th>
        @endforeach
    </tr>
    </thead>
    <tbody class="ui-sortable"  align="center">
    @if($data != null)
    @foreach($data as $item)
        <tr>
            <td>{{$item['account_name']}}</td>
            @foreach($activeCurrencies as $curr)
                <td>{{number_format($item['curr_balance_'.$curr->id],2)}}</td>
            @endforeach
        </tr>
    @endforeach
      
    </tbody>
    <tfoot>
    @if(count($data) > 0)   
            <tr id="trTotal">
                <td align="center" bgcolor="#00ff7f" >
                    اجمالي الرصيد
                </td>
                @foreach($activeCurrencies as $curr)
                <td bgcolor="#7fff00">{{number_format($final_balances['curr_balance_'.$curr->id],2)}}</td>
                @endforeach
            </tr>
            @else
            <tr id="trTotal">
                <td colspan='{{count($activeCurrencies)+1}}' style="text-align:center;"> لا توجد  نتائج</td>
            </tr>
        @endif
    @endif
    </tfoot>
</table>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $('#select-closing_type').select2();

        $(document).ready(function(){
            $('#export').click(function(){
                let data =$('form').serializeArray();
                let data_json = JSON.stringify(data);
                window.open("/modules/closing_accounts/export/"+data_json, '_blank');
            });
        });
        
    </script>
@endsection
