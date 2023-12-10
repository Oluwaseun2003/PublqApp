/*-----------------------------------------------------------------------------------
    Template Name: Evento - Event HTML Template
    Template URI: https://webtend.net/demo/html/evento/
    Author: WebTend
    Author URI:  https://webtend.net/
    Version: 1.0

    Note: This is Main JS File.
-----------------------------------------------------------------------------------
    CSS INDEX
    ===================
    01. Header Style
    02. Dropdown menu
    03. Submenu
    04. Scroll to Top
    05. Select & Dropdowns
    06. Price Range Fliter
    07. Events Filtering
    08. Gallery Filtering
    09. Product Gallery
    10. Product Navs
    11. Related Event Carousel
    12. Event Details Images
    13. Event Details Gallery
    14. Product Gallery
    15. Image Gallery Popup
    16. Event CountDown
    17. Quantity Number
    18. Preloader
-----------------------------------------------------------------------------------*/

(function ($) {

    "use strict";

    $(document).ready(function () {

        // 01. Header Style and Scroll to Top
        function headerStyle() {
            if ($('.main-header').length) {
                var windowpos = $(window).scrollTop();
                var siteHeader = $('.main-header');
                var scrollLink = $('.scroll-top');
                if (windowpos >= 250) {
                    siteHeader.addClass('fixed-header');
                    scrollLink.fadeIn(300);
                } else {
                    siteHeader.removeClass('fixed-header');
                    scrollLink.fadeOut(300);
                }
            }
        }
        headerStyle();


        // 02. Dropdown menu
        var mobileWidth = 992;
        var navcollapse = $('.navigation li.dropdown');

        navcollapse.hover(function () {
            if ($(window).innerWidth() >= mobileWidth) {
                $(this).children('ul').stop(true, false, true).slideToggle(300);
                $(this).children('.megamenu').stop(true, false, true).slideToggle(300);
            }
        });

        // 03. Submenu Dropdown Toggle
        if ($('.main-header .navigation li.dropdown ul').length) {
            $('.main-header .navigation li.dropdown').append('<div class="dropdown-btn"><span class="fa fa-angle-down"></span></div>');

            //Dropdown Button
            $('.main-header .navigation li.dropdown .dropdown-btn').on('click', function () {
                $(this).prev('ul').slideToggle(500);
                $(this).prev('.megamenu').slideToggle(800);
            });

            //Disable dropdown parent link
            $('.navigation li.dropdown > a').on('click', function (e) {
                e.preventDefault();
            });
        }

        //Submenu Dropdown Toggle
        if ($('.main-header .main-menu').length) {
            $('.main-header .main-menu .navbar-toggle').click(function () {
                $(this).prev().prev().next().next().children('li.dropdown').hide();
            });
        }


        // 04. Scroll to Top
        if ($('.scroll-to-target').length) {
            $(".scroll-to-target").on('click', function () {
                var target = $(this).attr('data-target');
                // animate
                $('html, body').animate({
                    scrollTop: $(target).offset().top
                }, 1000);

            });
        }


        // 05. Select & Dropdowns //

        // Language Select
        if ($('#language').length) {
            $("#language").selectmenu();
        }

        // Borwse by Select
        if ($('#borwseby').length) {
            $("#borwseby").selectmenu();
        }

        // Widget Select
        if ($('.widget-select').length) {
            $(".widget-select").selectmenu();
        }

        // Products Dropdown
        if ($('#products-dropdown-select').length) {
            $("#products-dropdown-select").selectmenu();
        }

        // Dropdown Select
        if ($('#dropdown-select').length) {
            $("#dropdown-select").selectmenu({
                change: function (event, data) {
                    $('.by-date').removeClass('active');
                    $("#" + data.item.value).addClass('active');
                }
            });
        }


        // 06. Price Range Fliter jQuery UI
        if ($('.price-slider-range').length) {
            $(".price-slider-range").slider({
                range: true,
                min: 5,
                max: 800,
                values: [10, 500],
                slide: function (event, ui) {
                    $("#price").val("$ " + ui.values[0] + " - $ " + ui.values[1]);
                }
            });
            $("#price").val("$ " + $(".price-slider-range").slider("values", 0) +
                " - $ " + $(".price-slider-range").slider("values", 1));
        }


        // 07. Events Filtering
        $(".events-filter li").on('click', function () {
            $(".events-filter li").removeClass("current");
            $(this).addClass("current");

            var selector = $(this).attr('data-filter');
            $('.events-active').imagesLoaded(function () {
                $(".events-active").isotope({
                    itemSelector: '.item',
                    filter: selector,
                });
            });

        });



        // 08. Gallery Filtering
        $(".gallery-filter li").on('click', function () {
            $(".gallery-filter li").removeClass("current");
            $(this).addClass("current");

            var selector = $(this).attr('data-filter');
            $('.gallery-active').imagesLoaded(function () {
                $(".gallery-active").isotope({
                    itemSelector: '.item',
                    filter: selector,
                });
            });

        });


        // 09. Product Gallery
        if ($('.product-gallery').length) {
            $('.product-gallery').slick({
                dots: false,
                infinite: false,
                autoplay: false,
                arrows: true,
                speed: 1000,
                rtl: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                asNavFor: '.product-thumb',
                prevArrow: '<button class="prev"><i class="fas fa-chevron-left"></i></button>',
                nextArrow: '<button class="next"><i class="fas fa-chevron-right"></i></button>',
            });
        }

        // 10. Product Navs
        if ($('.product-thumb').length) {
            $('.product-thumb').slick({
                dots: false,
                infinite: false,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                focusOnSelect: true,
                asNavFor: '.product-gallery',
                rtl: true,
                slidesToShow: 5,
                slidesToScroll: 1,
                prevArrow: '<button class="prev"><i class="fas fa-chevron-left"></i></button>',
                nextArrow: '<button class="next"><i class="fas fa-chevron-right"></i></button>',
                responsive: [
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 4,
                        }
                    }
                ]
            });
        }


        // 11. Related Event Carousel
        if ($('.related-event-wrap').length) {
            $('.related-event-wrap').slick({
                dots: false,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 5000,
                arrows: true,
                speed: 1000,
                rtl: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                prevArrow: $('.slick-next-prev .prev'),
                nextArrow: $('.slick-next-prev .next'),
                responsive: [
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }


        // 12. Event Details Images
        if ($('.event-details-images').length) {
            $('.event-details-images').slick({
                dots: false,
                infinite: false,
                autoplay: true,
                autoplaySpeed: 5000,
                arrows: true,
                speed: 1000,
                rtl: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                prevArrow: '<button class="prev"><i class="fas fa-chevron-left"></i></button>',
                nextArrow: '<button class="next"><i class="fas fa-chevron-right"></i></button>',
            });
        }


        // 13. Event Details Gallery Popup
        $('.event-details-images a').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
            },
        });


        // 14. Product Gallery Popup
        $('.product-gallery a').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
            },
        });


        // 15. Image Gallery Popup
        $('.gallery-item').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
            },
        });


        /* 16. Event CountDown Start */
        if ($('.count-down').length !== 0) {
            const second = 1000,
                minute = second * 60,
                hour = minute * 60,
                day = hour * 24;
            let countDown = new Date('Jul 31, 2022 00:00:00').getTime(),
                x = setInterval(function () {
                    let now = new Date().getTime(),
                        distance = countDown - now;
                    document.getElementById('days').innerText = Math.floor(distance / (day)),
                        document.getElementById('hours').innerText = Math.floor((distance % (day)) / (hour)),
                        document.getElementById('minutes').innerText = Math.floor((distance % (hour)) / (minute)),
                        document.getElementById('seconds').innerText = Math.floor((distance % (minute)) / second);
                }, second)
        };

    });



    /* ==========================================================================
       When document is resize, do
       ========================================================================== */

    $(window).on('resize', function () {
        var mobileWidth = 992;
        var navcollapse = $('.navigation li.dropdown');
        navcollapse.children('ul').hide();
        navcollapse.children('.megamenu').hide();

    });


    /* ==========================================================================
       When document is scroll, do
       ========================================================================== */

    $(window).on('scroll', function () {

        // Header Style and Scroll to Top
        function headerStyle() {
            if ($('.main-header').length) {
                var windowpos = $(window).scrollTop();
                var siteHeader = $('.main-header');
                var scrollLink = $('.scroll-top');
                if (windowpos >= 100) {
                    siteHeader.addClass('fixed-header');
                    scrollLink.fadeIn(300);
                } else {
                    siteHeader.removeClass('fixed-header');
                    scrollLink.fadeOut(300);
                }
            }
        }

        headerStyle();

    });

    /* ==========================================================================
       When document is loaded, do
       ========================================================================== */

    $(window).on('load', function () {

        // 18. Preloader
        function handlePreloader() {
            if ($('.preloader').length) {
                $('.preloader').delay(200).fadeOut(500);
            }
        }
        handlePreloader();


        // 07. Events Filtering
        if ($('.events-active').length) {
            $(".events-active").isotope({
                itemSelector: '.item',
            });
        };


        // 08. Gallery Filtering
        if ($('.gallery-active').length) {
            $(".gallery-active").isotope({
                itemSelector: '.item',
            });
        };


    });


})(window.jQuery);
