<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Social Media') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form" action="{{ route('admin.basic_settings.update_social_media') }}" method="post">
          
          @method('PUT')
          @csrf
          <input type="hidden" id="in_id" name="id">

          <div class="form-group">
            <label for="">{{ __('Social Media Icon') . '*' }}</label>
            <div class="btn-group d-block">
              <button type="button" class="btn btn-primary iconpicker-component edit-iconpicker-component">
                <i class="" id="in_icon"></i>
              </button>
              <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle" data-selected="fa-car" data-toggle="dropdown"></button>
              <div class="dropdown-menu"></div>
            </div>

            <input type="hidden" id="editInputIcon" name="icon">

            <div class="text-warning mt-2">
              <small>{{ __('Click on the dropdown icon to select an icon') }}</small>
            </div>
          </div>

          <div class="form-group">
            <label for="">{{ __('Social Media URL') . '*' }}</label>
            <input type="url" id="in_url" class="form-control" name="url" placeholder="{{ __('Enter URL of Social Media Account') }}">
            <p id="editErr_url" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Social Media Serial Number') . '*' }}</label>
            <input type="number" id="in_serial_number" class="form-control" name="serial_number" placeholder="{{ __('Enter Serial Number') }}">
            <p id="editErr_serial_number" class="mt-1 mb-0 text-danger em"></p>
            <p class="text-warning mt-2 mb-0">
              <small>{{ __('The higher the serial number is, the later the social media will be shown') }}</small>
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
