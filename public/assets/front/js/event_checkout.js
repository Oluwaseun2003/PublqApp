"use strict";
$(document).ready(function () {
  $('#stripe-element').addClass('d-none');
})
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

//coupon code script
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$('#coupon-code').on('keypress', function (event) {
  if (event.key === "Enter") {
    event.preventDefault();
    var coupon_code = $("#coupon-code").val();
    $.ajax({
      type: 'POST',
      url: url,
      data: {
        coupon_code: coupon_code,
      },
      success: function (data) {
        $("#coupon-code").val('');
        $("#couponReload").load(location.href + " #couponReload");
        if (data.status == 'success') {
          toastr['success'](data.message);
        } else {
          toastr['error'](data.message);
        }
      }
    });
  }
});

$(".base-btn").on('click', function (e) {
  e.preventDefault();
  var coupon_code = $("#coupon-code").val();
  $.ajax({
    type: 'POST',
    url: url,
    data: {
      coupon_code: coupon_code,
    },
    success: function (data) {
      $("#coupon-code").val('');
      $("#couponReload").load(location.href + " #couponReload");
      if (data.status == 'success') {
        toastr['success'](data.message);
      } else {
        toastr['error'](data.message);
      }
    }
  });
});

$('#coupon-code').on('submit', function (e) {
  e.preventDefault();
});

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
