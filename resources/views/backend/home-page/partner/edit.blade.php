<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Partner') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form" action="{{ route('admin.home_page.update_partner') }}" method="post" enctype="multipart/form-data">

          @csrf
          @method('PUT')
          <input type="hidden" id="in_id" name="id">

          <div class="form-group">
            <label for="">{{ __('Image') . '*' }}</label>
            <br>
            <div class="thumb-preview">
              <img src="" alt="client" class="in_image uploaded-img">
            </div>

            <div class="mt-3">
              <div role="button" class="btn btn-primary btn-sm upload-btn">
                {{ __('Choose Image') }}
                <input type="file" class="img-input" name="image">
              </div>
            </div>
            <p class="mt-1 mb-0 text-danger" id="editErr_image"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('URL') . '*' }}</label>
            <input type="url" class="form-control" name="url" placeholder="{{ __('Enter Url') }}" id="in_url">
            <p id="editErr_url" class="mt-1 mb-0 text-danger em"></p>
          </div>
          <div class="form-group">
            <label for="">{{ __('Serial Number') . '*' }}</label>
            <input type="number" class="form-control ltr" name="serial_number" placeholder="{{ __('Enter Serial Number') }}" id="in_serial_number">
            <p id="editErr_serial_number" class="mt-1 mb-0 text-danger em"></p>
            <p class="text-warning mt-2 mb-0">
              <small>{{ __('The higher the serial number is, the later the testimonial will be shown') }}</small>
            </p>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          {{ __('Close') }}
        </button>
        <button id="updateBtn" type="button" class="btn btn-primary btn-sm">
          {{ __('Update') }}
        </button>
      </div>
    </div>
  </div>
</div>
