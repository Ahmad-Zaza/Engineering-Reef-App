@extends('crudbooster::admin_template')
@section('content')
<table id="" class="table table-hover table-striped table-bordered">
    <thead>
        <tr class="active">
        <th></th>
            <th width="auto"><a href="" title="Click to sort ascending">name &nbsp; <i class="fa fa-sort-asc"></i></a></th>
            <th width="auto"><a href="" title="Click to sort ascending">Total Paid Amount &nbsp; <i class="fa fa-sort"></i></a></th>
            <th width="auto"><a href="" title="Click to sort ascending">Total Received Amount &nbsp; <i class="fa fa-sort"></i></a></th>
            <th width="auto"><a href="" title="Click to sort ascending">budget &nbsp; <i class="fa fa-sort"></i></a></th>

            <th width="auto"><a href="" title="Click to sort ascending">currency &nbsp; <i class="fa fa-sort"></i></a></th>
        </tr>
    </thead>
    <tbody class="ui-sortable">
    @foreach($data as $item)
    <tr>
    <td><input type="checkbox" class="checkbox" name="checkbox[]" value="71"></td>

        <td>
            {{$item->name}}      
        </td>

        <td>
            {{($item->paid_amount)?$item->paid_amount:0}}      
        </td>


        <td>
        {{($item->received_amount)?$item->received_amount:0}}      

        </td>

        <td>
            {{abs((($item->received_amount)?$item->received_amount:0) - (($item->paid_amount)?$item->paid_amount:0))}}      
        </td>


        <td>
            {{$item->currency}}      
        </td>
    </tr>

    @endforeach


        
    </tbody>


   
</table>
@endsection