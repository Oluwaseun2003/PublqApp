    <!-- Jquery -->
    <script>
      var baseUrl = "{{ url('/') }}";
    </script>
    <script src="{{ asset('assets/front/js/jquery.min.js') }}"></script>
    <!-- Popper -->
    <script src="{{ asset('assets/front/js/popper.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('assets/front/js/bootstrap.4.5.3.min.js') }}"></script>
    <!-- jQuery UI js -->
    <script src="{{ asset('assets/front/js/jquery-ui.min.js') }}"></script>
    <!-- Isotope -->
    <script src="{{ asset('assets/front/js/isotope.pkgd.min.js') }}"></script>
    <!-- Magific Popup -->
    <script src="{{ asset('assets/front/js/jquery.magnific-popup.min.js') }}"></script>
    <!-- Image Loaded -->
    <script src="{{ asset('assets/front/js/imagesloaded.pkgd.min.js') }}"></script>
    <!-- Slick Slider -->
    <script src="{{ asset('assets/front/js/slick.min.js') }}"></script>
    <!-- Main JS -->
    <script src="{{ asset('assets/front/js/vanilla-lazyload.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/jquery-syotimer.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/script.js') }}"></script>
    <script src="{{ asset('assets/admin/js/event.js') }}"></script>
    <script src="{{ asset('assets/front/js/toastr.js') }}"></script>
    <script src="{{ asset('assets/front/js/cart.js') }}"></script>
    <script src="{{ asset('assets/front/js/pwa.js') }}" defer></script>

    <script>
      @if (Session::has('message'))

        var type = "{{ Session::get('alert-type') }}";
        if (type) {
          type = type
        } else {
          var type = "{{ Session::get('alert-type', 'info') }}";
        }
        switch (type) {
          case 'info':
            toastr.options = {
              "closeButton": true,
              "progressBar": true,
              "timeOut": 10000,
              "extendedTimeOut": 10000,
              "positionClass": "toast-top-right",
            }
            toastr.info("{{ Session::get('message') }}");
            break;
          case 'success':
            toastr.options = {
              "closeButton": true,
              "progressBar": true,
              "timeOut ": 10000,
              "extendedTimeOut": 10000,
              "positionClass": "toast-top-right",
            }
            toastr.success("{{ Session::get('message') }}");
            break;
          case 'warning':
            toastr.options = {
              "closeButton": true,
              "progressBar": true,
              "timeOut ": 10000,
              "extendedTimeOut": 10000,
              "positionClass": "toast-top-right",
            }
            toastr.warning("{{ Session::get('message') }}");
            break;
          case 'error':
            toastr.options = {
              "closeButton": true,
              "progressBar": true,
              "timeOut ": 10000,
              "extendedTimeOut": 10000,
              "positionClass": "toast-top-right",
            }
            toastr.error("{{ Session::get('message') }}");
            break;
        }
      @endif
    </script>
