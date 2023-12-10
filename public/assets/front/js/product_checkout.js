"use strict";
$(document).ready(function () {
  $('#stripe-element').addClass('d-none');
})
//payment gateway start
$('select[name="gateway"]').on('change', function () {
  let value = $(this).val();
  let dataType = parseInt(value);

  if (isNaN(dataType)) {
    if ($('.offline-gateway-info').hasClass('d-block')) {
      $('.offline-gateway-info').removeClass('d-block');
    }

    // hide offline gateway informations
    $('.offline-gateway-info').addClass('d-none');

    // show or hide stripe card inputs
    if (value == 'stripe') {
      $('#stripe-element').removeClass('d-none');
    } else {
      $('#stripe-element').addClass('d-none');
    }
  } else {
    // hide stripe gateway card inputs
    if (!$('#stripe-element').hasClass('d-none')) {
      $('#stripe-element').addClass('d-none');
      $('#stripe-element').removeClass('d-block');
    }

    // hide offline gateway informations
    $('.offline-gateway-info').addClass('d-none');

    // show particular offline gateway informations
    $('#offline-gateway-' + value).removeClass('d-none');
  }
});

//payment gateway end 

//calucate shipping method start
$('input[name="shipping_method"]').on('change', function () {
  var charge = $(this).attr('data-id');
  let s_charge = parseInt(charge);
  let cart_total = parseInt($('.cart_total').html());
  if ($('.shop_discount').length > 0) {
    var shop_discount = parseInt($('.shop_discount').html());
  } else {
    var shop_discount = 0;
  }

  var grand_total = (s_charge + cart_total) - shop_discount;
  $('.shipping_cost').html(s_charge);
  $('.grand_total').html(grand_total);
});
//calucate shipping method end

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$('#coupon-code').on('keypress', function (event) {
  if (event.key === "Enter") {
    event.preventDefault();
    var coupon_code = $("#coupon-code").val();
    var shipping_cost = parseInt($('.shipping_cost').html());

    $.ajax({
      type: 'POST',
      url: coupon_url,
      data: {
        coupon_code: coupon_code,
        shipping_cost: shipping_cost,
      },
      success: function (data) {
        $("#couponReload").load(location.href + " #couponReload");
        $('.shipping_cost').html(shipping_cost);

        toastr.options = {
          "closeButton": true,
          "progressBar": true,
          "timeOut": 10000,
          "extendedTimeOut": 10000,
          "positionClass": "toast-top-right",
        }
        $('#coupon-code').val('');
        if (data.status == 'error') {
          toastr.error(data.message);
        } else if (data.status == 'success') {
          toastr.success(data.message);
        }
      }
    });
  }
});

$("body").on('click', '.base-btn2', function (e) {
  e.preventDefault();
  var coupon_code = $("#coupon-code").val();
  var shipping_cost = parseInt($('.shipping_cost').html());

  $.ajax({
    type: 'POST',
    url: coupon_url,
    data: {
      coupon_code: coupon_code,
      shipping_cost: shipping_cost,
    },
    success: function (data) {
      $("#couponReload").load(location.href + " #couponReload");
      $('.shipping_cost').html(shipping_cost);

      toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "timeOut": 10000,
        "extendedTimeOut": 10000,
        "positionClass": "toast-top-right",
      }
      $('#coupon-code').val('');
      if (data.status == 'error') {
        toastr.error(data.message);
      } else if (data.status == 'success') {
        toastr.success(data.message);
      }
    }
  });
});

//stripe payment gateway code are goes here

// Set your Stripe public key
var stripe = Stripe(stripe_key);

// Create a Stripe Element for the card field
var elements = stripe.elements();
var cardElement = elements.create('card', {
  style: {
    base: {
      iconColor: '#454545',
      color: '#454545',
      fontWeight: '500',
      lineHeight: '50px',
      fontSmoothing: 'antialiased',
      backgroundColor: '#f2f2f2',
      ':-webkit-autofill': {
        color: '#454545',
      },
      '::placeholder': {
        color: '#454545',
      },
    }
  },
});

// Add an instance of the card Element into the `card-element` div
cardElement.mount('#stripe-element');

// Handle form submission
var form = document.getElementById('payment-form');
form.addEventListener('submit', function (event) {
  event.preventDefault();
  if ($('#payment').val() == 'stripe') {
    stripe.createToken(cardElement).then(function (result) {
      if (result.error) {
        // Display errors to the customer
        var errorElement = document.getElementById('stripe-errors');
        errorElement.textContent = result.error.message;
      } else {
        // Send the token to your server
        stripeTokenHandler(result.token);
      }
    });
  } else {
    $('#payment-form').submit();
  }
});

// Send the token to your server
function stripeTokenHandler(token) {
  // Add the token to the form data before submitting to the server
  var form = document.getElementById('payment-form');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form to your server
  form.submit();
}
