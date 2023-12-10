<div class="col-lg-5">
  <table class="table table-striped border">
    <thead>
      <tr>
        <th scope="col">{{ __('BB Code') }}</th>
        <th scope="col">{{ __('Meaning') }}</th>
      </tr>
    </thead>
    <tbody>
      @if ($templateInfo->mail_type == 'verify_email')
        <tr>
          <td>{username}</td>
          <td scope="row">{{ __('Username of User') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'verify_email')
        <tr>
          <td>{verification_link}</td>
          <td scope="row">{{ __('Email Verification Link') }}</td>
        </tr>
      @endif

      @if (
          $templateInfo->mail_type == 'reset_password' ||
              $templateInfo->mail_type == 'event_booking' ||
              $templateInfo->mail_type == 'event_booking_approved' ||
              $templateInfo->mail_type == 'event_booking_rejected' ||
              $templateInfo->mail_type == 'product_shipping' || $templateInfo->mail_type == 'product_order')
        <tr>
          <td>{customer_name}</td>
          <td scope="row">{{ __('Name of The Customer') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'reset_password')
        <tr>
          <td>{password_reset_link}</td>
          <td scope="row">{{ __('Password Reset Link') }}</td>
        </tr>
      @endif

      @if (
          $templateInfo->mail_type == 'event_booking' ||
              $templateInfo->mail_type == 'event_booking_approved' ||
              $templateInfo->mail_type == 'event_booking_rejected')
        <tr>
          <td>{order_id}</td>
          <td scope="row">{{ __('Order Id of Event Booking') }}</td>
        </tr>
      @endif

      @if (
          $templateInfo->mail_type == 'event_booking' ||
              $templateInfo->mail_type == 'event_booking_approved' ||
              $templateInfo->mail_type == 'event_booking_rejected')
        <tr>
          <td>{title}</td>
          <td scope="row">{{ __('Event Title') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'balance_subtract' || $templateInfo->mail_type == 'balance_add')
        <tr>
          <td>{username}</td>
          <td scope="row">{{ __('Username of Organizer') }}</td>
        </tr>
      @endif

      @if (
          $templateInfo->mail_type == 'event_booking' ||
              $templateInfo->mail_type == 'withdraw_approve' ||
              $templateInfo->mail_type == 'balance_add' ||
              $templateInfo->mail_type == 'balance_subtract')
        <tr>
          <td>{transaction_id}</td>
          <td>{{ __('Transaction Id') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'withdraw_approve')
        <tr>
          <td>{withdraw_amount}</td>
          <td scope="row">{{ __('Total Withdraw Amount') }}</td>
        </tr>
        <tr>
          <td>{charge}</td>
          <td scope="row">{{ __('Total Charge of Withdraw') }}</td>
        </tr>
        <tr>
          <td>{payable_amount}</td>
          <td scope="row">{{ __('Total Payable Amount') }}</td>
        </tr>

        <tr>
          <td>{withdraw_method}</td>
          <td scope="row">{{ __('Method Name of Withdraw') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'withdraw_approve' || $templateInfo->mail_type == 'withdraw_rejected')
        <tr>
          <td>{organizer_username}</td>
          <td scope="row">{{ __('Username of the vendor') }}</td>
        </tr>
        <tr>
          <td>{withdraw_id}</td>
          <td scope="row">{{ __('Withdraw Id') }}</td>
        </tr>
      @endif
      @if (
          $templateInfo->mail_type == 'withdraw_approve' ||
              $templateInfo->mail_type == 'withdraw_rejected' ||
              $templateInfo->mail_type == 'balance_add' ||
              $templateInfo->mail_type == 'balance_subtract')
        <tr>
          <td>{current_balance}</td>
          <td scope="row">{{ __('Current Balance of Organizer') }}</td>
        </tr>
      @endif

      @if (
          $templateInfo->mail_type == 'product_shipping')
        <tr>
          <td>{status}</td>
          <td scope="row">{{ __('Product Shipping Status') }}</td>
        </tr>
      @endif
      @if (
          $templateInfo->mail_type == 'product_shipping' || $templateInfo->mail_type == 'product_order')
        <tr>
          <td>{order_id}</td>
          <td scope="row">{{ __('Product Order Id') }}</td>
        </tr>
      @endif

      <tr>
        <td>{website_title}</td>
        <td scope="row">{{ __('Website Title') }}</td>
      </tr>
    </tbody>
  </table>
</div>
