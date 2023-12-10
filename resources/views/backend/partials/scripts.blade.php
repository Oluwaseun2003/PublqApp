<script>
  'use strict';

  const baseUrl = "{{ url('/') }}";
  var loadImgs = '';
  var ProductloadImgs = '';
</script>

{{-- core js files --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>

{{-- jQuery ui --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/jquery.ui.touch-punch.min.js') }}"></script>

{{-- jQuery time-picker --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/jquery.timepicker.min.js') }}"></script>

{{-- jQuery scrollbar --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/jquery.scrollbar.min.js') }}"></script>

{{-- bootstrap notify --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/bootstrap-notify.min.js') }}"></script>

{{-- sweet alert --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/sweetalert.min.js') }}"></script>

{{-- bootstrap tags input --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/bootstrap-tagsinput.min.js') }}"></script>

{{-- bootstrap date-picker --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/bootstrap-datepicker.min.js') }}"></script>

{{-- tinymce --}}
<script src="{{ asset('assets/admin/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>


<!-- Select2 JS -->
<script src="{{ asset('assets/admin/js/select2.min.js') }}"></script>

{{-- js color --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/jscolor.min.js') }}"></script>

{{-- fontawesome icon picker js --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/fontawesome-iconpicker.min.js') }}"></script>

{{-- datatables js --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/datatables-1.10.23.min.js') }}"></script>

{{-- datatables bootstrap js --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/datatables.bootstrap4.min.js') }}"></script>

{{-- dropzone js --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/dropzone.min.js') }}"></script>

{{-- highlight js --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/highlight.pack.js') }}"></script>

{{-- atlantis js --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/atlantis.js') }}"></script>

{{-- fonts and icons script --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/webfont.min.js') }}"></script>

<script>
  "use strict";
  WebFont.load({
    google: {
      "families": ["Lato:300,400,700,900"]
    },
    custom: {
      "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands",
        "simple-line-icons"
      ],
      urls: ['{{ asset('assets/admin/css/fonts.min.css') }}']
    },
    active: function() {
      sessionStorage.fonts = true;
    }
  });
</script>

@if (session()->has('success'))
  <script>
    "use strict";
    var content = {};

    content.message = '{{ __(session('success')) }}';
    content.title = 'Success';
    content.icon = 'fa fa-bell';

    $.notify(content, {
      type: 'success',
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      delay: 4000
    });
  </script>
@endif

@if (session()->has('warning'))
  <script>
    "use strict";
    var content = {};

    content.message = '{{ __(session('warning')) }}';
    content.title = 'Warning!';
    content.icon = 'fa fa-bell';

    $.notify(content, {
      type: 'warning',
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      delay: 4000
    });
  </script>
@endif

<script>
  'use strict';
  const account_status = 1;
  const secret_login = 1;
</script>

{{-- admin-main js --}}
<script type="text/javascript" src="{{ asset('assets/admin/js/admin-main.js') }}"></script>

@yield('variables')

@yield('script')
@yield('vuescripts')
