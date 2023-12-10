"use strict";

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$(document).ready(function () {
  sessionStorage.setItem("booking_id", '');
});

// Flag to track if the request has already been sent
var requestSent = false;

function onScanSuccess(qrCodeMessage) {

  // Check if the request has already been sent
  if (requestSent && (qrCodeMessage == sessionStorage.getItem("booking_id"))) {
    return;
  }
  // Set the flag to true to indicate that the request is being sent
  requestSent = true;

  sessionStorage.setItem("booking_id", qrCodeMessage);
  //
  $.ajax({
    type: 'POST',
    url: url,
    data: {
      booking_id: qrCodeMessage
    },
    success: function (data) {
      console.log(data);
      if (data.alert_type == 'success') {
        swal({
          title: data.message,
          icon: "success",
        }).then(function () {
          requestSent = false;
        });
      } else {

        swal({
          title: data.message,
          icon: "error",
        }).then(function () {
          requestSent = false;
        });
      }
    }
  });

}

function onScanError(errorMessage) {
  //handle scan error
}
var html5QrcodeScanner = new Html5QrcodeScanner(
  "reader", {
  fps: 10,
  qrbox: 250
});

html5QrcodeScanner.render(onScanSuccess, onScanError);
