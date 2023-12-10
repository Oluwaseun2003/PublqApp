<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('Verify your bookings') }}</title>
  <link rel="manifest" crossorigin="use-credentials" href="{{ asset('pwa_manifest.json') }}" />
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/admin/img/' . $websiteInfo->favicon) }}">
  <link href="{{ asset('assets/pwa_scanner/bootstrap.min.css') }}" rel="stylesheet">
  <script>
    let url = "{{ route('check-qrcode') }}";
    let scanner_img = "{{ asset('assets/pwa_scanner/scanner.gif') }}";
  </script>
  <script src="{{ asset('assets/pwa_scanner/html5-qrcode.min.js') }}"></script>
  <script src="{{ asset('assets/pwa_scanner/pwa.js') }}" defer></script>
  <script src="{{ asset('assets/pwa_scanner/jquery.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('assets/front/css/toastr.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/pwa_scanner/style.css') }}">
</head>

<body>
  <div>
    <div class="container">
      <div class="row">
        <div class="col-12 mb-5">
          <div id="reader" class="mx-auto"></div>
        </div>
        <div class="col-lg-12">
          <div class="alert" id="alert">

          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- sweet alert --}}
  <script type="text/javascript" src="{{ asset('assets/admin/js/sweetalert.min.js') }}"></script>


  <script src="{{ asset('assets/pwa_scanner/script.js') }}"></script>
</body>

</html>
