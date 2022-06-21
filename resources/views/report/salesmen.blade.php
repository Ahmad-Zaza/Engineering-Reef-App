@extends('crudbooster::admin_template')
@section('content')
    <form method="get" action="{{url('modules/salesmen_report')}}" class="print_display_none">
        <div class="col-lg-12">
        <div class="col-lg-3">
        المهندس :
            <select class="form-control" name="delegate_id" id="delegate_id">
                <option value="-1">اختر المهندس</option>
                @foreach($delegates as $item)
                    <option value="{{$item->id}}" {{$delegate_id==$item->id?'selected':''}} >{{$item->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3">
        المادة :
            <select class="form-control" name="item_id" id="select-item">
                <option value="-1"> جميع المواد</option>
                @foreach($items as $item)
                    <option value="{{$item->id}}" {{$item_id==$item->id?'selected':''}}>{{$item->name_ar}}</option>
                @endforeach
            </select>
        </div>

            <div class="col-lg-3">
            الزبون:
                <select class="form-control" name="account_id" id="account_id">
                    <option value="-1">اختر الزبون</option>
                    @foreach($customers as $item)
                        <option value="{{$item->account_id}}" {{$account_id==$item->account_id?'selected':''}}>{{$item->name_ar}}</option>
                    @endforeach
                </select>
            </div>


        
        <div class="col-lg-3">

        {{trans('crudbooster.From')}} : {{$from_date}}<input type="date" name="from_date" id="from_date" value="{{$from_date}}"  class="form-control">
        </div>
      
        <br>
        <br>
  
        <div class="col-lg-3">
        {{trans('crudbooster.To')}} : {{$to_date}}  <input type="date" name="to_date" id="to_date" class="form-control" value="{{$to_date}}" >
        </div>
            <div class="col-lg-3">
            العملة :
                <select class="form-control" name="currency_id" id="select_currency">
                    <option value="-1"> جميع العملات</option>
                    @foreach($currencies as $item)
                        <option value="{{$item->id}}" {{$currency_id==$item->id?'selected':''}}>{{$item->name_ar}}</option>
                    @endforeach
                </select>
            </div>
        <br>
        <div class="col-lg-6">
        <button type="submit" class="btn btn-primary" id="search"> {{trans('crudbooster.Search')}} <i class="fa fa-search"></i></button>
            <a  name='reset' class="btn btn-warning" href="{{url('/modules/salesmen_report')}}" > تهيئة <i class="fa fa-refresh"></i></a>
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
<table id="tableId" class="table table-hover table-striped table-bordered" >
    <caption> <b> <center>  تقرير المهندس <br/> {{$delegate_name}}</center> </b></caption>
    <thead>
    <tr class="active" >
        <th width="auto" ><center><a href="">التاريخ</a></center></th>
        <th width="auto"><center><a href="" >الرمز</a></center></th>
        <th width="auto"><center><a href="" >ملاحظات</a></center></th>
        <th width="auto"><center><a href="" >نوع العملية</a></center></th>
        <th width="auto"><center><a href="" >اسم الزبون</a></center></th>
        <th width="auto"><center><a href="" >المجموع</a></center></th>
        <th width="auto"><center><a href=""> العملة </a></center></th>
        <th width="auto"><center><a href="" >المصدر</a></center></th>
    </tr>
    </thead>
    <tbody class="ui-sortable"  align="center">
    @if($data != null)
    @php
        $totalval = 0;
        $billCount = 0;
    @endphp
    @foreach($data as $item)
        @php  $billCount +=1;    @endphp
        <tr id="{{$item->billId}}">
            <td>
                {{$item->billDate}}
            </td>
            <td>
                {{$item->billCode}}
            </td>

            <td>
                {{$item->billNote}}
            </td>

            <td>
               @if($item->is_cash)
                   نقدي
                   @else
                    أجل
                   @endif
            </td>

            <td>
                @if($item->type == 2)

                {{$item->personName}}
                @elseif($item->type == 4)
                    {{$item->personName1}}
                @endif

            </td>
            <td>
           
                @if($item->type == 2)
                    @php
                        $curr_major_id=DB::table('currencies')->where('is_major',1)->first()->id;
                    @endphp
                        @if($currency_id == -1 && $item->currency_id != $curr_major_id)
                            @php  $totalval += $item->equalizer;    @endphp
                            {{number_format($item->equalizer,2)}}
                        @else
                             @php  $totalval += $item->billAmount;    @endphp
                            {{number_format($item->billAmount,2)}}
                        @endif

                @elseif($item->type == 4)
                    @if($currency_id == -1 && $item->currency_id != $curr_major_id)
                        @php  $totalval = $totalval - $item->equalizer;    @endphp
                        {{number_format(- $item->equalizer,2)}}
                    @else
                        @php  $totalval = $totalval - $item->billAmount;    @endphp
                        {{number_format(- $item->billAmount,2)}}
                    @endif
                @endif

            </td>
            <td> 
                @php
                    $curr=DB::table('currencies')->where('id',$item->currency_id)->first();
                    echo $curr->name_ar;
                @endphp
            </td>
            <td>
                <button type="button" class="btn btn-light-blue btn-md btn-edit print_display_none" id="edit" data-id="{{$item->billId}}" >الذهاب إلى المصدر</button>

            </td>

        </tr>

    @endforeach

    @endif

    </tbody>
    <tfoot>
        <tr id="trCount" >
            <td colspan="5" align="center" bgcolor="#00ff7f" >
            عدد الفواتير
            </td>
            <td id="debit" bgcolor="#7fff00" colspan="3">
                <span id="count">{{$billCount}}</span>

            </td>
        </tr>

    <tr id="trTotal" >
        <td colspan="5" align="center" bgcolor="#00ff7f" >
        مجموع ارصدة الفواتير
        </td>
        <td id="debit" bgcolor="#7fff00" colspan="5">
            <span id="total" >{{number_format($totalval,2)}}</span>
            
        </td>
    </tr>
    </tfoot>


</table>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
    <script   type="text/javascript">
        $('#delegate_id').select2();
        $('#select-item').select2();
        $('#account_id').select2();
        $('#select_currency').select2();

        

        // $('#val').val(sumVal);
        $('#tableId').delegate('.btn-edit','click',function() {

           var value = $(this).data('id');
           //  var currentRow=$(this).closest("tr");
           //  var type= currentRow.find("td:eq(0)").text(); // get current row 1st TD value
           //  var entryBaseId=currentRow.find("td:eq(1)").text(); // get current row 1st TD value
           // //  var billId=currentRow.find("td:eq(0)").text(); // get current row 1st TD value
            var base_url = window.location.origin;


                window.location.href=base_url +"/modules/bills_sales_invoice/detail/"+value;
            });

    
        $('#delegate_id').change(function(){

            var id = $(this).val();

            document.getElementById('account_id').options.length = 0;
            $.post('/reports/getCustomers/'+id,function(res){
                res.forEach(element => {
                    $('#account_id').append(new Option(element.name_ar, element.account_id));

                });
            })
        })
        
        
        /*
        $('#account_id').change(function(){
            var id = $(this).val();
            console.log(id);
            document.getElementById('delegate_id').options.length = 0;
            $.post('/reports/getDelegates/'+id,function(res){

                res.forEach(element => {
                    $('#delegate_id').append(new Option(element.name, element.id));
                });
                // document.getElementById('select_currency').options.length = 0;
                // //
                // $.post('/reports/getCurrenciesDealing/'+id,function(res){
                //     res.forEach(element => {
                //         $('#select_currency').append(new Option(element.name_ar, element.id));
                //
                //     });
                // })
            })

        }) 
        */

        $(document).ready(function(){
            $('#export').click(function(){
                let data =$('form').serializeArray();
                let data_json = JSON.stringify(data);
                window.open("/modules/salesmen/export/"+data_json, '_blank');
            });

            $("#search").click(function(){
                $("#search i").removeClass('fa-search');
                $("#search i").addClass('fa-spinner fa-spin');
            });
        });

    </script>
@stop
