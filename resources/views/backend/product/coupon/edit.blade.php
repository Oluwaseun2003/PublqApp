<div class="modal fade" id="editshippingCharge" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Coupon') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="ajaxEditForm" class="modal-form" action="{{ route('admin.shop_management.update_coupon') }}"
                    method="post">

                    @method('PUT')
                    @csrf
                    <input type="hidden" id="in_id" name="id">

                    <div class="row no-gutters">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Name') . '*' }}</label>
                                <input type="text" id="in_name" class="form-control" name="name"
                                    placeholder="{{ __('Enter Name') }}">
                                <p id="editErr_name" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Code') . '*' }}</label>
                                <input type="text" id="in_code" class="form-control" name="code"
                                    placeholder="{{ __('Enter Code') }}">
                                <p id="editErr_code" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Type') . '*' }}</label>
                                <select name="type" id="in_type" class="form-control">
                                    <option value="percentage">{{ __('Percentage') }}</option>
                                    <option value="fixed">{{ __('Fixed') }}</option>
                                </select>
                                <p id="editErr_type" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Value') . '*' }}</label>
                                <input type="text" id="in_value" class="form-control" name="value"
                                    placeholder="{{ __('Enter Value') }}">
                                <p id="editErr_value" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Start Date') . '*' }}</label>
                                <input type="text" id="in_start_date" class="form-control datepicker"
                                    name="start_date" placeholder="{{ __('Enter Start Date') }}">
                                <p id="editErr_start_date" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('End Date') . '*' }}</label>
                                <input type="text" id="in_end_date" class="form-control datepicker" name="end_date"
                                    placeholder="{{ __('Enter End Date') }}">
                                <p id="editErr_end_date" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ __('Minimum Spend') . '*' }}</label>
                                <input type="text" id="in_minimum_spend" class="form-control" name="minimum_spend"
                                    placeholder="{{ __('Enter Minimum Spend') }}">
                                <p class="mb-0 text-warning">
                                    {{ __('Keep it blank, if you do not want to keep any minimum spend limit') }}</p>
                                <p id="editErr_minimum_spend" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>
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
