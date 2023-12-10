<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Testimonial') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form" action="{{ route('admin.home_page.update_testimonial') }}"
          method="post" enctype="multipart/form-data">

          @csrf

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
            <label for="">{{ __('Name') . '*' }}</label>
            <input type="text" class="form-control" name="name" placeholder="{{ __('Enter Client Name') }}" id="in_name">
            <p id="editErr_name" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Occupation') . '*' }}</label>
            <input type="text" class="form-control" name="occupation" placeholder="{{ __('Enter Client Occupation') }}"
              id="in_occupation">
            <p id="editErr_occupation" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Rating') . '*' }}</label>
            <input type="number" class="form-control" name="rating" placeholder="{{ __('Enter Rating') }}" id="in_rating">
            <p id="editErr_rating" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Comment') . '*' }}</label>
            <textarea class="form-control" name="comment" placeholder="{{ __('Enter Client Comment') }}" rows="5" id="in_comment"></textarea>
            <p id="editErr_comment" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Serial Number') . '*' }}</label>
            <input type="number" class="form-control ltr" name="serial_number" placeholder="{{ __('Enter Serial Number') }}"
              id="in_serial_number">
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
