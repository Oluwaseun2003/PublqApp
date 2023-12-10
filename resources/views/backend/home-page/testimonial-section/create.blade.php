<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Testimonial') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxForm" class="modal-form create"
          action="{{ route('admin.home_page.store_testimonial', ['language' => request()->input('language')]) }}"
          method="post" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label for="">{{ __('Language') . '*' }}</label>
            <select name="language_id" class="form-control">
              <option selected disabled>{{ __('Select a Language') }}</option>
              @foreach ($langs as $lang)
                <option value="{{ $lang->id }}">{{ $lang->name }}</option>
              @endforeach
            </select>
            <p id="err_language_id" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Image') . '*' }}</label>
            <br>
            <div class="thumb-preview">
              <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="uploaded-img">
            </div>

            <div class="mt-3">
              <div role="button" class="btn btn-primary btn-sm upload-btn">
                {{ __('Choose Image') }}
                <input type="file" class="img-input" name="image">
              </div>
            </div>
            <p id="err_image" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Name') . '*' }}</label>
            <input type="text" class="form-control" name="name" placeholder="{{ __('Enter Client Name') }}">
            <p id="err_name" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Occupation') . '*' }}</label>
            <input type="text" class="form-control" name="occupation" placeholder="{{ __('Enter Client Occupation') }}">
            <p id="err_occupation" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Rating') . '*' }}</label>
            <input type="number" class="form-control" name="rating" placeholder="{{ __('Enter Rating') }}">
            <p id="err_rating" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Comment') . '*' }}</label>
            <textarea class="form-control" name="comment" placeholder="{{ __('Enter Client Comment') }}" rows="5"></textarea>
            <p id="err_comment" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Serial Number') . '*' }}</label>
            <input type="number" class="form-control ltr" name="serial_number" placeholder="{{ __('Enter Serial Number') }}">
            <p id="err_serial_number" class="mt-1 mb-0 text-danger em"></p>
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
        <button id="submitBtn" type="button" class="btn btn-primary btn-sm">
          {{ __('Save') }}
        </button>
      </div>
    </div>
  </div>
</div>
