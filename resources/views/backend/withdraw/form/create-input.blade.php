<div class="card">
  <div class="card-header">
    <div class="card-title">{{ __('Create Input') }}</div>
  </div>

  <form id="ajaxForm" action="{{ route('admin.withdraw_payment_method.store_input') }}" method="post"
    enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="withdraw_payment_method_id" value="{{ request()->input('id') }}">
    <div class="form-group">
      <label for=""><strong>{{ __('Field Type') }}</strong></label>
      <div class="">
        <div class="form-check form-check-inline">
          <input name="type" class="form-check-input" type="radio" id="inlineRadio1" value="1" v-model="type"
            @change="typeChange()">
          <label class="form-check-label" for="inlineRadio1">{{ __('Text Field') }}</label>
        </div>
        <div class="form-check form-check-inline">
          <input name="type" class="form-check-input" type="radio" id="inlineRadio7" value="7" v-model="type"
            @change="typeChange()">
          <label class="form-check-label" for="inlineRadio7">{{ __('Number Field') }}</label>
        </div>
        <div class="form-check form-check-inline">
          <input name="type" class="form-check-input" type="radio" id="inlineRadio2" value="2" v-model="type"
            @change="typeChange()">
          <label class="form-check-label" for="inlineRadio2">{{ __('Select') }}</label>
        </div>
        <div class="form-check form-check-inline">
          <input name="type" class="form-check-input" type="radio" id="inlineRadio3" value="3" v-model="type"
            @change="typeChange()">
          <label class="form-check-label" for="inlineRadio3">{{ __('Checkbox') }}</label>
        </div>
        <div class="form-check form-check-inline">
          <input name="type" class="form-check-input" type="radio" id="inlineRadio4" value="4" v-model="type"
            @change="typeChange()">
          <label class="form-check-label" for="inlineRadio4">{{ __('Textarea') }}</label>
        </div>
        <div class="form-check form-check-inline">
          <input name="type" class="form-check-input" type="radio" id="inlineRadio5" value="5" v-model="type"
            @change="typeChange()">
          <label class="form-check-label" for="inlineRadio5">{{ __('Datepicker') }}</label>
        </div>
        <div class="form-check form-check-inline">
          <input name="type" class="form-check-input" type="radio" id="inlineRadio6" value="6" v-model="type"
            @change="typeChange()">
          <label class="form-check-label" for="inlineRadio6">{{ __('Timepicker') }}</label>
        </div>
      </div>
      <p id="errtype" class="mb-0 text-danger em"></p>
    </div>

    <div class="form-group">
      <label>{{ __('Required') }}</label>
      <div class="selectgroup w-100">
        <label class="selectgroup-item">
          <input type="radio" name="required" value="1" class="selectgroup-input" checked>
          <span class="selectgroup-button">{{ __('Yes') }}</span>
        </label>
        <label class="selectgroup-item">
          <input type="radio" name="required" value="0" class="selectgroup-input">
          <span class="selectgroup-button">{{ __('No') }}</span>
        </label>
      </div>
      <p id="err_required" class="mb-0 text-danger em"></p>
    </div>

    <div class="form-group">
      <label for=""><strong>{{ __('Label Name') }}</strong></label>
      <div class="">
        <input type="text" class="form-control" name="label" value="" placeholder="{{ __('Enter Label Name') }}">
      </div>
      <p id="err_label" class="mb-0 text-danger em"></p>
    </div>

    <div class="form-group" v-if="placeholdershow">
      <label for=""><strong>{{ __('Placeholder') }}</strong></label>
      <div class="">
        <input type="text" class="form-control" name="placeholder" value=""
          placeholder="{{ __('Enter Placeholder') }}">
      </div>
      <p id="err_placeholder" class="mb-0 text-danger em"></p>
    </div>


    <div class="form-group" v-if="counter > 0" id="optionarea">
      <label for=""><strong>{{ __('Options') }}</strong></label>
      <div class="row mb-2 counterrow" v-for="n in counter" :id="'counterrow' + n">
        <div class="col-md-10">
          <input type="text" class="form-control" name="options[]" value="" placeholder="{{ __('Option label') }}">
        </div>

        <div class="col-md-1">
          <button type="button" class="btn btn-danger btn-md text-white btn-sm" @click="removeOption(n)"><i
              class="fa fa-times"></i></button>
        </div>
      </div>
      <p id="err_options.0" class="mb-2 text-danger em"></p>
      <button type="button" class="btn btn-success btn-sm text-white" @click="addOption()"><i
          class="fa fa-plus"></i> {{ __('Add Option') }}</button>
    </div>


    <div class="form-group text-center">
      <button id="submitBtn" type="submit" class="btn btn-primary btn-sm">{{ __('ADD FIELD') }}</button>
    </div>
  </form>

</div>
