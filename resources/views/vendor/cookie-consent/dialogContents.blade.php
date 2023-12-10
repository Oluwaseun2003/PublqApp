<div class="js-cookie-consent cookie-consent">
  <div class="container">
    <div class="cookie-container">
      <span class="cookie-consent__message">
        {!! $cookieAlertInfo->cookie_alert_text !!}
      </span>

      <button class="js-cookie-consent-agree cookie-consent__agree">
        {{ $cookieAlertInfo->cookie_alert_btn_text }}
      </button>
    </div>
  </div>
</div>
