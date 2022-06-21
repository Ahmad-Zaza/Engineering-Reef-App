@extends('crudbooster::admin_template')

 @section('content')


     <link rel="stylesheet" href="{!! asset('css/dropzone.css') !!}" />
     <script src="{!! asset('js/dropzone.js') !!}" type="text/javascript"></script> @section('scripts') @show

<style>
    /* Zoom in Keyframes */

    @-webkit-keyframes zoomin {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.5);
        }
        100% {
            transform: scale(1);
        }
    }

    @keyframes zoomin {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.5);
        }
        100% {
            transform: scale(1);
        }
    }

    /*End of Zoom in Keyframes */

    .container-img-gal:hover .img-gal {
        -ms-user-select: none;
        -webkit-animation: zoomin 3s ease-in;
        animation: zoomin 3s ease-in infinite;
        transition: all .5s ease-in-out;
    }

    .overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100%;
        width: 100%;
        opacity: 1;
        transition: .5s ease;
        background-color: rgba(29, 29, 29, 0.9);
        cursor: pointer;
    }

    .container-img-gal:hover .overlay {
        opacity: 0;
    }

    .text {
        color: white;
        font-size: 20px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        text-align: center;
    }

    .fa.fa-remove {
        font-size: 24px;
        color: red;
        position: absolute;
        right: 3px;
        top: 0;
        z-index: 11;
    }
</style>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active" onclick="openDropZone()">
        <a href="#upload" aria-controls="upload" role="tab" data-toggle="tab">Upload Image</a>
    </li>
    <li role="presentation" onclick="showImagesAlbum()">
        <a href="#images" aria-controls="images" role="tab" data-toggle="tab">images</a>
    </li>
</ul>

<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="upload">
        <form method="post" action="/image/upload/store/{{$model_id}}" enctype="multipart/form-data" class="dropzone" id="dropzone">
            @csrf
        </form>
    </div>
    <div role="tabpanel" class="tab-pane" id="images">
        <div class="row">
            <div class="col-md-12">

                <div id="mdb-lightbox-ui"></div>

                <div class="mdb-lightbox no-margin">
                



                </div>

            </div>
        </div>
    </div>
</div>



<script type="text/javascript">



    function openDropZone(){
        Dropzone.forElement('#dropzone').removeAllFiles(true)
    }

    function showImagesAlbum() {

       
       
        $('.mdb-lightbox').empty();
        $.ajax({
            type: "GET",
            url: "/image/showImageJson/{{$model_id}}",
            contentType: "application/json",
            dataType: "json",
            success: function (data) {
                $('.mdb-lightbox').empty();
                debugger
                data.data.forEach(function (element) {
                    $('.mdb-lightbox').prepend(`
                    <div id="image-`+element.id+`">

                <a href="/`+element.image_path+`" target="_blank" data-size="1600x1067">
                    <figure class="col-md-2">
                       
                            <div class="container-img-gal" style="height: 300px; margin: 10px 0;overflow: hidden;    position: relative;
                            width: 100%;">
                            
                                <div class="img-gal" style="background-image: url(/`+element.image_path+`);background-size: contain;background-repeat: no-repeat;background-position: center;height: 100%;
                    position: relative;
                    -webkit-user-select: none;
                    -moz-user-select: none;
                    overflow: hidden;"></div>
                                <a  onclick="deleteImage(`+element.id+`)">
                                    <i class="fa fa-remove" id="remove-icon" style="cursor: pointer;"></i>
                                </a>
                                <div class="overlay">
                                    <div class="text">VIEW IMAGE</div>
                                </div>

                            </div>
                       
                    </figure>
                </a>
                    </div>
                    `);
                });
            },
        });
    }


    function deleteImage(id){
        $.ajax({
            type: "GET",
            url: "/image/delete/"+id,
            contentType: "application/json",
            dataType: "json",
            success: function (data) {
                    console.log(data);
                    var id="#image-"+data
                    $(id).remove();
            },
        });
    }

    window.onload = function () {
        // access Dropzone here
        Dropzone.options.dropzone =
            {
                maxFilesize: 12,
                renameFile: function (file) {
                    var dt = new Date();
                    var time = dt.getTime();
                    return time + file.name;
                },
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                addRemoveLinks: true,
                timeout: 5000,
                success: function (file, response) {
                    console.log(response);
                },
                error: function (file, response) {
                    return false;
                },
                removedfile: function (file) {
                    var name = file.upload.filename;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{ url("image/delete") }}',
                        data: { filename: name },
                        success: function (data) {
                            console.log("File has been successfully removed!!");
                        },
                        error: function (e) {
                            console.log(e);
                        }
                    });
                    var fileRef;
                    return (fileRef = file.previewElement) != null ?
                        fileRef.parentNode.removeChild(file.previewElement) : void 0;
                },


            };
    };


</script> 

@endsection