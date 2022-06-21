// $(".section1 .owl-carousel.owl-content").owlCarousel({
//     items: 1,
//     margin: 0,
//     stagePadding: 0,
//     dots: true,
//     autoplay: true,
//     autoplayTimeout: 2000,
//     autoplayHoverPause: true,
//     loop: true,
//     smartSpeed: 450,
//     afterMove: function (elem) {
//         var current = this.currentItem;
//         $(".section1 .owl-carousel.owl-img").trigger('owl.goTo', current);
//     }
// });
// var o2 = $(".section1 .owl-carousel.owl-img");
// var o2settings = {
//     items: 1,
//     margin: 0,
//     stagePadding: 0,
//     // dots: true,
//     autoplay: true,
//     autoplayTimeout: 2000,
//     autoplayHoverPause: true,
//     loop: true,
//     smartSpeed: 450,
//     afterMove: function (elem) {
//         var current = this.currentItem;
//         $(".section1 .owl-carousel.owl-content").trigger('owl.goTo', current);
//     }
// };
// o2.owlCarousel(o2settings);
// $(".section1 .owl-carousel.owl-content").on('translate.owl.carousel', function (e) {
//     o2.trigger('to.owl.carousel', e.page.index * o2settings.slideBy);
// });

// $(window).on('load', function () {
//     console.log('-_-');  

// });
// jQuery(document).click(function (e) {
//     var target = e.target; //target div recorded 
//     if (!jQuery(target).is('#search-word')) {
//         // $('#searchSec').fadeOut(300);
//         $("#search-box").addClass("open");
//     }
//     else {
//         $("#search-box").removeClass("open");
//     }
// });

$("#searchIcon").on("click", function (event) {
    $("#search-box").addClass("open"),
        $('#search-box > form > input[type="search"]').focus()
}),
    $("#search-box, #search-box .close").on("click keyup", function (event) {
        event.target != this && "close" != event.target.className && 27 != event.keyCode || $(this).removeClass("open")
    });


$(document).ready(function () {

    if ($(".langMenu li a.active")) {
        $(".langMenu li a.active").parent("li").css("display", "none")
    }


    $(".slide-title").each(function () {
        var title_highlight = $(this).text();
        var substr = title_highlight.split(' ');

        // var substr2 = title_highlight.split(' ').length -1;
        // console.log(substr2);
        var f2word = substr[0] + ' ' + substr[1];
        var ff2word = new RegExp(f2word, '');
        var span = $("<span/>").text(f2word);
        span.css("color", "#f07620");

        var c = title_highlight.replace(ff2word, "");
        $(this).html(c);
        $(this).prepend(span);
    });



    $('.loadinglogo img').animate({ 'opacity': '1' }, 100);
    $('.screen-loading').animate({ 'opacity': '0' }, function () {
        $(this).css({ 'display': 'none' });
        $(".page-content").animate({ 'opacity': '1' }, 500);
    });

    $("#searchIcon").click(function () {
        $('#searchSec').show(300);
    });
    if ($(window).width() < 767) {
        $('.owl-blogs').owlCarousel({
            center: true,
            items: 1,
            loop: true,
            stagePadding: 50,
            responsive: {
                // 600:{
                //     items:4
                // }
            }
        });
    }
    else if ($(window).width() < 1025) {
        $('.owl-blogs').owlCarousel({
            center: true,
            items: 1,
            loop: true,
            stagePadding: 230,
            responsive: {
                1024: {
                    items: 2
                }
            }
        });
    }
    else {
        $(".section5 .owl-carousel").removeClass("owl-carousel");
    }
    var docWidth = document.documentElement.offsetWidth;

    [].forEach.call(
        document.querySelectorAll('*'),
        function (el) {
            if (el.offsetWidth > docWidth) {
                console.log('hi');
                console.log(el);
            }
        }
    );
    // if ($("#servicesTab").length) {
    //     getAccordion("#servicesTab", 768);
    // }
    AOS.init();
    $(".owl-carousel.owl-testimonials-home").owlCarousel({
        items: 1,
        margin: 0,
        stagePadding: 0,
        dots: true,
        // autoplay: true,
        autoplayTimeout: 2000,
        autoplayHoverPause: true,
        loop: true,
        // onInitialized: fixOwl,
        // onRefreshed: fixOwl,
        autoplayHoverPause: false,
        rtl: $(".langMenu li a.active").text() == "ar" ? true : false,
        nav: true,
        navText: ['<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>'],
        smartSpeed: 450
    });

    // var o2 = $('.section1 .owl-carousel.owl-content');
    // o2.owlCarousel({
    //     animateOut: 'fadeOut',
    //     animateIn: 'fadeIn',
    //     items: 1,
    //     singleItem: true,
    //     loop: true,
    //     margin: 0,
    //     dots: false,
    //     pagination: false,
    //     nav: false,
    //     touchDrag: true,
    //     autoplay: true,
    //     smartSpeed: 450,
    //     autoplayHoverPause: false,
    // });


    var owl_highlight_img = $('.section1 .owl-carousel.owl-img');
    var setting_highlight_img = {
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        items: 1,
        singleItem: true,
        loop: true,
        margin: 0,
        dots: false,
        pagination: false,
        nav: false,
        autoplay: true,
        smartSpeed: 450,
        touchDrag: false,
        mouseDrag:false,
        autoplayHoverPause: false,
        rtl: $(".langMenu li a.active").text() == "ar" ? true : false,
    }
    owl_highlight_img.owlCarousel(setting_highlight_img);

    var owl_highlights_txt = $(".section1 .owl-carousel.owl-content");
    owl_highlights_txt.owlCarousel({
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        items: 1,
        singleItem: true,
        loop: true,
        margin: 0,
        dots: false,
        pagination: false,
        nav: false,
        touchDrag: false,
        mouseDrag:false,
        autoplay: true,
        smartSpeed: 450,
        autoplayHoverPause: false,
        rtl: $(".langMenu li a.active").text() == "ar" ? true : false,

    });

     // sync 
     owl_highlights_txt.on('change.owl.carousel', function (event) {
        // debugger
        var ind = event.item.index == 0 ? 0 : 2;
        owl_highlight_img.trigger('to.owl.carousel', [event.item.index-1, 300, true]);
    });

    $("div.product:nth-child(odd) div.overlay").addClass("overlay-show");

    var owl_about_img = $('.owl-carousel.owl-about-img');
    var setting_about_img = {
        items: 1,
        margin: 0,
        stagePadding: 0,
        loop: true,
        nav: true,
        touchDrag: false,
    }
    
    owl_about_img.owlCarousel(setting_about_img);
    var owl_about_txt = $(".owl-carousel.owl-about-txt");
    owl_about_txt.owlCarousel({
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        items: 1,
        margin: 0,
        stagePadding: 0,
        loop: true,
        nav: true,
        navText: ['<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>'],
    });

    // sync 
    owl_about_txt.on('change.owl.carousel', function (event) {
        // debugger
        var ind = event.item.index == 0 ? 0 : 2;
        owl_about_img.trigger('to.owl.carousel', [event.item.index-1, 300, true]);
    });


    var elementBgimage = document.getElementsByClassName("element-data-bgimage");
    for (var i = 0; i < elementBgimage.length; i++) {
        var imgSrc = elementBgimage[i].getAttribute("data-bgimage");
        elementBgimage[i].style.backgroundImage = 'url(' + imgSrc + ')';
        console.log(elementBgimage[i].getAttribute("data-bgimage"));
    }

});

$(".owl-blogs .product").click(function () {

});

// $(window).on('load', function () {

// });

// $(window).on("scroll", function () {

// });

// $("div.product:nth-child(even) .overlay").hover(function () {
//     $(this).addClass("overlay-show");
//     // $("div.product:nth-child(odd) div.overlay").removeClass("overlay-show");
// }, function () {
//     // CALL BACK
//     $(this).removeClass("overlay-show");
//     $("div.product:nth-child(odd) div.overlay").addClass("overlay-show");
// });

// var scrollbutton = $("#scroll-top");
// scrollbutton.click(function () {
//     $("html,body").animate({ scrollTop: 0 }, 900);
// });

$(window).on("scroll", function () {
    var hT,
        hH,
        wH = $(window).height(),
        wS = $(this).scrollTop();
    switch (true) {
        // case wS > ($('.footer').offset().top + $('.footer').outerHeight() - wH)-200:
        //     if($(window).width()< 768){
        //         $('.footer-left').animate({ 'left': '-275px' }, 800);
        //     }else
        //     $('.footer-left').animate({ 'left': '0' }, 800);
        //     break;
        // case wS > ($('.section5').offset().top + $('.section5').outerHeight() - wH) - 200:
        //     $('.img-sweet').animate({ 'opacity': '0.7' }, 1500);
        //     $('.section5-details').animate({ 'top': '0' }, 2000);
        //     break;
        // case wS > ($('.section4').offset().top + $('.section4').outerHeight() - wH) - 200:
        //     $('.detailssec4').animate({ 'opacity': '1', 'font-size': '16px' }, 1500);
        //     $('.brief-service').animate({ 'height': '75px' }, 2000);
        //     break;
        // case wS > ($('.section3').offset().top + $('.section3').outerHeight() - wH) - 250:
        //     $('.textsec3').animate({ 'top': '0', 'line-height': '1.6em' }, 1500);
        //     break;
        // case wS > ($('.section2').offset().top + $('.section2').outerHeight() - wH) - 200:
        //     $('.textsec2').animate({ 'top': '30vh', 'line-height': '1.6em' }, 2000);
        //     $('.decoration-sec2').animate({ 'right': '0' }, 1500);
        //     break;
    }
});


$(".scroll-down").on('click', function (event) {
    if (this.hash !== "") {
        event.preventDefault();
        var hash = this.hash;
        console.log($(hash).offset().top);
        $('html, body').animate({
            scrollTop: $(hash).offset().top
        }, 1000, function () {
            // Add hash (#) to URL when done scrolling (default click behavior)
            window.location.hash = hash;
        });
    }
});

function loadMoreblogs() {
    var count = $("#count-blogs").text();
    console.log(count);
        $(".more-btn").attr('disabled', 'disabled');

    $.ajax({
        type: "GET",
        url: '/blogs/page/' + count,
        success: function (data) {

            count = $("#count-blogs").text(data.sum);
            debugger
console.log(data);
            if (data.data.length == 0) {
                $('.more-btn').css("visibility", "hidden");
            }
            data.data.forEach(function (element) {
                // $('.owl-blogs').append("<div class='col-sm-6'><a href='/news/" + element.id + "'><div class='news' style='background-image:url(" + element.image + ")'><div class='overlay-news'></div></div><div class='news-info'><p class='brief hvr-underline-from-left'>"
                //     + element.title + "</p><span>Posted by " + element.writer + "</span><p class='details'>"
                //     + element.desc + "</div></a></div>");
                var a = $('.owl-blogs').append("<div class='col-sm-4 product'><a href='/blogs/" + element.id + "' class='a-overlay'><div class='overlay'><div class='view-p'><span>'by:'"
                    + element.writer + "</span> <h3 class='golden-title'>" + element.title + "</h3><p class='overlay-details'>"
                    + element.brief+ "<a>'more'</a></p></div><ul class='social-blog'><li class='hvr-underline-from-center'><span>facebook</span></li><li class='hvr-underline-from-center'><span>instagram</span></li><li class='hvr-underline-from-center'><span>twitter</span></li></ul></div><img src="
                    + element.img + " class='img-responsive'/></a></div>");
            });
                        $(".more-btn").removeAttr('disabled');
                        
                        var prop=(data.finish)?'hidden':'visible';
                        $(".more-btn").css("visibility", prop)

        },
        error: function (data) {
            alert(data.data);
        }
    });
}
$(".ajax-request").submit(function (e) {
    e.preventDefault();

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      
 $(document.body).css({ 'cursor': 'wait' });
$(".ajax-request button").prop("disabled", true);
    var url = $(this).attr('action');


    $.ajax({
        type: "POST",
        url: url,
        data:new FormData(this),
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
         success: function (data) {
            console.log(data);
            $(document.body).css({ 'cursor': 'default' });
            $(".ajax-request button").prop("disabled", false);
            $(".ajax-request input[type=text]").prop("value", "");
            $(".ajax-request textarea").prop("value", "");
            console.log('SUccess');
        
        },
        error: function (data) {
            alert(data);

        }
    });

});