"use strict";
// 06. Price Range Fliter jQuery UI
if ($('.price-slider-range').length) {
  $(".price-slider-range").slider({
    range: true,
    min: min_price,
    max: max_price,
    values: [curr_min, curr_max],
    slide: function (event, ui) {
      if (position == 'left') {
        $("#price").val(symbol + ui.values[0] + " - " + symbol + ui.values[1]);
      } else {
        $("#price").val(ui.values[0] + symbol + " - " + ui.values[1] + symbol);
      }

      $('#min-id').val(ui.values[0]);
      $('#max-id').val(ui.values[1]);
    }
  });
  if (position == 'left') {
    $("#price").val(symbol + $(".price-slider-range").slider("values", 0) +
      " - " + symbol + $(".price-slider-range").slider("values", 1));
  } else {
    $("#price").val($(".price-slider-range").slider("values", 0) + symbol +
      " - " + symbol + $(".price-slider-range").slider("values", 1));
  }
}
