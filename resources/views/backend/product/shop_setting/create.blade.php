<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Shipping Charge') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="modalForm" class="modal-form create"
                    action="{{ route('admin.shop_management.store_shipping') }}" method="post"
                    enctype="multipart/form-data">
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
                        <label for="">{{ __('Title') . '*' }}</label>
                        <input type="text" class="form-control" name="title" placeholder="{{ __('Enter Title') }}">
                        <p id="err_title" class="mt-1 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ __('Short Text') . '*' }}</label>
                        <input type="text" class="form-control" name="text"
                            placeholder="{{ __('Enter Short Text') }}">
                        <p id="err_text" class="mt-1 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ __('Charge') . ' (' . $settings->base_currency_text . ')*' }}</label>
                        <input type="text" class="form-control" name="charge" placeholder="{{ __('Enter Charge') }}">
                        <p id="err_charge" class="mt-1 mb-0 text-danger em"></p>
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
