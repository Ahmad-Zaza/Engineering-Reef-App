@extends('crudbooster::admin_template')
@section('content')




            <table id="table-sort" class="table table-hover table-striped table-bordered ">
                  <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                sorting
                            </th>
                            <th>
                                title
                            </th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $key=>$item)
                        <tr id="{{$item->id}}">
                            <td id="key-{{$item->id}}">
                                {{$key+1}}
                            </td>
                            <td id="sorting-{{$item->id}}">
                                    {{$item->sorting}}
                            </td>
                            <td>
                                {{$item->title_en}}
                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>

<script>
     
</script>


@endsection