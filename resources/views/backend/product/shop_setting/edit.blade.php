<div class="modal fade" id="editshippingCharge" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Shipping Charge') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form" action="{{ route('admin.shop_management.update_shipping') }}" method="post">

          @method('PUT')
          @csrf
          <input type="hidden" id="in_id" name="id">

          <div class="form-group">
            <label for="">{{ __('Title') . '*' }}</label>
            <input type="text" id="in_title" class="form-control" name="title" placeholder="{{ __('Enter Title') }}">
            <p id="editErr_title" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Text') . '*' }}</label>
            <input type="text" id="in_text" class="form-control" name="text" placeholder="{{ __('Enter Tex') }}t">
            <p id="editErr_text" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Charge') . '*' }}</label>
            <input type="text" id="in_charge" class="form-control" name="charge" placeholder="{{ __('Enter Charge') }}">
            <p id="editErr_charge" class="mt-1 mb-0 text-danger em"></p>
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
