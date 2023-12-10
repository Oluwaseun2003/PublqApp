<div class="footer-item mt-30">
  <div class="footer-title item-3">
    <i class="fal fa-paper-plane"></i>
    <h4 class="title">{{ __('Contact Us') }}</h4>
  </div>

  <div class="footer-list-area">
    <div class="footer-list d-block d-sm-flex">
      <ul>
        <li><a href="{{ 'mailto:' . $basicInfo->email_address }}"><i class="fal fa-envelope"></i> {{ $basicInfo->email_address }}</a></li>
        <li><a href="{{ 'tel:' . $basicInfo->contact_number }}"><i class="fal fa-phone-office"></i> {{ $basicInfo->contact_number }}</a></li>
        <li><a href="{{ "//maps.google.com/?ll=$basicInfo->latitude,$basicInfo->longitude" }}"><i class="fal fa-map-marker-alt"></i> {{ $basicInfo->address }}</a></li>
      </ul>
    </div>
  </div>
</div>
