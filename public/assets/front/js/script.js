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
            $("#language").selectmenu({
                change: function (event, ui) {
                    this.form.submit();
                }
            });
        }

        // Borwse by Select
        if ($('#borwseby').length) {
            $("#borwseby").selectmenu();
        }

        // Borwse by Select
        if ($('#rateBy').length) {
            $("#rateBy").selectmenu();
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
                slidesToShow: 1,
                slidesToScroll: 1,
                rtl: rtl == 1 ? true : false,
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
                slidesToShow: 5,
                slidesToScroll: 1,
                rtl: rtl == 1 ? true : false,
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
                slidesToShow: 3,
                slidesToScroll: 1,
                rtl: rtl == 1 ? true : false,
                prevArrow: $('.slick-next-prev .prev'),
                nextArrow: $('.slick-next-prev .next'),
                responsive: [
                    {
                        breakpoint: 992,
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
                slidesToShow: 1,
                slidesToScroll: 1,
                rtl: rtl == 1 ? true : false,
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
        }


        // 08. Gallery Filtering
        if ($('.gallery-active').length) {
            $(".gallery-active").isotope({
                itemSelector: '.item',
            });
        }

        if ($(".popup-wrapper").length > 0) {
            let $firstPopup = $(".popup-wrapper").eq(0);
            popupAnnouncement($firstPopup);
        }
    });

    // lazyload init
    new LazyLoad();

    $('.offer-timer').each(function () {
        let $this = $(this);
        let d = new Date($this.data('end_date'));
        let ye = parseInt(new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d));
        let mo = parseInt(new Intl.DateTimeFormat('en', { month: 'numeric' }).format(d));
        let da = parseInt(new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d));
        let t = $this.data('end_time');
        let time = t.split(":");
        let hr = parseInt(time[0]);
        let min = parseInt(time[1]);
        $this.syotimer({
            year: ye,
            month: mo,
            day: da,
            hour: hr,
            minute: min,
        });
    });
})(window.jQuery);

function popupAnnouncement($this) {
    let closedPopups = [];
    if (sessionStorage.getItem('closedPopups')) {
        closedPopups = JSON.parse(sessionStorage.getItem('closedPopups'));
    }

    // if the popup is not in closedPopups Array
    if (closedPopups.indexOf($this.data('popup_id')) == -1) {
        $('#' + $this.attr('id')).show();
        let popupDelay = $this.data('popup_delay');

        setTimeout(function () {
            jQuery.magnificPopup.open({
                items: { src: '#' + $this.attr('id') },
                type: 'inline',
                callbacks: {
                    afterClose: function () {
                        // after the popup is closed, store it in the sessionStorage & show next popup
                        closedPopups.push($this.data('popup_id'));
                        sessionStorage.setItem('closedPopups', JSON.stringify(closedPopups));


                        if ($this.next('.popup-wrapper').length > 0) {
                            popupAnnouncement($this.next('.popup-wrapper'));
                        }
                    }
                }
            }, 0);
        }, popupDelay);
    } else {
        if ($this.next('.popup-wrapper').length > 0) {
            popupAnnouncement($this.next('.popup-wrapper'));
        }
    }
}

// scroll to bottom

if ($('.messages').length > 0) {
    $('.messages')[0].scrollTop = $('.messages')[0].scrollHeight;
}

/*============================================
    Image upload
============================================*/
var fileReader = function (input) {
    var regEx = new RegExp(/\.(gif|jpe?g|tiff?|png|webp|bmp)$/i);
    var errorMsg = $("#errorMsg");

    if (input.files && input.files[0] && regEx.test(input.value)) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        errorMsg.html("Please upload a valid file type")
    }
}
$("#imageUpload").on("change", function () {
    fileReader(this);
});

$('.quantity-up').on('click', function () {
    let max_qty = parseInt($(this).prev().attr('data-max_buy_ticket'));
    let stock = Number($(this).prev().attr('data-stock'));
    let ticket_id = $(this).prev().attr('data-ticket_id');
    let purchase = $(this).prev().attr('data-purchase');
    let p_qty = parseInt($(this).prev().attr('data-p_qty'));
    var numProduct = Number($(this).prev().val());

    $('.max_error_' + ticket_id + max_qty).text('');

    if (max_qty != 'unlimited' && (numProduct + p_qty) >= max_qty) {
        $('.max_error_' + ticket_id + max_qty).text(`You can't purchase more tickets.`);
    } else if (purchase == 'true') {
        $('.max_error_' + ticket_id + max_qty).text(`You already purchase this ticket.`);
    } else if (stock < (numProduct)) {
        $('.max_error_' + ticket_id + max_qty).text(`You can't purchase more tickets.`);
    } else if (stock != 'unlimited' && stock < numProduct + 1) {
        $('.max_error_' + ticket_id + max_qty).text('Stock Out');
    } else if (max_qty != 'unlimited' && (numProduct >= max_qty && max_qty != '')) {
        $('.max_error_' + ticket_id + max_qty).text('One can buy maximum ' + max_qty + ' Tickets');
    } else {

        $(this).prev().val(numProduct + 1);
        calcTotal();
    }
});
$('.quantity-down').on('click', function () {
    var numProduct = Number($(this).next().val());
    let max_qty = $(this).next().attr('data-max_buy_ticket');
    let ticket_id = $(this).next().attr('data-ticket_id');
    if (numProduct > 0) $(this).next().val(numProduct - 1);
    calcTotal();

    $('.max_error_' + ticket_id + max_qty).text('');
});
$('.quantity-down_variation').on('click', function () {
    var numProduct = Number($(this).next().next().val());
    if (numProduct > 0) $(this).next().next().val(numProduct - 1);
    calcTotal();

    let max_qty = $(this).next().next().attr('data-max_buy_ticket');
    let ticket_id = $(this).next().next().attr('data-ticket_id');
    $('.max_error_' + ticket_id + max_qty).text('');
});

function calcTotal() {
    let sum = 0;
    $(".quantity").each(function (index) {
        var price = parseFloat($(this).attr('data-price'));
        var qty = parseInt($(this).val());
        sum = sum + (price * qty);

        if (sum > 0) {
            $('.currency_symbol').removeClass('d-none');
            $('#total_price').html(sum.toFixed(2));
            $('#total').val(sum.toFixed(2));
        } else {
            $('#total_price').html(0);
            $('#total').val(0);
            $('.currency_symbol').addClass('d-none');
        }

    });
}

//add to wishlist
$('#add_to_wishlist').on('click', function () {
    var id = $(this).attr('data-id');
    $.ajax({
        url: baseUrl + '/addto/wishlist/' + id,
        method: 'get',
        success: function (result) {
        }
    })
});

$(document).ready(function () {
    $('#example').DataTable({
        responsive: true,
        ordering: false
    });
});

$(".read-more-btn").on("click", function () {
    $(this).parent().toggleClass('show');
})

var bgImage = $(".bg-img")
bgImage.each(function () {
    var el = $(this),
        src = el.attr("data-bg-image");

    el.css({
        "background-image": "url(" + src + ")",
        "background-size": "cover",
        "background-position": "center",
        "display": "block"
    });
});

$('.event-countdown').each(function () {
    let $this = $(this);

    $this.syotimer({
        date: new Date($this.data('now')),
        year: $this.data('year'),
        month: $this.data('month'),
        day: $this.data('day'),
        hour: $this.data('hour'),
        minute: $this.data('minute'),
        timeZone: $this.data('timezone'),
        afterDeadline: function (timerBlock) {
            timerBlock.bodyBlock.html('');
        }
    })
});

$('.showLoader').on('click', function () {
    $('.preloader').show();
});


var countEl = $(".event-countdown");
var childCount = countEl.find(".syotimer-cell");
childCount.each(function () {
    var child = $(this).find(".syotimer-cell__value");
    setInterval(() => {
        var value = Number(child.html());
        child.attr("style", '--value: ' + value + '');
    }, 0);
});

$(document).on('click', '.review-value li a', function () {
    $('.review-value li a i').removeClass('text-primary');
    let reviewValue = $(this).attr('data-href');
    parentClass = `review-${reviewValue}`;
    $('.' + parentClass + ' li a i').addClass('text-primary');
    $('#reviewValue').val(reviewValue);
});
// add user email for subscribe
$('.subscriptionForm').on('submit', function (event) {
    event.preventDefault();

    let formURL = $(this).attr('action');
    let formMethod = $(this).attr('method');

    let formData = new FormData($(this)[0]);

    $.ajax({
        url: formURL,
        method: formMethod,
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            $('input[name="email_id"]').val('');

            toastr['success'](response.success);
        },
        error: function (errorData) {
            toastr['error'](errorData.responseJSON.error.email_id[0]);
        }
    });
});
$('body').on('submit', '#vendorContactForm', function (e) {
    e.preventDefault();
    let vendorContactForm = document.getElementById('vendorContactForm');
    $('.request-loader').addClass('show');
    var url = $(this).attr('action');
    var method = $(this).attr('method');

    let fd = new FormData(vendorContactForm);
    $.ajax({
        url: url,
        method: method,
        data: fd,
        contentType: false,
        processData: false,
        success: function (data) {
            $('.request-loader').removeClass('show');
            $('.em').each(function () {
                $(this).html('');
            });

            if (data == 'success') {
                location.reload();
            }
        },
        error: function (error) {
            $('.em').each(function () {
                $(this).html('');
            });

            for (let x in error.responseJSON.errors) {
                document.getElementById('Error_' + x).innerHTML = error.responseJSON.errors[x][0];
            }

            $('.request-loader').removeClass('show');
        }
    })
})




