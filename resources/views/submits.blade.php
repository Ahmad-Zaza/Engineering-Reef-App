@extends('crudbooster::admin_template')
@section('title', 'submits') @section('content')

<div class="row">
    <div class="container">
            @foreach($data as $item)
               <div class="well">
                    {{$item->created_at}}
                    {!! $item->response !!}
    
               </div>
            @endforeach
    </div>
</div>
@endsection
