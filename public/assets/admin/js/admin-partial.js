
$(document).ready(function () {
  'use strict';

  // course thumbnail image
  $('.thumb-img-input').on('change', function (event) {
    let file = event.target.files[0];
    let reader = new FileReader();

    reader.onload = function (e) {
      $('.uploaded-thumb-img').attr('src', e.target.result);
    };

    reader.readAsDataURL(file);
  });

  // course cover image
  $('.cover-img-input').on('change', function (event) {
    let file = event.target.files[0];
    let reader = new FileReader();

    reader.onload = function (e) {
      $('.uploaded-cover-img').attr('src', e.target.result);
    };

    reader.readAsDataURL(file);
  });

  // course price type
  $('input:radio[name="pricing_type"]').on('change', function () {
    let radioBtnVal = $('input:radio[name="pricing_type"]:checked').val();

    if (radioBtnVal == 'premium') {
      $('#price-input').removeClass('d-none');
    } else {
      $('#price-input').addClass('d-none');
    }
  });

  // course form
  $('#courseForm').on('submit', function (e) {
    $('.request-loader').addClass('show');
    e.preventDefault();

    let action = $(this).attr('action');
    let fd = new FormData($(this)[0]);

    $.ajax({
      url: action,
      method: 'POST',
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $('.request-loader').removeClass('show');

        if (data.status == 'success') {
          location.reload();
        }
      },
      error: function (error) {
        let errors = ``;

        for (let x in error.responseJSON.errors) {
          errors += `<li>
                <p class="text-danger mb-0">${error.responseJSON.errors[x][0]}</p>
              </li>`;
        }

        $('#courseErrors ul').html(errors);
        $('#courseErrors').show();

        $('.request-loader').removeClass('show');

        $('html, body').animate({
          scrollTop: $('#courseErrors').offset().top - 100
        }, 1000);
      }
    });
  });

  // course's thanks page form
  $('#thanksPageForm').on('submit', function (e) {
    $('.request-loader').addClass('show');
    e.preventDefault();

    let action = $(this).attr('action');
    let fd = new FormData($(this)[0]);

    $.ajax({
      url: action,
      method: 'POST',
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $('.request-loader').removeClass('show');

        if (data.status == 'success') {
          location.reload();
        }
      },
      error: function (error) {
        let errors = ``;

        for (let x in error.responseJSON.errors) {
          errors += `<li>
                <p class="text-danger mb-0">${error.responseJSON.errors[x][0]}</p>
              </li>`;
        }

        $('#thanksPageErrors ul').html(errors);
        $('#thanksPageErrors').show();

        $('.request-loader').removeClass('show');

        $('html, body').animate({
          scrollTop: $('#thanksPageErrors').offset().top - 100
        }, 1000);
      }
    });
  });

  // blog form
  $('#blogForm').on('submit', function (e) {
    $('.request-loader').addClass('show');
    e.preventDefault();

    let action = $(this).attr('action');
    let fd = new FormData($(this)[0]);

    $.ajax({
      url: action,
      method: 'POST',
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $('.request-loader').removeClass('show');

        if (data.status == 'success') {
          location.reload();
        }
      },
      error: function (error) {
        let errors = ``;

        for (let x in error.responseJSON.errors) {
          errors += `<li>
                <p class="text-danger mb-0">${error.responseJSON.errors[x][0]}</p>
              </li>`;
        }

        $('#blogErrors ul').html(errors);
        $('#blogErrors').show();

        $('.request-loader').removeClass('show');

        $('html, body').animate({
          scrollTop: $('#blogErrors').offset().top - 100
        }, 1000);
      }
    });
  });

  // custom page form
  $('#pageForm').on('submit', function (e) {
    e.preventDefault();


    $('.request-loader').addClass('show');
    if ($(".btn-codeview").hasClass("active")) {
      $('.btn-codeview').trigger("click");
      let action = $('#pageForm').attr('action');
      let fd = new FormData($('#pageForm')[0]);
      $.ajax({
        url: action,
        method: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        success: function (data) {
          $('.request-loader').removeClass('show');

          if (data.status == 'success') {
            location.reload();
          }
        },
        error: function (error) {
          let errors = ``;

          for (let x in error.responseJSON.errors) {
            errors += `<li>
                <p class="text-danger mb-0">${error.responseJSON.errors[x][0]}</p>
              </li>`;
          }

          $('#pageErrors ul').html(errors);
          $('#pageErrors').show();

          $('.request-loader').removeClass('show');

          $('html, body').animate({
            scrollTop: $('#pageErrors').offset().top - 100
          }, 1000);
        }
      });
    } else {
      let action = $(this).attr('action');
      let fd = new FormData($(this)[0]);

      $.ajax({
        url: action,
        method: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        success: function (data) {
          $('.request-loader').removeClass('show');

          if (data.status == 'success') {
            location.reload();
          }
        },
        error: function (error) {
          let errors = ``;

          for (let x in error.responseJSON.errors) {
            errors += `<li>
                <p class="text-danger mb-0">${error.responseJSON.errors[x][0]}</p>
              </li>`;
          }

          $('#pageErrors ul').html(errors);
          $('#pageErrors').show();

          $('.request-loader').removeClass('show');

          $('html, body').animate({
            scrollTop: $('#pageErrors').offset().top - 100
          }, 1000);
        }
      });
    }



  });

  // sort course lesson contents
  $('#sort-content').sortable({
    stop: function (event, ui) {
      let sortRoute = '';

      if (sortContentUrl) {
        sortRoute = sortContentUrl;
      }

      $('.request-loader').addClass('show');

      let fd = new FormData();

      $('.ui-state-default').each(function (index) {
        fd.append('ids[]', $(this).data('id'));

        let orderNo = parseInt(index) + 1;
        fd.append('orders[]', orderNo);
      });

      $.ajax({
        url: sortRoute,
        type: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (data) {
          $('.request-loader').removeClass('show');

        }
      });
    }
  });

  // change course certificate status (enable/disable)
  $('input:radio[name="certificate_status"]').on('change', function () {
    let radioBtnVal = $('input:radio[name="certificate_status"]:checked').val();

    if (radioBtnVal == 1) {
      $('#certificate-settings').show();
    } else {
      $('#certificate-settings').hide();
    }
  });

  // show or hide input field according to selected ad type
  $('.ad-type').on('change', function () {
    let adType = $(this).val();

    if (adType == 'banner') {
      if (!$('#slot-input').hasClass('d-none')) {
        $('#slot-input').addClass('d-none');
      }

      $('#image-input').removeClass('d-none');
      $('#url-input').removeClass('d-none');
    } else {
      if (!$('#image-input').hasClass('d-none') && !$('#url-input').hasClass('d-none')) {
        $('#image-input').addClass('d-none');
        $('#url-input').addClass('d-none');
      }

      $('#slot-input').removeClass('d-none');
    }
  });

  $('.edit-ad-type').on('change', function () {
    let adType = $(this).val();

    if (adType == 'banner') {
      if (!$('#edit-slot-input').hasClass('d-none')) {
        $('#edit-slot-input').addClass('d-none');
      }

      $('#edit-image-input').removeClass('d-none');
      $('#edit-url-input').removeClass('d-none');
    } else {
      if (!$('#edit-image-input').hasClass('d-none') && !$('#edit-url-input').hasClass('d-none')) {
        $('#edit-image-input').addClass('d-none');
        $('#edit-url-input').addClass('d-none');
      }

      $('#edit-slot-input').removeClass('d-none');
    }
  });

  if ($("input[name='quiz_completion']").length > 0) {
    function loadQuizScore() {
      if ($("input[name='quiz_completion']:checked").val() == 1) {
        $("#minScore").show();
      } else {
        $("#minScore").hide();
      }
    }
    loadQuizScore();
    $("input[name='quiz_completion']").on('change', function () {
      loadQuizScore();
    });
  }


  // course price type
  $('input:radio[name="ticket_available_type"]').on('change', function () {
    let radioBtnVal = $('input:radio[name="ticket_available_type"]:checked').val();

    if (radioBtnVal == 'limited') {
      $('#ticket_available').removeClass('d-none');
    } else {
      $('#ticket_available').addClass('d-none');
    }
  });
  // course price type
  $('input:radio[name="max_ticket_buy_type"]').on('change', function () {
    let radioBtnVal = $('input:radio[name="max_ticket_buy_type"]:checked').val();

    if (radioBtnVal == 'limited') {
      $('#max_buy_ticket').removeClass('d-none');
    } else {
      $('#max_buy_ticket').addClass('d-none');
    }
  });

  // ticket pricing_type

  $("body").on('change', '#free_ticket', function () {
    if ($('#free_ticket').prop('checked') == true) {
      $('#ticket-pricing').addClass('d-none');
      $('#early_bird_discount_free').addClass('d-none');
    } else {
      $('#ticket-pricing').removeClass('d-none');
      $('#early_bird_discount_free').removeClass('d-none');
    }
  });

  // event price type
  $('input:radio[name="early_bird_discount_type"]').on('change', function () {
    let radioBtnVal = $('input:radio[name="early_bird_discount_type"]:checked').val();

    if (radioBtnVal == 'enable') {
      $('#early_bird_dicount').removeClass('d-none');
    } else {
      $('#early_bird_dicount').addClass('d-none');
    }
  });
  // event price type
  $('input:radio[name="pricing_type_2"]').on('change', function () {
    let radioBtnVal = $('input:radio[name="pricing_type_2"]:checked').val();

    if (radioBtnVal == 'variation') {
      $('#variation_pricing').removeClass('d-none');
      $('#normal_pricing').addClass('d-none');
      $('.hideInvariatinwiseTicket').addClass('d-none');
      $('#early_bird_discount_free').removeClass('d-none');
    } else if (radioBtnVal == 'normal') {
      $('#variation_pricing').addClass('d-none');
      $('#normal_pricing').removeClass('d-none');
      $('.hideInvariatinwiseTicket').removeClass('d-none');
      $('#early_bird_discount_free').removeClass('d-none');
    } else {
      $('#variation_pricing').addClass('d-none');
      $('#normal_pricing').addClass('d-none');
      $('.hideInvariatinwiseTicket').removeClass('d-none');
      $('#early_bird_discount_free').addClass('d-none');
    }
  });

  $('thead').on('click', '.addRow', function () {
    var id = Math.random(1, 999999);
    var id = parseInt(id * 100);
    var tr = `<tr>
        <td>
          ${names}
        </td>
        <td>
          <div class="form-group">
            <label for="">Price (${BaseCTxt}) *</label>
            <input type="text" name="variation_price[]" class="form-control">
          </div>
        </td>
        <td>
          <div class="from-group mt-1">
            <input type="checkbox" checked name="v_ticket_available_type[]" value="limited"
              class="ticket_available_type" id="limited_${id}"
              data-id="${id}">
            <label for="limited_${id}"
              class="limited_${id}">Limited</label>

            <input type="checkbox" name="v_ticket_available_type[]" value="unlimited"
              class="ticket_available_type d-none" id="unlimited_${id}"
              data-id="${id}">
            <label for="unlimited_${id}"
              class="unlimited_${id} d-none">Unlimited</label>

          </div>

          <div class="form-group" id="input_${id}">
            <label for="">Ticket Available * </label>
            <input type="text" name="v_ticket_available[]"
              value="" class="form-control">
          </div>
        </td>
        <td>
          <div class="from-group mt-1">
            <input type="checkbox" checked name="v_max_ticket_buy_type[]" value="limited"
              class="max_ticket_buy_type" id="buy_limited_${id}" data-id="${id}">
            <label for="buy_limited_${id}" class="buy_limited_${id} ">Limited'</label>

            <input type="checkbox" name="v_max_ticket_buy_type[]" value="unlimited"
              class="max_ticket_buy_type d-none" id="buy_unlimited_${id}" data-id="${id}">
            <label for="buy_unlimited_${id}"
              class="buy_unlimited_${id} d-none">Unlimited</label>
          </div>

          <div class="form-group" id="input2_${id}">
            <label for="">Max ticket for each customer * </label>
            <input type="text" name="v_max_ticket_buy[]" class="form-control">
          </div>
        </td>
        <td><a href="javascript:void(0)" class="btn btn-danger btn-sm deleteRow" > <i class="fas fa-minus"></i></a></td>
      </tr>`;
    $('tbody').append(tr);
  });

  $('tbody').on('click', '.deleteRow', function () {
    $(this).parent().parent().remove();
  });
  $('tbody').on('click', '.deleteRowAndDB', function () {
    $('.request-loader').addClass('show');

    $.get(baseUrl + '/admin/delete-variation/' + $(this).data('id'), function (data, status) {

      if (data == 'success') {
        $('.request-loader').removeClass('show');
        location.reload();
      }
    });
  });

  $('.eventDateType').on('change', function () {
    let value = $(this).val();
    if (value == 'multiple') {
      $('#single_dates').addClass('d-none');
      $('#multiple_dates').removeClass('d-none');
      $('.countDownStatus').addClass('d-none');
    } else {
      $('#single_dates').removeClass('d-none');
      $('#multiple_dates').addClass('d-none');
      $('.countDownStatus').removeClass('d-none');
    }
  });

  //add row for event dates
  $('thead').on('click', '.addDateRow', function () {
    var tr = `<tr>
                <td>
                  <div class="form-group">
                    <label for="">Start Date *</label>
                    <input type="date" name="m_start_date[]" class="form-control">
                  </div>
                </td>
                <td>
                  <div class="form-group">
                    <label for="">Start Time *</label>
                    <input type="time" name="m_start_time[]" class="form-control">
                  </div>
                </td>
                <td>
                  <div class="form-group">
                    <label for="">End Date *</label>
                    <input type="date" name="m_end_date[]" class="form-control">
                  </div>
                </td>
                <td>
                  <div class="form-group">
                    <label for="">End Time *</label>
                    <input type="time" name="m_end_time[]" class="form-control">
                  </div>
                </td>
                <td>
                  <a href="javascript:void(0)" class="btn btn-danger deleteDateRow">
                    <i class="fas fa-minus"></i></a>
                </td>
              </tr>`;
    $('tbody').append(tr);
  });

  $('tbody').on('click', '.deleteDateRow', function () {
    $(this).parent().parent().remove();
  });

  $('tbody').on('click', '.deleteDateDbRow', function () {
    $('.request-loader').addClass('show');

    $.get($(this).data('url'), function (data, status) {

      if (data == 'success') {
        $('.request-loader').removeClass('show');
        location.reload();
      }
    });
  });

});

$('body').on('click', '.ticket_available_type', function () {
  var id = $(this).attr('data-id');
  var full_id = 'input_' + id;

  if ($(this).is(":checked") && $(this).val() == 'unlimited') {
    $('#' + full_id).addClass('d-none');
    $('#' + full_id).prop("checked", false);
    $('#limited_' + id).addClass('d-none');
    $('.limited_' + id).addClass('d-none');
  } else if ($(this).not(":checked") && $(this).val() == 'limited') {
    $('#' + full_id).addClass('d-none');
    $('#' + full_id).prop("checked", false);
    $('#limited_' + id).addClass('d-none');
    $('.limited_' + id).addClass('d-none');
    $('.unlimited_' + id).removeClass('d-none');
    $('#unlimited_' + id).removeClass('d-none');
    $('#unlimited_' + id).prop("checked", true);
  } else {
    $('#' + full_id).removeClass('d-none');
    $('#limited_' + id).removeClass('d-none');
    $('#limited_' + id).prop("checked", true);
    $('.limited_' + id).removeClass('d-none');
    $(this).addClass('d-none');
    $(this).prop("checked", false);
    $('.unlimited_' + id).addClass('d-none');
  }

});
$('body').on('click', '.max_ticket_buy_type', function () {
  var id = $(this).attr('data-id');
  var full_id = 'input2_' + id;

  if ($(this).is(":checked") && $(this).val() == 'unlimited') {
    $('#' + full_id).addClass('d-none');
    $('#' + full_id).prop("checked", false);
    $('#buy_limited_' + id).addClass('d-none');
    $('.buy_limited_' + id).addClass('d-none');
  } else if ($(this).not(":checked") && $(this).val() == 'limited') {
    $('#' + full_id).addClass('d-none');
    $('#' + full_id).prop("checked", false);
    $('#buy_limited_' + id).addClass('d-none');
    $('.buy_limited_' + id).addClass('d-none');
    $('.buy_unlimited_' + id).removeClass('d-none');
    $('#buy_unlimited_' + id).removeClass('d-none');
    $('#buy_unlimited_' + id).prop("checked", true);
  } else {
    $('#' + full_id).removeClass('d-none');
    $('#buy_limited_' + id).removeClass('d-none');
    $('#buy_limited_' + id).prop("checked", true);
    $('.buy_limited_' + id).removeClass('d-none');
    $(this).addClass('d-none');
    $(this).prop("checked", false);
    $('.buy_unlimited_' + id).addClass('d-none');
  }

});
