$(document).ready(function () {
  'use strict';

  // search blog by category
  $('ul.blog-categories li a').on('click', function (e) {
    e.preventDefault();

    let value = $(this).data('category_id');

    $('#categoryKey').val(value);
    $('#submitBtn').trigger('click');
  });

  if (typeof shortName !== 'undefined') {
    // disqus init
    let d = document, s = d.createElement('script');
    s.src = `https://${shortName}.disqus.com/embed.js`;
    s.setAttribute('data-timestamp', +new Date());
    (d.head || d.body).appendChild(s);
  }
});
