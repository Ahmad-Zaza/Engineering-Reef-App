<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<div class='form-group {{$header_group_class}} {{ ($errors->first($name))?"has-error":"" }}' id='form-group-{{$name}}' style='{{@$form["style"]}}'>
    <label class='control-label col-sm-2'>{{$form['label']}}
        @if($required)
            <span class='text-danger' title='{!! trans('crudbooster.this_field_is_required') !!}'>*</span>
        @endif
    </label>

    <div class="{{$col_width?:'col-sm-10'}}">

        @if($value=='')
            <div class="input-group">
            <input  id="{{$name}}" class="form-control hide"  type="text"   value='{{$value}}' name="{{$name}}">

            <a data-lightbox="roadtrip" class="hide" id="link-{{$name}}" href="">
            <img style="width:150px;height:150px;" id="img-{{$name}}" title="Add image for {{$name}}" src="">
            </a>
            <br>

                <span class="input-group-btn" style="display:inline">
			        <a id="" onclick="OpenInsertImagesingle('{{$name}}')"  class="btn btn-primary">
			          @if(@$form['filemanager_type'] == 'file')
                            <i class="fa fa-file-o"></i> {{trans("crudbooster.chose_an_file")}}
                        @else
                            <i class='fa fa-picture-o'></i> {{trans("crudbooster.chose_an_image")}}
                        @endif
			        </a>
			      </span>

            </div>
        @endif
            
        @if($value)
            <input id="thumbnail-{{$name}}" class="form-control" type="hidden" value='{{$value}}' name="{{$name}}">
            @if(@$form['filemanager_type'] == 'file')
                @if($value)
                    <div style='margin-top:15px'><a id='holder-{{$name}}' href='{{asset($value)}}' target='_blank'
                                                    title=' {{trans("crudbooster.button_download_file")}} {{ basename($value)}}'><i
                                    class='fa fa-download'></i> {{trans("crudbooster.button_download_file")}}  {{ basename($value)}}</a>
                        &nbsp;<a class='btn btn-danger btn-delete btn-xs'
                                 onclick='swal({   title: "{{trans("crudbooster.delete_title_confirm")}}",   text: "{{trans("crudbooster.delete_description_confirm")}}",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "{{trans("crudbooster.confirmation_yes")}}",cancelButtonText: "{{trans('crudbooster.button_cancel')}}",   closeOnConfirm: false }, function(){  location.href="{{url($mainpath."/delete-filemanager?file=".$row->{$name}."&id=".$row->id."&column=".$name)}}" });'
                                 href='javascript:void(0)' title='{{trans('crudbooster.text_delete')}}'><i class='fa fa-ban'></i></a>
                    </div>
                @endif
            @else
                <p><a data-lightbox="roadtrip" href="{{ ($value)?asset($value):'' }}"><img id='holder-{{$name}}'
                                                                                           {{ ($value)?'src='.asset($value):'' }} style="margin-top:15px;max-height:100px;"></a>
                </p>
            @endif

            @if(!$readonly || !$disabled)
                <p><a class='btn btn-danger btn-delete btn-sm'
                      onclick='swal({   title: "{{trans("crudbooster.delete_title_confirm")}}",   text: "{{trans("crudbooster.delete_description_confirm")}}",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "{{trans("crudbooster.confirmation_yes")}}", cancelButtonText: "{{trans('crudbooster.button_cancel')}}",   closeOnConfirm: false }, function(){  location.href="{{url(CRUDBooster::mainpath("update-single?table=$table&column=$name&value=&id=$id"))}}" });'><i
                                class='fa fa-ban'></i> {{trans('crudbooster.text_delete')}} </a></p>
            @endif
        @endif


        <div class='help-block'>{{@$form['help']}}</div>
        <div class="text-danger">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
    </div>
</div>
@if(@$form['filemanager_type'])
    @push('bottom')
        <script type="text/javascript">$('#lfm-{{$name}}').filemanager('file', {prefix: "{{url(config('lfm.prefix'))}}"});</script>
    @endpush
@else
    @push('bottom')
        <script type="text/javascript">$('#lfm-{{$name}}').filemanager('images', {prefix: "{{url(config('lfm.prefix'))}}"});</script>
    @endpush

    
@endif


<div class="modal fade" id="modalInsertPhotosingle{{$name}}" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                     <h4 class="modal-title">{{trans('crudbooster.Insert_Image')}} <i class="fa "></i></h4>
                </div>
                <div class="modal-body" style="padding:0px; margin:0px; width: 100%;">

                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <script>

function OpenInsertImagesingle(name) {
    
    var link=`<iframe width="100%" height="400" src="/js/includes/filemanager/dialog.php?type=2&multiple=0&field_id=`+name+`" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>`;
    console.log(link);
    $("#modalInsertPhotosingle{{$name}} .modal-header h4 i").addClass('fa-refresh fa-spin');
    $("#modalInsertPhotosingle{{$name}} .modal-body").html(link);
    setTimeout(function(){
        $("#modalInsertPhotosingle{{$name}} .modal-header h4 i").removeClass('fa-refresh fa-spin');
    }, 3000);
    

    $("#modalInsertPhotosingle{{$name}}").modal();
}

var id='#modalInsertPhotosingle{{$name}}';

$(function () {

$(id).on('hidden.bs.modal', function(){
    
    var check=$('#{{$name}}').val();
    if(check!=""){
    $("#img-{{$name}}").attr("src", check);
    $("#link-{{$name}}").attr("href", check);
    $("#link-{{$name}}").removeClass("hide");
    }
    
    

  });
});

</script>