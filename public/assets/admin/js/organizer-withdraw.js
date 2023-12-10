$(document).ready(function () {
  'use strict';

  $('body').on('change', '#withdraw_method', function () {
    $.get(baseUrl + '/organizer/get-withdraw-method/input/' + $(this).val(), function (data) {
      $('#appned_input').html('');
      var input = '';
      $.each(data, function (key, value) {
        if (value.required == 1) {
          var required = '<span class="text-danger">*</span>';
        } else {
          var required = '';
        }

        if (value.type == 1) {
          input += `<div class='form-group'>
                  <label>${value.label} ${required}</label>
                  <input type='text' class='form-control' name="${value.name}" placeholder="${value.placeholder}">
                  <p id="err_${value.name}" class="mt-2 mb-0 text-danger em"></p>
                </div>`;
        } else if (value.type == 2) {
          input += `<div class='form-group'>
                  <label>${value.label} ${required}</label>
                  <select class="form-control" name="${value.name}">`;
          $.each(value.options, function (k, option) {
            input += `<option value="${option.id}">${option.name}</option>`;
          })
          input += `</select>
          <p id="err_${value.name}" class="mt-2 mb-0 text-danger em"></p>
                </div>`;
        } else if (value.type == 3) {

          input += `<div class='form-group'>
                  <label>${value.label} ${required}</label>
                  <div class="custom-control custom-checkbox">`;
          $.each(value.options, function (k, option) {
            input += `<div class="custom-control custom-checkbox">
                      <input type="checkbox" id="customRadio${option.id}" name="${value.name}"
                        class="custom-control-input">
                    <label class="custom-control-label"
                        for="customRadio${option.id}">${option.name}</label>
                      </div>
                      <p id="err_${value.name}" class="mt-2 mb-0 text-danger em"></p>
                      `;
          })
          input += `</div>`;
        } else if (value.type == 4) {
          input += `<div class='form-group'>
                  <label>${value.label} ${required}</label>
                  <textarea class="form-control" name="${value.name}" placeholder="${value.placeholder}"></textarea>
                  <p id="err_${value.name}" class="mt-2 mb-0 text-danger em"></p>
                </div>`;
        } else if (value.type == 5) {
          input += `<div class='form-group'>
                  <label>${value.label} ${required}</label>
                  <input type='date' class='form-control' name="${value.name}" placeholder="${value.placeholder}">
                  <p id="err_${value.name}" class="mt-2 mb-0 text-danger em"></p>
                </div>`;
        } else if (value.type == 6) {
          input += `<div class='form-group'>
                  <label>${value.label} ${required}</label>
                  <input type='time' class='form-control' name="${value.name}" placeholder="${value.placeholder}">
                  <p id="err_${value.name}" class="mt-2 mb-0 text-danger em"></p>
                </div>`;
        } else if (value.type == 7) {
          input += `<div class='form-group'>
                  <label>${value.label} ${required}</label>
                  <input type='number' class='form-control' name="${value.name}" placeholder="${value.placeholder}">
                  <p id="err_${value.name}" class="mt-2 mb-0 text-danger em"></p>
                </div>`;
        }
      });

      $('#appned_input').html(input);
    });
  });


  $('body').on('keyup', '#withdraw_amount', function () {
    if ($(this).val().length > 0) {
      $('.withdraw_alert_text').removeClass('d-none');
    } else {
      $('.withdraw_alert_text').addClass('d-none');
    }

    $("#receive_amount").html('');
    $("#total_charge").html('');
    $("#your_balance").html('');

    var method = $('#withdraw_method').val();
    var amount = $(this).val();

    $.get(baseUrl + '/organizer/withdraw/balance-calculation/' + method + '/' + $(this).val(), function (data) {
      if (data == 'error') {
        $('#max_balance').removeClass('d-none');
      } else {
        $('#max_balance').addClass('d-none');
        $("#receive_amount").html(data.receive_balance);
        $("#total_charge").html(data.total_charge);
        $("#your_balance").html(data.user_balance);
      }
    })
  });
});
