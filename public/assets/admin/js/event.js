$(document).ready(function () {
  'use strict';

  function clickSubmit() {
    $("#filtersForm input").each(function () {
      if ($(this).val().length == 0) {
        $(this).remove();
      }
    });
    $('#submitBtn').trigger('click');
  }


  $("#category").selectmenu({
    change: function (event, ui) {
      if ($(this).val().length == 0) {
        $(this).remove();
      }
      $('#catForm').submit();
    }
  });

  $("#country").selectmenu({
    change: function (event, ui) {
      $('#country-id').val($(this).val());
      clickSubmit();
    }
  });
  $("#state").selectmenu({
    change: function (event, ui) {
      $('#state-id').val($(this).val());
      clickSubmit();
    }
  });
  $("#city").selectmenu({
    change: function (event, ui) {
      $('#city-id').val($(this).val());
      clickSubmit();
    }
  });

  // search course by category
  $('input:radio[name="event"]').on('change', function () {
    let event = $('input:radio[name="event"]:checked').val();

    $('#event').val(event);
    clickSubmit();
  });


  // search course by sorting
  $('#slider_submit').on('click', function () {
    clickSubmit();
  });

  if ($('input[name="daterange"]').length > 0) {
    $('input[name="daterange"]').daterangepicker({
      opens: 'left',
      autoUpdateInput: false,
      locale: {
        format: 'YYYY-MM-DD'
      }
    }, function (start, end, label) {
      var start = start.format('YYYY-MM-DD');
      var end = end.format('YYYY-MM-DD');
      var dates = start + ' - ' + end;
      $('#dates-id').val(dates);
      clickSubmit();
    });
  }

  $('#product-search-button').on('click', function () {
    let value = $('#search').val();

    if (value == '') {
      alert('Please enter something.');
    } else {
      $('#keyword-id').val(value);
      clickSubmit();
    }
  });

  $(".product_short").selectmenu({
    change: function (event, ui) {
      if ($(this).val().length == 0) {
        $(this).remove();
      }
      $('#shortForm').submit();
    }
  });

});
