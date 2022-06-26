<div class='form-group {{$header_group_class}} {{ ($errors->first($name))?"has-error":"" }}' id='form-group-{{$name}}' style="{{@$form['style']}}">
    <label class='col-sm-2 control-label'>{{$form['label']}}
        @if($required)
            <span class='text-danger' title='{!! trans('crudbooster.this_field_is_required') !!}'>*</span>
        @endif
    </label>

    <div class="{{$col_width?:'col-sm-10'}}">
        @if($value)
            <?php
            if(Storage::exists($value) || file_exists($value)):
            $url = asset($value);
            $ext = pathinfo($url, PATHINFO_EXTENSION);
            $images_type = array('jpg', 'png', 'gif', 'jpeg', 'bmp', 'tiff');
            if(in_array(strtolower($ext), $images_type)):
            ?>
            <div id="img-{{$name}}">
            <p><a data-lightbox='roadtrip' href='{{$url}}'><img style='max-width:160px' title="Image For {{$form['label']}}" src='{{$url}}'/></a></p>
            </div>
            <?php else:?>
            <p id="download-{{$name}}"><a href='{{$url}}'>{{trans("crudbooster.button_download_file")}}</a></p>
            <?php endif;
            echo "<input type='hidden' name='_$name' value='$value'/>";
            else:
                echo "<p class='text-danger'><i class='fa fa-exclamation-triangle'></i> ".trans("crudbooster.file_broken")."</p>";
            endif;
            ?>
            @if(!$readonly || !$disabled)
                <p><a class='btn btn-danger btn-delete btn-sm' id="btn-delete-{{$name}}"  onclick="deleteImage('{{$name}}')"
                      ><i
                                class='fa fa-ban'></i> {{trans('crudbooster.text_delete') }}   </a>
                                <span class="hide" id="link-delete-img">{{url(CRUDBooster::mainpath("delete-image?image=".$value."&id=".$row->id."&column="))}}</span>
                                </p>
            @endif
            <p class='text-muted' id="notic-{{$name}}"><em>{{trans("crudbooster.notice_delete_file_upload")}}</em></p>

        @endif
            <div id="upload-{{$name}}" class="{{($value)?'hide':''}}">
            <input type='file' id="{{$name}}" title="{{$form['label']}}" {{$required}} {{$readonly}} {{$disabled}} class='form-control' name="{{$name}}"/>
            <p class='help-block'>{{ @$form['help'] }}</p>
            </div>
        <div class="text-danger">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>

    </div>

</div>


<script>


//fesal 
//convert delete file to ajax request
function deleteImage(name){
    
    if(!confirm('{{trans("crudbooster.delete_title_confirm")}}')) return false;
    var id="#link-delete-img";
    var url=$(id).text()+name;
    $('html, body').css("cursor", "wait");
    
    $.ajax({
                url: url,
                type: "get",
                success: function (data){
                    var img_delete="#img-"+name;
                    var btn_delete="#btn-delete-"+name;
                    var button_upload="#upload-"+name;
                    var note="#notic-"+name;
                    var download="#download-"+name;
                    $('html, body').css("cursor", "auto");

                    
                    $(img_delete).hide();
                    $(btn_delete).hide();
                    $(note).hide();
                    $(download).hide();
                    $(button_upload).removeClass('hide');
                },
                error: function(error){
                    console.log(error);
                    $('html, body').css("cursor", "auto");
                    
                },
                dataType: 'json'
        });
}
</script>