$(document).on('change', '#zip_file', function () {
  var formdata = new FormData();
  var file = event.target.files[0];
  var name = event.target.files[0].name;
  formdata.append('file', file);
  $.ajax({
    url: $(this).attr('data-href'),
    type: 'post',
    data: formdata,
    xhr: function () {
      var appXhr = $.ajaxSettings.xhr();
      if (appXhr.upload) {
        if ('#zip_file') {
          appXhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable) {
              currentMainProgress = (e.loaded / e.total) * 100;
              currentMainProgress = parseInt(currentMainProgress);
              $(".progress").removeClass('d-none');
              $(".progress-bar").html(currentMainProgress + '%');
              $(".progress-bar").width(currentMainProgress + '%');
              if (currentMainProgress == 100)
                $(".progress-bar").addClass('bg-success');
            }
            $('.show-name small').text(name);
          }, false);
        }
      }

      return appXhr;
    },
    success: function (data) {
      if (data.errors) {
        $(".progress").addClass('d-none');
        $('#errfile').text(data.errors.file[0]).removeClass('d-none');
      } else {
        $('#errfile').text('').addClass('d-none');
      }
    },


    contentType: false,
    processData: false
  });

});
