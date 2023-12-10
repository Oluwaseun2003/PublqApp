<div class="modal fade" id="editproductCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Event Category') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form" action="{{ route('admin.shop_management.update_category') }}" method="post">

          @method('PUT')
          @csrf
          <input type="hidden" id="in_id" name="id">

          <div class="form-group">
            <label for="">{{ __('Category Name') . '*' }}</label>
            <input type="text" id="in_name" class="form-control" name="name" placeholder="{{ __('Enter Category Name') }}">
            <p id="editErr_name" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Category Status') . '*' }}</label>
            <select name="status" id="in_status" class="form-control">
              <option disabled>{{ __('Select a Status') }}</option>
              <option value="1">{{ __('Active') }}</option>
              <option value="0">{{ __('Deactive') }}</option>
            </select>
            <p id="editErr_status" class="mt-1 mb-0 text-danger em"></p>
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
