@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Form Builder') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a
          href="{{ route('admin.withdraw.payment_method', ['language' => $defaultLang->code]) }}">{{ __('Withdraw payment methods') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ $payment_method->name }}</a>
      </li>

      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Form Builder') }}</a>
      </li>
    </ul>
  </div>

  <div class="row" id="app">
    <div class="col-lg-7">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title">{{ __('Input Fields') }}</div>
            </div>
            <div class="col-lg-4">
              <a class="btn btn-info btn-sm float-right d-inline-block mr-2"
                href="{{ route('admin.withdraw.payment_method', ['language' => $defaultLang->code]) }}">
                <span class="btn-label">
                  <i class="fas fa-backward"></i>
                </span>
                {{ __('Back') }}
              </a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <p class="text-warning mb-0">** {{ __('Do not create') }} <strong
              class="text-danger">{{ __('Withdraw Amount & Additional Reference') }}</strong>
            {{ __('input field, it will be in the Table Reservation form by default') }}.</p>
          <p class="text-warning">** {{ __('Drag & Drop the input fields to change the order number') }}</p>

          <form class="ui-state-default ui-state-disabled">
            <div class="form-group">
              <label for="">{{ __('Withdraw Amount') }} **</label>
              <div class="row">
                <div class="col-md-10">
                  <input class="form-control" type="text" name="" value="" placeholder="{{ __('Withdraw Amount') }}">
                </div>
              </div>
            </div>
          </form>
          <form class="ui-state-default ui-state-disabled">
            <div class="form-group">
              <label for="">{{ __('Additional Reference(Optional)') }} </label>
              <div class="row">
                <div class="col-md-10">
                  <input class="form-control" type="text" name="" value=""
                    placeholder="{{ __('Additional Reference') }}">
                </div>
              </div>
            </div>
          </form>
          @if (count($inputs) > 0)
            <div id="sortable">
              @includeIf('backend.withdraw.form.created-inputs')
            </div>
          @endif

        </div>
      </div>
    </div>

    <div class="col-lg-5">
      @includeIf('backend.withdraw.form.create-input')
    </div>
  </div>
@endsection

@section('script')
  <script src="{{ asset('assets/admin/js/vue/vue.js') }}"></script>
  <script src="{{ asset('assets/admin/js/vue/axios.js') }}"></script>

  <script>
    let url = "{{ route('admin.withdraw_payment_method.order_update') }}";
    $(function() {
      $("#sortable").sortable({
        stop: function(event, ui) {
          $(".request-loader").addClass('show');
          let fd = new FormData();
          $(".ui-state-default.ui-sortable-handle").each(function(index) {
            fd.append('ids[]', $(this).data('id'));
            let order = parseInt(index) + 1
            fd.append('orders[]', order);
          });
          $.ajax({
            url: url,
            method: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            success: function(data) {
              $(".request-loader").removeClass('show');
            }
          })
        }
      });
      $("#sortable").disableSelection();
    });
  </script>
@endsection
@section('vuescripts')
  <script>
    var app = new Vue({
      el: '#app',
      data: {
        type: 1,
        counter: 0,
        placeholdershow: true
      },
      methods: {
        typeChange() {
          if (this.type == 3) {
            this.placeholdershow = false;
          } else {
            this.placeholdershow = true;
          }
          if (this.type == 2 || this.type == 3) {
            this.counter = 1;
          } else {
            this.counter = 0;
          }
        },
        addOption() {
          $("#optionarea").addClass('d-block');
          this.counter++;
        },
        removeOption(n) {
          $("#counterrow" + n).remove();
          if ($(".counterrow").length == 0) {
            this.counter = 0;
          }
        }
      }
    })
  </script>
@endsection
