(function($) {
"use strict";

/* ==============================================
LOADER -->
=============================================== */

    $(window).load(function() {
        $('#loader').delay(300).fadeOut('slow');
        $('#loader-container').delay(200).fadeOut('slow');
        $('body').delay(300).css({'overflow':'visible'});
    })

/* ==============================================
MENU -->
=============================================== */

    $(document).ready(function(){
        $('ul.nav li.dropdown').hover(function() {
          $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(200);
        }, function() {
          $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(200);
        });  

/* ==============================================
PARALLAX
=============================================== */

    $('#owl-clients').owlCarousel({
        margin:15,
        nav:false,
        dots:false,
        loop:true,
        autoplay:true,
        autoplayTimeout:2500,
        autoplayHoverPause:true,
        responsive:{
        0:{
        items:1
        },
        600:{
        items:3
        },
        1000:{
        items:5
        }
        }
    })
    $('#owl-home-properties').owlCarousel({
        loop:true,
        margin:30,
        nav:true,
        dots:false,
        autoplay:true,
        autoplayTimeout:2500,
        autoplayHoverPause:true,
        responsive:{
        0:{
        items:1
        },
        600:{
        items:2
        },
        1000:{
        items:4
        }
        }
    })
    $('#owl-team').owlCarousel({
        loop:true,
        margin:0,
        nav:true,
        dots:false,
        autoplay:true,
        autoplayTimeout:2500,
        autoplayHoverPause:true,
        responsive:{
        0:{
        items:1
        },
        600:{
        items:2
        },
        1000:{
        items:4
        }
        }
    })
    $('#owl-properties').owlCarousel({
        loop:true,
        margin:15,
        nav:true,
        dots:false,
        responsive:{
        0:{
        items:1
        },
        600:{
        items:2
        },
        1000:{
        items:3
        }
        }
    })
    $('#owl-testimonial').owlCarousel({
        loop:true,
        margin:15,
        nav:true,
        dots:false,
        responsive:{
        0:{
        items:1
        },
        600:{
        items:2
        },
        1000:{
        items:3
        }
        }
    })
    $('#owl-agents').owlCarousel({
        loop:true,
        margin:15,
        nav:true,
        dots:false,
        responsive:{
        0:{
        items:1
        },
        600:{
        items:1
        },
        1000:{
        items:1
        }
        }
        })
    })

/* ==============================================
ROTATE TEXT -->
=============================================== */

	$(".rotate").textrotator({
		animation: "fade",
		speed: 1000
	});

/* ==============================================
JS WINDOW HEIGHT -->
=============================================== */

    $(".js-height-full").height($(window).height());
        $(".js-height-parent").each(function(){
        $(this).height($(this).parent().first().height());
    });

/* ==============================================
ACCORDION -->
=============================================== */

    function toggleChevron(e) {
        $(e.target)
            .prev('.panel-heading')
            .find("i.indicator")
            .toggleClass('fa-minus fa-plus');
    }
    $('#accordion').bind('hidden.bs.collapse', toggleChevron);
    $('#accordion').bind('shown.bs.collapse', toggleChevron);

})(jQuery);
    