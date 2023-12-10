<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Feature') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form" action="{{ route('admin.home_page.update_feature') }}" method="post">
          
          @method('PUT')
          @csrf
          <input type="hidden" name="id" id="in_id">
          <input type="hidden" name="theme_version" value="{{ $themeInfo->theme_version }}">

          <div class="form-group">
            <label>{{ __('Background Color') . '*' }}</label>
            <input class="jscolor form-control ltr" name="background_color" id="in_background_color">
            <p id="editErr_background_color" class="mt-1 mb-0 text-danger em"></p>
          </div>

          @if ($themeInfo->theme_version == 3)
            <div class="form-group">
              <label for="">{{ __('Icon') . '*' }}</label>
              <div class="btn-group d-block">
                <button type="button" class="btn btn-primary iconpicker-component edit-iconpicker-component">
                  <i class="" id="in_icon"></i>
                </button>
                <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle" data-selected="fa-car" data-toggle="dropdown"></button>
                <div class="dropdown-menu"></div>
              </div>

              <input type="hidden" id="editInputIcon" name="icon">
              <p id="editErr_icon" class="mt-1 mb-0 text-danger em"></p>

              <div class="text-warning mt-2">
                <small>{{ __('Click on the dropdown icon to select an icon') }}</small>
              </div>
            </div>
          @endif

          <div class="form-group">
            <label for="">{{ __('Title') . '*' }}</label>
            <input type="text" class="form-control" name="title" placeholder="{{ __('Enter Feature Title') }}" id="in_title">
            <p id="editErr_title" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Text') . '*' }}</label>
            <textarea class="form-control" name="text" placeholder="{{ __('Enter Feature Text') }}" id="in_text" rows="5"></textarea>
            <p id="editErr_text" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Serial Number') . '*' }}</label>
            <input type="number" class="form-control ltr" name="serial_number" placeholder="{{ __('Enter Feature Serial Number') }}" id="in_serial_number">
            <p id="editErr_serial_number" class="mt-1 mb-0 text-danger em"></p>
            <p class="text-warning mt-2 mb-0">
              <small>{{ __('The higher the serial number is, the later the feature will be shown') }}</small>
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
