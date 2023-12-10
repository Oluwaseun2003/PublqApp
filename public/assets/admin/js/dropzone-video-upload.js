Dropzone.options.videoDropzone = {
  paramName: 'video',
  acceptedFiles: '.mp4',
  url: vidUpUrl,
  method: 'post',
  success: function (file, response) {
    // remove error message if exist
    $('#err_vid').text('');

    $('#video-original-name').append(`<input type="hidden" id="vid-org-${response.originalName}" name="vid_org[]" value="${response.originalName}">`);

    $('#video-unique-name').append(`<input type="hidden" id="vid-unq-${response.uniqueName}" name="vid_unq[]" value="${response.uniqueName}">`);

    $('#video-duration').append(`<input type="hidden" id="vid-time-${response.originalName}" name="vid_time[]" value="${response.duration}">`);

    // create remove button
    const rmvBtn = Dropzone.createElement("<button class='rmv-btn'><i class='fa fa-times'></i></button>");

    // capture the dropzone instance as closure
    let _this = this;

    // bind an event to the remove button
    rmvBtn.addEventListener('click', function (event) {
      // make sure the button click event doesn't submit the form
      event.preventDefault();
      event.stopPropagation();

      // remove video from dropzone preview
      _this.removeFile(file);

      // remove video from storage
      rmvVid(response.originalName, response.uniqueName);
    });

    // add the remove button to the file preview element
    file.previewElement.appendChild(rmvBtn);
  },
  error: function (file, message) {
    $('#err_vid').text(message.error.video[0]);

    // create remove button
    const rmvBtn = Dropzone.createElement("<button class='rmv-btn'><i class='fa fa-times'></i></button>");

    // capture the dropzone instance as closure
    let _this = this;

    // bind an event to the remove button
    rmvBtn.addEventListener('click', function (event) {
      // make sure the button click event doesn't submit the form
      event.preventDefault();
      event.stopPropagation();

      // remove video from dropzone preview
      _this.removeFile(file);
    });

    // add the remove button to the file preview element
    file.previewElement.appendChild(rmvBtn);
  }
};

function rmvVid(orgName, unqName) {
  $.ajax({
    url: vidRmvUrl,
    type: 'POST',
    data: { 'title': unqName },
    success: function (response) {
      const orgEle = document.getElementById('vid-org-' + orgName);
      orgEle.remove();

      const unqEle = document.getElementById('vid-unq-' + unqName);
      unqEle.remove();

      const vidTime = document.getElementById('vid-time-' + orgName);
      vidTime.remove();
    },
    error: function (response) {
    }
  });
}
