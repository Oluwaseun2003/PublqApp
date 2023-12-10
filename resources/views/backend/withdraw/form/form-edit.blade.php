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
                <a href="#">{{ __('Withdraw Payment Method Option') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Form Builder') }}</a>
            </li>
        </ul>
    </div>


    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <div class="row">
                    <div class="col-lg-6">
                        {{ __('Edit Input') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row" id="app">

                <div class="col-lg-6 offset-lg-3">
                    <form id="ajaxForm" action="{{ route('admin.withdraw_payment_method.update_input') }}" method="post"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="input_id" value="{{ $input->id }}">
                        <input type="hidden" name="type" value="{{ $input->type }}">

                        <div class="form-group">
                            <label>{{ __('Required') }}</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="required" value="1" class="selectgroup-input"
                                        {{ $input->required == 1 ? 'checked' : '' }}>
                                    <span class="selectgroup-button">{{ __('Yes') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="required" value="0" class="selectgroup-input"
                                        {{ $input->required == 0 ? 'checked' : '' }}>
                                    <span class="selectgroup-button">{{ __('No') }}</span>
                                </label>
                            </div>
                            <p id="err_required" class="mb-0 text-danger em"></p>
                        </div>


                        <div class="form-group">
                            <label for=""><strong>{{ __('Label Name') }}</strong></label>
                            <div class="">
                                <input type="text" class="form-control" name="label" value="{{ $input->label }}"
                                    placeholder="Enter Label Name">
                            </div>
                            <p id="err_label" class="mb-0 text-danger em"></p>
                        </div>


                        @if ($input->type != 3)
                            <div class="form-group">
                                <label for=""><strong>{{ __('Placeholder') }}</strong></label>
                                <div class="">
                                    <input type="text" class="form-control" name="placeholder"
                                        value="{{ $input->placeholder }}" placeholder="Enter Placeholder">
                                </div>
                                <p id="err_placeholder" class="mb-0 text-danger em"></p>
                            </div>
                        @endif

                        @if ($input->type == 2 || $input->type == 3)
                            <div class="form-group">
                                <label for=""><strong>{{ __('Options') }}</strong></label>
                                <div class="row mb-2 counterrow" v-for="n in counter" :id="'counterrow' + n">
                                    <div class="col-md-11">
                                        <input class="form-control optionin" type="text" name="options[]"
                                            :value="names[n - 1]" placeholder="Option label">
                                    </div>

                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger text-white" @click="removeOption(n)"><i
                                                class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success btn-sm text-white" @click="addOption()"><i
                                        class="fa fa-plus"></i> {{ __('Add Option') }}</button>
                                <p id="err_options" class="mb-2 text-danger em"></p>
                                <p id="err_options.3" class="mb-2 text-danger em"></p>
                            </div>
                        @endif


                        <div class="text-center form-group">
                            <button id="submitBtn" type="submit" class="btn btn-primary">{{ __('UPDATE FIELD') }}</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/vue/vue.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vue/axios.js') }}"></script>
@endsection


@section('vuescripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                counter: parseInt('{{ $counter }}'),
                names: []
            },
            created() {
                $.get("{{ route('admin.withdraw_payment_method.options', $input->id) }}", (data) => {
                    for (var i = 0; i < data.length; i++) {
                        this.names.push(data[i].name);
                    }
                });
            },
            methods: {
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
