
        @if($seo)
        @section('description', $seo->description_en.','.$seo->description_ar)
        @section('keywords', $seo->keywords_en.','.$seo->keywords_ar)
        @section('author', $seo->author_en.','.$seo->author_ar)
        @section('title', $seo->title_en)
        @endif
        <link href="/landing_page/js/dist/css/bootstrap.min_ltr.css" rel="stylesheet" type="text/css" />
        <link href="/landing_page/js/fancybox/jquery.fancybox-1.3.4.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        body{
            background-image: url('{{$data->background_image}}');
            
        }
        </style>
    {!! $data->code !!}
    <script src="{{ asset('landing_page/vendor1/jquery/jquery.min.js') }}"></script>

<script>
 $('form[data-form]').css("visibility",'hidden');

    var formId=[];
                    $('form[data-form]').each(function(){
                            formId.push($(this).data("form"));
                     });

                       formId.forEach(element => {
                        $.ajax({
                            url: "/modules/getFormCode/" + element,
                            type:"get",
                            success: function(res) {

                                $('[data-form]').each(function(){
                                if($(this).data("form")==element){
                                    $(this).html(res);
                                    $(this).css("visibility",'visible');;
                                }

                               });
                            },

                        });
                       });

                       </script>
