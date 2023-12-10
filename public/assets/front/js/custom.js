"use strict";
$('body').on('click', '#sameasshipping', function () {
    if ($('#sameasshipping').is(":checked")) {
        $('#shipping_address').addClass('d-none');
    } else {
        $('#shipping_address').removeClass('d-none');
    }
})

