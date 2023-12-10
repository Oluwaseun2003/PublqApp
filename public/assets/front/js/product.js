"use strict";
// 06. Price Range Fliter jQuery UI
if ($('.price-slider-range').length) {
  $(".price-slider-range").slider({
    range: true,
    min: min_price,
    max: max_price,
    values: [curr_min, curr_max],
    slide: function (event, ui) {
      $("#price").val("$ " + ui.values[0] + " - $ " + ui.values[1]);
      $('#min-id').val(ui.values[0]);
      $('#max-id').val(ui.values[1]);
    }
  });
  $("#price").val("$ " + $(".price-slider-range").slider("values", 0) +
    " - $ " + $(".price-slider-range").slider("values", 1));
}
