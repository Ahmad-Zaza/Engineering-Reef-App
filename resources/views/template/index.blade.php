@extends('layouts.app') @section('content')
<style>
    /* Zoom in Keyframes */
    #page-wrapper {
        background-image: url(".././bg1.png");
        background-repeat: no-repeat;
        background-size: cover;
    }

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
</style>
<div class="row">
    <div class="col-md-12">

        <div id="mdb-lightbox-ui"></div>

        <div class="mdb-lightbox no-margin">
            @foreach($templates as $item)
            @if($item->from_scratch)
            <figure class="col-md-4">
                <a href="/landingPage/create/{{$item->id}}" target="_blank" data-size="1600x1067">
                    <div class="container-img-gal" style="height: 300px; margin: 10px 0;overflow: hidden;    position: relative;
          width: 100%;">
                    
                        <div class="img-gal" style="background-image: url('{{$item->image_path}}');background-size: contain;background-repeat: no-repeat;background-position: center;height: 100%;
                        position: relative;
                        -webkit-user-select: none;
                        -moz-user-select: none;
                        overflow: hidden;"></div>
                        <div class="overlay">
                            <div class="text">{{$item->title}}</div>
                        </div>

                    </div>
                </a>
            </figure>
            @endif

            @if(!$item->from_scratch)
            <figure class="col-md-4">
                    <a href="/fromscratch" target="_blank" data-size="1600x1067">
                        <div class="container-img-gal" style="height: 300px; margin: 10px 0;overflow: hidden;    position: relative;
              width: 100%;">
                        
                            <div class="img-gal" style="background-image: url('{{$item->image_path}}');background-size: contain;background-repeat: no-repeat;background-position: center;height: 100%;
                            position: relative;
                            -webkit-user-select: none;
                            -moz-user-select: none;
                            overflow: hidden;"></div>
                            <div class="overlay">
                                <div class="text">{{$item->title}}</div>
                            </div>
    
                        </div>
                    </a>
                </figure>

            @endif

            @endforeach



        </div>

    </div>
</div>

@endsection