<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Event Category') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="modalForm" class="modal-form create" action="{{ route('admin.event_management.store_category') }}"
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
              <p id="err_image" class="mt-1 mb-0 text-danger em"></p>
            </div>
          </div>

          <div class="form-group">
            <label for="">{{ __('Name') . '*' }}</label>
            <input type="text" class="form-control" name="name" placeholder="Enter Category Name">
            <p id="err_name" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Status') . '*' }}</label>
            <select name="status" class="form-control">
              <option selected disabled>{{ __('Select a Status') }}</option>
              <option value="1">{{ __('Active') }}</option>
              <option value="0">{{ __('Deactive') }}</option>
            </select>
            <p id="err_status" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Featured') . '*' }}</label>
            <select name="is_featured" class="form-control">
              <option selected disabled>{{ __('Select featured status') }}</option>
              <option value="yes">{{ __('YES') }}</option>
              <option value="no">{{ __('NO') }}</option>
            </select>
            <p id="err_is_featured" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Serial Number') . '*' }}</label>
            <input type="number" class="form-control ltr" name="serial_number"
              placeholder="Enter Category Serial Number">
            <p id="err_serial_number" class="mt-1 mb-0 text-danger em"></p>
            <p class="text-warning mt-2 mb-0">
              <small>{{ __('The higher the serial number is, the later the category will be shown.') }}</small>
            </p>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          {{ __('Close') }}
        </button>
        <button id="modalSubmit" type="button" class="btn btn-primary btn-sm">
          {{ __('Save') }}
        </button>
      </div>
    </div>
  </div>
</div>
