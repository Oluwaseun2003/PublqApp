Dropzone.options.fileDropzone = {
  paramName: 'file',
  acceptedFiles: '.txt, .doc, .docx, .pdf, .zip',
  url: fileUpUrl,
  method: 'post',
  success: function (file, response) {
    // remove error message if exist
    $('#err_file').text('');

    $('#file-original-name').append(`<input type="hidden" id="file-org-${response.originalName}" name="file_org[]" value="${response.originalName}">`);

    $('#file-unique-name').append(`<input type="hidden" id="file-unq-${response.uniqueName}" name="file_unq[]" value="${response.uniqueName}">`);

    // create remove button
    const rmvBtn = Dropzone.createElement("<button class='rmv-btn'><i class='fa fa-times'></i></button>");

    // capture the dropzone instance as closure
    let _this = this;

    // bind an event to the remove button
    rmvBtn.addEventListener('click', function (event) {
      // make sure the button click event doesn't submit the form
      event.preventDefault();
      event.stopPropagation();

      // remove file from dropzone preview
      _this.removeFile(file);

      // remove file from storage
      rmvFile(response.originalName, response.uniqueName);
    });

    // add the remove button to the file preview element
    file.previewElement.appendChild(rmvBtn);
  },
  error: function (file, message) {
    $('#err_file').text(message.error.file[0]);

    // create remove button
    const rmvBtn = Dropzone.createElement("<button class='rmv-btn'><i class='fa fa-times'></i></button>");

    // capture the dropzone instance as closure
    let _this = this;

    // bind an event to the remove button
    rmvBtn.addEventListener('click', function (event) {
      // make sure the button click event doesn't submit the form
      event.preventDefault();
      event.stopPropagation();

      // remove file from dropzone preview
      _this.removeFile(file);
    });

    // add the remove button to the file preview element
    file.previewElement.appendChild(rmvBtn);
  }
};

function rmvFile(orgName, unqName) {
  $.ajax({
    url: fileRmvUrl,
    type: 'POST',
    data: { 'title': unqName },
    success: function (response) {
      const orgEle = document.getElementById('file-org-' + orgName);
      orgEle.remove();

      const unqEle = document.getElementById('file-unq-' + unqName);
      unqEle.remove();
    },
    error: function (response) {
    }
  });
}
