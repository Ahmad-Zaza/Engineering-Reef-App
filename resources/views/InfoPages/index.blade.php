@extends('crudbooster::admin_template')
@section('title', 'SEO') @section('content')

<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>



<div class="row">
    <div class="col-lg-12">

        <div class="ibox float-e-margins">
           
            <div class="ibox-content">

                @foreach ($errors->all() as $error)
                <li style="text-align: center" class="alert alert-danger">{{ $error }}</li>
                @endforeach
                <form  enctype="multipart/form-data" action="/info-page-store/{{$type}}" class="form-horizontal" method="post">
                    @csrf
                    
                      




                    

                  

                    <div class="form-group">
                        <label class="col-sm-2 control-label">brief english</label>

                        <div class="col-sm-10">
                            <input type="text" name="breif_en" title="brief" value="{{ ($data)?$data->breif_en:''}}" class="form-control">
                        </div>


                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">brief arabic</label>

                        <div class="col-sm-10">
                            <input type="text" name="breif_ar" title="brief" value="{{ ($data)?$data->breif_ar:''}}" class="form-control">
                        </div>


                    </div>



                     
                   

                        </div>

                  








                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <a class="btn btn-danger" href="/admin" >cancel</a>
                            <input class="btn btn-primary" type="submit" value="save">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{!! asset('js/imageScript.js') !!}" type="text/javascript"></script>

<script>

$(".add-key").click(function(){

var oldVal=$("#keywords_en").val();
var newVal=$("#text-key").val();

$("#keywords_en").val(oldVal +" , "+newVal);
$("#text-key").val("");



});

   $(".add-key1").click(function(){

var oldVal=$("#keywords_ar").val();
var newVal=$("#text-key1").val();

$("#keywords_ar").val(oldVal +" , "+newVal);
$("#text-key1").val("");



});





$(".form-horizontal").keypress(function(e) {
  //Enter key
  if (e.which == 13) {
    return false;
  }
});

</script>

@endsection
