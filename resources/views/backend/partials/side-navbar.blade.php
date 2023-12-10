<div class="sidebar sidebar-style-2"
    data-background-color="{{ $settings->admin_theme_version == 'light' ? 'white' : 'dark2' }}">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    @if (Auth::guard('admin')->user()->image != null)
                        <img src="{{ asset('assets/admin/img/admins/' . Auth::guard('admin')->user()->image) }}"
                            alt="Admin Image" class="avatar-img rounded-circle">
                    @else
                        <img src="{{ asset('assets/admin/img/blank_user.jpg') }}" alt=""
                            class="avatar-img rounded-circle">
                    @endif
                </div>

                <div class="info">
                    <a data-toggle="collapse" href="#adminProfileMenu" aria-expanded="true">
                        <span>
                            {{ Auth::guard('admin')->user()->first_name }}

                            @if (is_null($roleInfo))
                                <span class="user-level">{{ __('Super Admin') }}</span>
                            @else
                                <span class="user-level">{{ $roleInfo->name }}</span>
                            @endif

                            <span class="caret"></span>
                        </span>
                    </a>

                    <div class="clearfix"></div>

                    <div class="collapse in" id="adminProfileMenu">
                        <ul class="nav">
                            <li>
                                <a href="{{ route('admin.edit_profile') }}">
                                    <span class="link-collapse">{{ __('Edit Profile') }}</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.change_password') }}">
                                    <span class="link-collapse">{{ __('Change Password') }}</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.logout') }}">
                                    <span class="link-collapse">{{ __('Logout') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            @php
                if (!is_null($roleInfo)) {
                    $rolePermissions = json_decode($roleInfo->permissions);
                }
            @endphp

            <ul class="nav nav-primary">
                {{-- search --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <form action="" onsubmit="return false">
                            <div class="form-group py-0">
                                <input name="term" type="text" class="form-control sidebar-search ltr"
                                    placeholder="Search Menu Here...">
                            </div>
                        </form>
                    </div>
                </div>

                {{-- dashboard --}}
                <li class="nav-item @if (request()->routeIs('admin.dashboard')) active @endif">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="la flaticon-paint-palette"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>

                {{-- menu builder --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Menu Builder', $rolePermissions)))
                    <li class="nav-item @if (request()->routeIs('admin.menu_builder')) active @endif">
                        <a href="{{ route('admin.menu_builder', ['language' => $defaultLang->code]) }}">
                            <i class="fal fa-bars"></i>
                            <p>{{ __('Menu Builder') }}</p>
                        </a>
                    </li>
                @endif
                {{-- event --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Event Management', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.event_management.categories')) active
            @elseif (request()->routeIs('add.event.event')) active
            @elseif (request()->routeIs('admin.choose-event-type')) active
            @elseif (request()->routeIs('admin.event_management.event')) active
            @elseif (request()->routeIs('admin.event_management.edit_event')) active
            @elseif (request()->routeIs('admin.event.ticket')) active
            @elseif (request()->routeIs('admin.event.add.ticket')) active
            @elseif (request()->routeIs('admin.event.edit.ticket')) active @endif">
                        <a data-toggle="collapse" href="#event">
                            <i class="fal fa-book"></i>
                            <p>{{ __('Event Management') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="event"
                            class="collapse
              @if (request()->routeIs('admin.event_management.categories')) show
              @elseif (request()->routeIs('admin.choose-event-type')) show
              @elseif (request()->routeIs('add.event.event')) show
              @elseif (request()->routeIs('admin.event_management.event')) show
              @elseif (request()->routeIs('admin.event_management.edit_event')) show
              @elseif (request()->routeIs('admin.event.ticket')) show
              @elseif (request()->routeIs('admin.event.add.ticket')) show
              @elseif (request()->routeIs('admin.event.edit.ticket')) show @endif">
                            <ul class="nav nav-collapse">
                                <li
                                    class="{{ request()->routeIs('admin.event_management.categories') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.event_management.categories', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Event Categories') }}</span>
                                    </a>
                                </li>

                                <li class="submenu">
                                    <a data-toggle="collapse" href="#EventsManagement"
                                        aria-expanded="{{ request()->routeIs('admin.choose-event-type') ||
                                        request()->routeIs('add.event.event') ||
                                        request()->routeIs('admin.event_management.event') ||
                                        request()->routeIs('admin.event_management.edit_event') ||
                                        request()->routeIs('admin.event.ticket') ||
                                        request()->routeIs('admin.event.add.ticket') ||
                                        request()->routeIs('admin.event.edit.ticket')
                                            ? 'true'
                                            : 'false' }}">
                                        <span class="sub-item">{{ __('Event Management') }}</span>
                                        <span class="caret"></span>
                                    </a>
                                    <div class="collapse
                    @if (request()->routeIs('admin.choose-event-type')) show
                    @elseif(request()->routeIs('add.event.event')) show
                    @elseif(request()->routeIs('admin.event_management.event')) show
                    @elseif(request()->routeIs('admin.event_management.edit_event')) show
                    @elseif(request()->routeIs('admin.event.ticket')) show
                    @elseif(request()->routeIs('admin.event.add.ticket')) show
                    @elseif(request()->routeIs('admin.event.edit.ticket')) show @endif"
                                        id="EventsManagement">
                                        <ul class="nav nav-collapse subnav">
                                            <li
                                                class=" @if (request()->routeIs('admin.choose-event-type')) active
                        @elseif (request()->routeIs('add.event.event')) active @endif ">
                                                <a
                                                    href="{{ route('admin.choose-event-type', ['language' => $defaultLang->code]) }}">
                                                    <span class="sub-item">{{ __('Add Event') }}</span>
                                                </a>
                                            </li>

                                            <li
                                                class="@if (request()->routeIs('admin.event_management.event') && request()->input('event_type') == '') active
                        @elseif (request()->routeIs('admin.event_management.edit_event') && request()->input('event_type') == '') active 
                        @elseif (request()->routeIs('admin.event.ticket') && request()->input('event_type') == '') active
                        @elseif (request()->routeIs('admin.event.add.ticket') && request()->input('event_type') == '') active
                        @elseif (request()->routeIs('admin.event.edit.ticket') && request()->input('event_type') == '') active @endif">
                                                <a
                                                    href="{{ route('admin.event_management.event', ['language' => $defaultLang->code]) }}">
                                                    <span class="sub-item">{{ __('All Events') }}</span>
                                                </a>
                                            </li>

                                            <li
                                                class="@if (request()->routeIs('admin.event_management.event') && request()->input('event_type') == 'venue') active 
                        @elseif (request()->routeIs('admin.add.event.event') && request()->input('type') == 'venue') active
                        @elseif (request()->routeIs('admin.event.ticket') && request()->input('event_type') == 'venue') active 
                        @elseif (request()->routeIs('admin.event.add.ticket') && request()->input('event_type') == 'venue') active
                        @elseif (request()->routeIs('admin.event.edit.ticket') && request()->input('event_type') == 'venue') active @endif">
                                                <a
                                                    href="{{ route('admin.event_management.event', ['language' => $defaultLang->code, 'event_type' => 'venue']) }}">
                                                    <span class="sub-item">{{ __('Venue Events') }}</span>
                                                </a>
                                            </li>

                                            <li
                                                class="@if (request()->routeIs('admin.event_management.event') && request()->input('event_type') == 'online') active 
                        @elseif (request()->routeIs('admin.add.event.event') && request()->input('type') == 'online') active @endif ">
                                                <a
                                                    href="{{ route('admin.event_management.event', ['language' => $defaultLang->code, 'event_type' => 'online']) }}">
                                                    <span class="sub-item">{{ __('Online Events') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- event booking --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Event Bookings', $rolePermissions)))
                    <li
                        class="nav-item
          @if (request()->routeIs('admin.event.booking')) active
          @elseif (request()->routeIs('admin.event_booking.details')) active  
          @elseif (request()->routeIs('admin.event_management.coupons')) active  
          @elseif (request()->routeIs('admin.event_booking.settings.tax_commission')) active  
          @elseif (request()->routeIs('admin.event_booking.report')) active @endif">
                        <a data-toggle="collapse" href="#event_bookings">
                            <i class="fal fa-users-class"></i>
                            <p>{{ __('Event Bookings') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="event_bookings"
                            class="collapse
            @if (request()->routeIs('admin.event_management.coupons')) show 
            @elseif (request()->routeIs('admin.event.booking')) show 
            @elseif (request()->routeIs('admin.event_booking.details')) show 
            @elseif (request()->routeIs('admin.event_booking.report')) show 
            @elseif (request()->routeIs('admin.event_booking.settings.tax_commission')) show @endif">
                            <ul class="nav nav-collapse">

                                <li class="submenu">
                                    <a data-toggle="collapse" href="#EventsSettings"
                                        aria-expanded="{{ request()->routeIs('admin.event_management.coupons') || request()->routeIs('admin.event_booking.settings.tax_commission') ? 'true' : 'false' }}">
                                        <span class="sub-item">{{ __('Settings') }}</span>
                                        <span class="caret"></span>
                                    </a>
                                    <div class="collapse
                    @if (request()->routeIs('admin.event_management.coupons')) show 
                    @elseif (request()->routeIs('admin.event_booking.settings.tax_commission')) show @endif"
                                        id="EventsSettings">
                                        <ul class="nav nav-collapse subnav">
                                            <li
                                                class="{{ request()->routeIs('admin.event_management.coupons') ? 'active' : '' }}">
                                                <a href="{{ route('admin.event_management.coupons') }}">
                                                    <span class="sub-item">{{ __('Coupons') }}</span>
                                                </a>
                                            </li>
                                            <li
                                                class="{{ request()->routeIs('admin.event_booking.settings.tax_commission') ? 'active' : '' }}">
                                                <a href="{{ route('admin.event_booking.settings.tax_commission') }}">
                                                    <span class="sub-item">{{ __('Tax & Commission') }}</span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </li>

                                <li
                                    class="
                  @if (request()->routeIs('admin.event.booking') && empty(request()->input('status'))) active
                  @elseif (request()->routeIs('admin.event_booking.details')) active @endif">
                                    <a href="{{ route('admin.event.booking') }}">
                                        <span class="sub-item">{{ __('All Bookings') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.event.booking') && request()->input('status') == 'completed' ? 'active' : '' }}">
                                    <a href="{{ route('admin.event.booking', ['status' => 'completed']) }}">
                                        <span class="sub-item">{{ __('Completed Bookings') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.event.booking') && request()->input('status') == 'pending' ? 'active' : '' }}">
                                    <a href="{{ route('admin.event.booking', ['status' => 'pending']) }}">
                                        <span class="sub-item">{{ __('Pending Bookings') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.event.booking') && request()->input('status') == 'rejected' ? 'active' : '' }}">
                                    <a href="{{ route('admin.event.booking', ['status' => 'rejected']) }}">
                                        <span class="sub-item">{{ __('Rejected Bookings') }}</span>
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('admin.event_booking.report') ? 'active' : '' }}">
                                    <a href="{{ route('admin.event_booking.report') }}">
                                        <span class="sub-item">{{ __('Report') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- Withdraw Method --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Withdraw Method', $rolePermissions)))
                    <li
                        class="nav-item
          @if (request()->routeIs('admin.withdraw.payment_method')) active
          @elseif (request()->routeIs('admin.withdraw.payment_method')) active
          @elseif (request()->routeIs('admin.withdraw_payment_method.mange_input')) active
          @elseif (request()->routeIs('admin.withdraw_payment_method.edit_input')) active
          @elseif (request()->routeIs('admin.withdraw.withdraw_request')) active @endif">
                        <a data-toggle="collapse" href="#withdraw_method">
                            <i class="fas fa-credit-card"></i>
                            <p>{{ __('Withdraw Method') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="withdraw_method"
                            class="collapse
            @if (request()->routeIs('admin.withdraw.payment_method')) show
            @elseif (request()->routeIs('admin.withdraw.payment_method')) show
            @elseif (request()->routeIs('admin.withdraw_payment_method.mange_input')) show 
            @elseif (request()->routeIs('admin.withdraw_payment_method.edit_input')) show 
            @elseif (request()->routeIs('admin.withdraw.withdraw_request')) show @endif">
                            <ul class="nav nav-collapse">
                                <li
                                    class="@if (request()->routeIs('admin.withdraw.payment_method')) active 
                  @elseif (request()->routeIs('admin.withdraw_payment_method.mange_input')) active 
                  @elseif (request()->routeIs('admin.withdraw_payment_method.edit_input')) active @endif">
                                    <a
                                        href="{{ route('admin.withdraw.payment_method', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Payment Methods') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.withdraw.withdraw_request') && empty(request()->input('status')) ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.withdraw.withdraw_request', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Withdraw Requests') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- Transaction --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Transaction', $rolePermissions)))
                    <li class="nav-item @if (request()->routeIs('admin.transcation')) active @endif">
                        <a href="{{ route('admin.transcation') }}">
                            <i class="fal fa-exchange-alt"></i>
                            <p>{{ __('Transactions') }}</p>
                        </a>
                    </li>
                @endif

                {{-- organizer --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Organizer Mangement', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.organizer_management.registered_organizer')) active
            @elseif (request()->routeIs('admin.organizer_management.add_organizer')) active
            @elseif (request()->routeIs('admin.organizer_management.organizer_details')) active
            @elseif (request()->routeIs('admin.edit_management.organizer_edit')) active
            @elseif (request()->routeIs('admin.organizer_management.organizer.change_password')) active 
            @elseif (request()->routeIs('admin.organizer_management.settings')) active @endif">
                        <a data-toggle="collapse" href="#organizer">
                            <i class="la flaticon-users"></i>
                            <p>{{ __('Organizers Management') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="organizer"
                            class="collapse
              @if (request()->routeIs('admin.organizer_management.registered_organizer')) show
              @elseif (request()->routeIs('admin.organizer_management.organizer_details')) show
              @elseif (request()->routeIs('admin.edit_management.organizer_edit')) show
              @elseif (request()->routeIs('admin.organizer_management.add_organizer')) show
              @elseif (request()->routeIs('admin.organizer_management.organizer.change_password')) show 
              @elseif (request()->routeIs('admin.organizer_management.settings')) show @endif">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->routeIs('admin.organizer_management.settings')) active @endif">
                                    <a href="{{ route('admin.organizer_management.settings') }}">
                                        <span class="sub-item">{{ __('Settings') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="@if (request()->routeIs('admin.organizer_management.registered_organizer')) active
                  @elseif (request()->routeIs('admin.organizer_management.organizer_details')) active
                  @elseif (request()->routeIs('admin.edit_management.organizer_edit')) active
                  @elseif (request()->routeIs('admin.organizer_management.organizer.change_password')) active @endif">
                                    <a href="{{ route('admin.organizer_management.registered_organizer') }}">
                                        <span class="sub-item">{{ __('Registered Organizers') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->routeIs('admin.organizer_management.add_organizer')) active @endif">
                                    <a href="{{ route('admin.organizer_management.add_organizer') }}">
                                        <span class="sub-item">{{ __('Add Organizer') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- customer --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Customer Management', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.organizer_management.registered_customer')) active
            @elseif (request()->routeIs('admin.customer_management.customer_edit')) active
            @elseif (request()->routeIs('admin.customer_management.customer_details')) active
            @elseif (request()->routeIs('admin.customer_management.customer.change_password')) active 
            @elseif (request()->routeIs('admin.organizer_management.add_customer')) active @endif">
                        <a data-toggle="collapse" href="#customer">
                            <i class="fas fa-users"></i>
                            <p>{{ __('Customers Management') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div id="customer"
                            class="collapse
              @if (request()->routeIs('admin.organizer_management.registered_customer')) show
              @elseif (request()->routeIs('admin.customer_management.customer_details')) show
              @elseif (request()->routeIs('admin.customer_management.customer_edit')) show
              @elseif (request()->routeIs('admin.customer_management.customer.change_password')) show 
              @elseif (request()->routeIs('admin.organizer_management.add_customer')) show @endif">
                            <ul class="nav nav-collapse">
                                <li
                                    class="@if (request()->routeIs('admin.organizer_management.registered_customer')) active
                  @elseif (request()->routeIs('admin.customer_management.customer_details')) active
                  @elseif (request()->routeIs('admin.customer_management.customer_edit')) active
                  @elseif (request()->routeIs('admin.customer_management.customer.change_password')) active @endif">
                                    <a href="{{ route('admin.organizer_management.registered_customer') }}">
                                        <span class="sub-item">{{ __('Registered Customers') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->routeIs('admin.organizer_management.add_customer')) active @endif">
                                    <a href="{{ route('admin.organizer_management.add_customer') }}">
                                        <span class="sub-item">{{ __('Add Customer') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- customer --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Support Ticket', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.support_ticket.setting')) active
            @elseif (request()->routeIs('admin.support_tickets')) active
            @elseif (request()->routeIs('admin.support_tickets.message')) active @endif">
                        <a data-toggle="collapse" href="#support_ticket">
                            <i class="la flaticon-web-1"></i>
                            <p>{{ __('Support Tickets') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="support_ticket"
                            class="collapse
              @if (request()->routeIs('admin.support_ticket.setting')) show
              @elseif (request()->routeIs('admin.support_tickets')) show
              @elseif (request()->routeIs('admin.support_tickets.message')) show @endif">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->routeIs('admin.support_ticket.setting')) active @endif">
                                    <a href="{{ route('admin.support_ticket.setting') }}">
                                        <span class="sub-item">{{ __('Settings') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="
                  @if (request()->routeIs('admin.support_tickets') && empty(request()->input('status'))) active
                  @elseif(request()->routeIs('admin.support_tickets.message')) active @endif ">
                                    <a href="{{ route('admin.support_tickets') }}">
                                        <span class="sub-item">{{ __('All Tickets') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ request()->routeIs('admin.support_tickets') && request()->input('status') == 1 ? 'active' : '' }}">
                                    <a href="{{ route('admin.support_tickets', ['status' => 1]) }}">
                                        <span class="sub-item">{{ __('Pending Tickets') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ request()->routeIs('admin.support_tickets') && request()->input('status') == 2 ? 'active' : '' }}">
                                    <a href="{{ route('admin.support_tickets', ['status' => 2]) }}">
                                        <span class="sub-item">{{ __('Open Tickets') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ request()->routeIs('admin.support_tickets') && request()->input('status') == 3 ? 'active' : '' }}">
                                    <a href="{{ route('admin.support_tickets', ['status' => 3]) }}">
                                        <span class="sub-item">{{ __('Closed Tickets') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- customer --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Shop Management', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.product.setting')) active
            @elseif (request()->routeIs('admin.shop_management.shipping_charge')) active
            @elseif (request()->routeIs('admin.shop_management.category')) active
            @elseif (request()->routeIs('admin.shop_management.coupon')) active
            @elseif (request()->routeIs('admin.shop_management.product_type')) active
            @elseif (request()->routeIs('admin.shop_management.product.create')) active
            @elseif (request()->routeIs('admin.shop_management.products')) active
            @elseif (request()->routeIs('admin.shop_management.product.edit')) active
            @elseif (request()->routeIs('admin.product.order')) active
            @elseif (request()->routeIs('admin.product_order.details')) active
            @elseif (request()->routeIs('admin.product_order.report')) active @endif">
                        <a data-toggle="collapse" href="#shop_management">
                            <i class="fas fa-store-alt"></i>
                            <p>{{ __('Shop Management') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="shop_management"
                            class="collapse
              @if (request()->routeIs('admin.product.setting')) show
              @elseif (request()->routeIs('admin.shop_management.shipping_charge')) show
              @elseif (request()->routeIs('admin.shop_management.category')) show
              @elseif (request()->routeIs('admin.shop_management.coupon')) show
              @elseif (request()->routeIs('admin.shop_management.product_type')) show
              @elseif (request()->routeIs('admin.shop_management.product.create')) show
              @elseif (request()->routeIs('admin.shop_management.product.edit')) show
              @elseif (request()->routeIs('admin.shop_management.products')) show
              @elseif (request()->routeIs('admin.product.order')) show
              @elseif (request()->routeIs('admin.product_order.details')) show
              @elseif (request()->routeIs('admin.product_order.report')) show @endif">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->routeIs('admin.product.setting')) active @endif">
                                    <a href="{{ route('admin.product.setting') }}">
                                        <span class="sub-item">{{ __('Settings') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ request()->routeIs('admin.shop_management.shipping_charge') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.shop_management.shipping_charge', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Shipping Charges') }}</span>
                                    </a>
                                </li>
                                <li class="{{ request()->routeIs('admin.shop_management.coupon') ? 'active' : '' }}">
                                    <a href="{{ route('admin.shop_management.coupon', ['status' => 1]) }}">
                                        <span class="sub-item">{{ __('Coupon') }}</span>
                                    </a>
                                </li>

                                <li class="submenu">
                                    <a data-toggle="collapse" href="#productManagement"
                                        aria-expanded="{{ request()->routeIs('admin.shop_management.category') || request()->routeIs('admin.shop_management.product_type') || request()->routeIs('admin.shop_management.product.create') || request()->routeIs('admin.shop_management.products') || request()->routeIs('admin.product_order.report') ? 'true' : 'false' }}">
                                        <span class="sub-item">{{ __('Manage Products') }}</span>
                                        <span class="caret"></span>
                                    </a>
                                    <div class="collapse
                    @if (request()->routeIs('admin.shop_management.category')) show
                    @elseif(request()->routeIs('admin.shop_management.product_type')) show
                    @elseif(request()->routeIs('admin.shop_management.product.create')) show
                    @elseif(request()->routeIs('admin.shop_management.product.edit')) show
                    @elseif(request()->routeIs('admin.shop_management.products')) show
                    @elseif(request()->routeIs('admin.product_order.report')) show @endif"
                                        id="productManagement">
                                        <ul class="nav nav-collapse subnav">
                                            <li
                                                class="
                        @if (request()->routeIs('admin.shop_management.category')) active @endif">
                                                <a
                                                    href="{{ route('admin.shop_management.category', ['language' => $defaultLang->code]) }}">
                                                    <span class="sub-item">{{ __('Category') }}</span>
                                                </a>
                                            </li>
                                            <li
                                                class="
                        @if (request()->routeIs('admin.shop_management.product_type')) active
                        @elseif(request()->routeIs('admin.shop_management.product.create')) active @endif">
                                                <a href="{{ route('admin.shop_management.product_type') }}">
                                                    <span class="sub-item">{{ __('Add Product') }}</span>
                                                </a>
                                            </li>
                                            <li
                                                class="
                        @if (request()->routeIs('admin.shop_management.products')) active
                        @elseif(request()->routeIs('admin.shop_management.product.edit')) active @endif">
                                                <a
                                                    href="{{ route('admin.shop_management.products', ['language' => $defaultLang->code]) }}">
                                                    <span class="sub-item">{{ __('Products') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="submenu">
                                    <a data-toggle="collapse" href="#orderManagement"
                                        aria-expanded="{{ request()->routeIs('admin.product.order') || request()->routeIs('admin.product_order.report') || request()->routeIs('admin.product_order.details') ? 'true' : 'false' }}">
                                        <span class="sub-item">{{ __('Manage Orders') }}</span>
                                        <span class="caret"></span>
                                    </a>
                                    <div class="collapse
                    @if (request()->routeIs('admin.product.order')) show
                    @elseif(request()->routeIs('admin.product_order.details')) show
                    @elseif(request()->routeIs('admin.product_order.report')) show @endif"
                                        id="orderManagement">
                                        <ul class="nav nav-collapse subnav">

                                            <li
                                                class="
                        @if (request()->routeIs('admin.product.order') && empty(request()->input('type'))) active 
                        @elseif (request()->routeIs('admin.product_order.details')) active @endif">
                                                <a href="{{ route('admin.product.order') }}">
                                                    <span class="sub-item">{{ __('All Orders') }}</span>
                                                </a>
                                            </li>
                                            <li
                                                class="
                        @if (request()->routeIs('admin.product.order') && request()->input('type') == 'pending') active @endif">
                                                <a href="{{ route('admin.product.order', ['type' => 'pending']) }}">
                                                    <span class="sub-item">{{ __('Pending Orders') }}</span>
                                                </a>
                                            </li>
                                            <li
                                                class="
                        @if (request()->routeIs('admin.product.order') && request()->input('type') == 'processing') active @endif">
                                                <a
                                                    href="{{ route('admin.product.order', ['type' => 'processing']) }}">
                                                    <span class="sub-item">{{ __('Processing Orders') }}</span>
                                                </a>
                                            </li>
                                            <li
                                                class="
                        @if (request()->routeIs('admin.product.order') && request()->input('type') == 'completed') active @endif">
                                                <a
                                                    href="{{ route('admin.product.order', ['type' => 'completed']) }}">
                                                    <span class="sub-item">{{ __('Completed Orders') }}</span>
                                                </a>
                                            </li>
                                            <li
                                                class="
                        @if (request()->routeIs('admin.product.order') && request()->input('type') == 'rejected') active @endif">
                                                <a href="{{ route('admin.product.order', ['type' => 'rejected']) }}">
                                                    <span class="sub-item">{{ __('Rejected Orders') }}</span>
                                                </a>
                                            </li>
                                            <li
                                                class="
                        @if (request()->routeIs('admin.product_order.report')) active @endif">
                                                <a
                                                    href="{{ route('admin.product_order.report', ['language' => $defaultLang->code]) }}">
                                                    <span class="sub-item">{{ __('Report') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- home page --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Home Page', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.home_page.hero_section')) active
            @elseif (request()->routeIs('admin.home_page.section_titles')) active
            @elseif (request()->routeIs('admin.home_page.features_section')) active
            @elseif (request()->routeIs('admin.home_page.event_features_section')) active
            @elseif (request()->routeIs('admin.home_page.how.work')) active
            @elseif (request()->routeIs('admin.home_page.partner')) active
            @elseif (request()->routeIs('admin.home_page.testimonials_section')) active
            @elseif (request()->routeIs('admin.home_page.about_us_section')) active
            @elseif (request()->routeIs('admin.home_page.section_customization')) active @endif">
                        <a data-toggle="collapse" href="#home_page">
                            <i class="fal fa-layer-group"></i>
                            <p>{{ __('Home Page') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="home_page"
                            class="collapse
              @if (request()->routeIs('admin.home_page.hero_section')) show
              @elseif (request()->routeIs('admin.home_page.section_titles')) show
              @elseif (request()->routeIs('admin.home_page.features_section')) show
              @elseif (request()->routeIs('admin.home_page.event_features_section')) show
              @elseif (request()->routeIs('admin.home_page.how.work')) show
              @elseif (request()->routeIs('admin.home_page.partner')) show
              @elseif (request()->routeIs('admin.home_page.testimonials_section')) show
              @elseif (request()->routeIs('admin.home_page.about_us_section')) show
              @elseif (request()->routeIs('admin.home_page.section_customization')) show @endif">
                            <ul class="nav nav-collapse">
                                <li class="{{ request()->routeIs('admin.home_page.hero_section') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.home_page.hero_section', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Hero Section') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.home_page.section_titles') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.home_page.section_titles', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Section Titles') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.home_page.event_features_section') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.home_page.event_features_section', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Event Features Section') }}</span>
                                    </a>
                                </li>
                                <li class="{{ request()->routeIs('admin.home_page.how.work') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.home_page.how.work', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('How it Work Section') }}</span>
                                    </a>
                                </li>
                                <li class="{{ request()->routeIs('admin.home_page.partner') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.home_page.partner', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Partner Section') }}</span>
                                    </a>
                                </li>


                                <li
                                    class="{{ request()->routeIs('admin.home_page.testimonials_section') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.home_page.testimonials_section', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Testimonials Section') }}</span>
                                    </a>
                                </li>


                                <li
                                    class="{{ request()->routeIs('admin.home_page.about_us_section') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.home_page.about_us_section', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('About Us Section') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.home_page.section_customization') ? 'active' : '' }}">
                                    <a href="{{ route('admin.home_page.section_customization') }}">
                                        <span class="sub-item">{{ __('Section Hide/Show') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- footer --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Footer', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.footer.content')) active
            @elseif (request()->routeIs('admin.footer.quick_links')) active
            @elseif (request()->routeIs('admin.contact.page')) active @endif">
                        <a data-toggle="collapse" href="#footer">
                            <i class="fal fa-shoe-prints"></i>
                            <p>{{ __('Footer') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="footer"
                            class="collapse @if (request()->routeIs('admin.footer.content')) show
              @elseif (request()->routeIs('admin.footer.quick_links')) show
              @elseif (request()->routeIs('admin.contact.page')) show @endif">
                            <ul class="nav nav-collapse">
                                <li class="{{ request()->routeIs('admin.footer.content') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.footer.content', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Content & Color') }}</span>
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('admin.footer.quick_links') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.footer.quick_links', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Quick Links') }}</span>
                                    </a>
                                </li>
                                <li class="{{ request()->routeIs('admin.contact.page') ? 'active' : '' }}">
                                    <a href="{{ route('admin.contact.page', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Contact Page') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- custom page --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Custom Pages', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.custom_pages')) active
            @elseif (request()->routeIs('admin.custom_pages.create_page')) active
            @elseif (request()->routeIs('admin.custom_pages.edit_page')) active @endif">
                        <a href="{{ route('admin.custom_pages', ['language' => $defaultLang->code]) }}">
                            <i class="la flaticon-file"></i>
                            <p>{{ __('Custom Pages') }}</p>
                        </a>
                    </li>
                @endif

                {{-- blog --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Blog Management', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.blog_management.categories')) active
            @elseif (request()->routeIs('admin.blog_management.blogs')) active
            @elseif (request()->routeIs('admin.blog_management.create_blog')) active
            @elseif (request()->routeIs('admin.blog_management.edit_blog')) active @endif">
                        <a data-toggle="collapse" href="#blog">
                            <i class="fal fa-blog"></i>
                            <p>{{ __('Blog Management') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div id="blog"
                            class="collapse
              @if (request()->routeIs('admin.blog_management.categories')) show
              @elseif (request()->routeIs('admin.blog_management.blogs')) show
              @elseif (request()->routeIs('admin.blog_management.create_blog')) show
              @elseif (request()->routeIs('admin.blog_management.edit_blog')) show @endif">
                            <ul class="nav nav-collapse">
                                <li
                                    class="{{ request()->routeIs('admin.blog_management.categories') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.blog_management.categories', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Categories') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="@if (request()->routeIs('admin.blog_management.blogs')) active
                  @elseif (request()->routeIs('admin.blog_management.create_blog')) active
                  @elseif (request()->routeIs('admin.blog_management.edit_blog')) active @endif">
                                    <a
                                        href="{{ route('admin.blog_management.blogs', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Blog') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- faq --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('FAQ Management', $rolePermissions)))
                    <li class="nav-item {{ request()->routeIs('admin.faq_management') ? 'active' : '' }}">
                        <a href="{{ route('admin.faq_management', ['language' => $defaultLang->code]) }}">
                            <i class="la flaticon-round"></i>
                            <p>{{ __('FAQ Management') }}</p>
                        </a>
                    </li>
                @endif

                {{-- faq --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Contact Page', $rolePermissions)))
                    <li
                        class="nav-item {{ request()->routeIs('admin.basic_settings.contact_page') ? 'active' : '' }}">
                        <a href="{{ route('admin.basic_settings.contact_page') }}">
                            <i class="fas fa-address-book"></i>
                            <p>{{ __('Contact Page') }}</p>
                        </a>
                    </li>
                @endif

                {{-- advertise --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Advertise', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.advertise.settings')) active
            @elseif (request()->routeIs('admin.advertise.advertisements')) active @endif">
                        <a data-toggle="collapse" href="#workingId">
                            <i class="fab fa-buysellads"></i>
                            <p>{{ __('Ads') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="workingId"
                            class="collapse @if (request()->routeIs('admin.advertise.settings')) show
              @elseif (request()->routeIs('admin.advertise.advertisements')) show @endif">
                            <ul class="nav nav-collapse">
                                <li class="{{ request()->routeIs('admin.advertise.settings') ? 'active' : '' }}">
                                    <a href="{{ route('admin.advertise.settings') }}">
                                        <span class="sub-item">{{ __('Settings') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.advertise.advertisements') ? 'active' : '' }}">
                                    <a href="{{ route('admin.advertise.advertisements') }}">
                                        <span class="sub-item">{{ __('Advertisements') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- announcement popup --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Announcement Popups', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.announcement_popups')) active
            @elseif (request()->routeIs('admin.announcement_popups.select_popup_type')) active
            @elseif (request()->routeIs('admin.announcement_popups.create_popup')) active
            @elseif (request()->routeIs('admin.announcement_popups.edit_popup')) active @endif">
                        <a href="{{ route('admin.announcement_popups', ['language' => $defaultLang->code]) }}">
                            <i class="fal fa-bullhorn"></i>
                            <p>{{ __('Announcement Popups') }}</p>
                        </a>
                    </li>
                @endif
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Subscribers', $rolePermissions)))
                    <li
                        class="nav-item
          @if (request()->routeIs('admin.user_management.subscribers')) active
          @elseif(request()->routeIs('admin.user_management.mail_for_subscribers')) active @endif">
                        <a data-toggle="collapse" href="#subscribers">
                            <i class="la flaticon-envelope"></i>
                            <p>{{ __('Subscribers') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
            @if (request()->routeIs('admin.user_management.subscribers')) show
            @elseif(request()->routeIs('admin.user_management.mail_for_subscribers')) show @endif"
                            id="subscribers">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->routeIs('admin.user_management.subscribers')) active @endif">
                                    <a href="{{ route('admin.user_management.subscribers') }}">
                                        <span class="sub-item">{{ __('Subscribers') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->routeIs('admin.user_management.mail_for_subscribers')) active @endif">
                                    <a href="{{ route('admin.user_management.mail_for_subscribers') }}">
                                        <span class="sub-item">{{ __('Mail to Subscribers') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Push Notification', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.user_management.push_notification.settings')) active 
                    @elseif (request()->routeIs('admin.user_management.push_notification.notification_for_visitors')) active @endif">
                        <a data-toggle="collapse" href="#push_notification">
                            <i class="fal fa-bell"></i>
                            <p>{{ __('Push Notification') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="push_notification"
                            class="collapse 
              @if (request()->routeIs('admin.user_management.push_notification.settings')) show 
                    @elseif (request()->routeIs('admin.user_management.push_notification.notification_for_visitors')) show @endif">
                            <ul class="nav nav-collapse">
                                <li
                                    class="{{ request()->routeIs('admin.user_management.push_notification.settings') ? 'active' : '' }}">
                                    <a href="{{ route('admin.user_management.push_notification.settings') }}">
                                        <span class="sub-item">{{ __('Settings') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.user_management.push_notification.notification_for_visitors') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.user_management.push_notification.notification_for_visitors') }}">
                                        <span class="sub-item">{{ __('Send Notification') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- payment gateway --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Payment Gateways', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.payment_gateways.online_gateways')) active
            @elseif (request()->routeIs('admin.payment_gateways.offline_gateways')) active @endif">
                        <a data-toggle="collapse" href="#payment_gateways">
                            <i class="la flaticon-paypal"></i>
                            <p>{{ __('Payment Gateways') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="payment_gateways"
                            class="collapse
              @if (request()->routeIs('admin.payment_gateways.online_gateways')) show
              @elseif (request()->routeIs('admin.payment_gateways.offline_gateways')) show @endif">
                            <ul class="nav nav-collapse">
                                <li
                                    class="{{ request()->routeIs('admin.payment_gateways.online_gateways') ? 'active' : '' }}">
                                    <a href="{{ route('admin.payment_gateways.online_gateways') }}">
                                        <span class="sub-item">{{ __('Online Gateways') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.payment_gateways.offline_gateways') ? 'active' : '' }}">
                                    <a href="{{ route('admin.payment_gateways.offline_gateways') }}">
                                        <span class="sub-item">{{ __('Offline Gateways') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- basic settings --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Basic Settings', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.basic_settings.general_settings')) active 
            @elseif (request()->routeIs('admin.basic_settings.mail_from_admin')) active
            @elseif (request()->routeIs('admin.basic_settings.mail_to_admin')) active
            @elseif (request()->routeIs('admin.basic_settings.mail_templates')) active
            @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) active
            @elseif (request()->routeIs('admin.basic_settings.breadcrumb')) active
            @elseif (request()->routeIs('admin.basic_settings.page_headings')) active
            @elseif (request()->routeIs('admin.basic_settings.plugins')) active
            @elseif (request()->routeIs('admin.basic_settings.seo')) active
            @elseif (request()->routeIs('admin.basic_settings.maintenance_mode')) active
            @elseif (request()->routeIs('admin.basic_settings.cookie_alert')) active
            @elseif (request()->routeIs('admin.basic_settings.footer_logo')) active
            @elseif (request()->routeIs('admin.basic_settings.social_medias')) active @endif">
                        <a data-toggle="collapse" href="#basic_settings">
                            <i class="la flaticon-settings"></i>
                            <p>{{ __('Basic Settings') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="basic_settings"
                            class="collapse
              @if (request()->routeIs('admin.basic_settings.general_settings')) show
              @elseif (request()->routeIs('admin.basic_settings.mail_from_admin')) show
              @elseif (request()->routeIs('admin.basic_settings.mail_to_admin')) show
              @elseif (request()->routeIs('admin.basic_settings.mail_templates')) show
              @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) show
              @elseif (request()->routeIs('admin.basic_settings.breadcrumb')) show
              @elseif (request()->routeIs('admin.basic_settings.page_headings')) show
              @elseif (request()->routeIs('admin.basic_settings.plugins')) show
              @elseif (request()->routeIs('admin.basic_settings.seo')) show
              @elseif (request()->routeIs('admin.basic_settings.maintenance_mode')) show
              @elseif (request()->routeIs('admin.basic_settings.cookie_alert')) show
              @elseif (request()->routeIs('admin.basic_settings.footer_logo')) show
              @elseif (request()->routeIs('admin.basic_settings.social_medias')) show @endif">
                            <ul class="nav nav-collapse">
                                <li
                                    class="{{ request()->routeIs('admin.basic_settings.general_settings') ? 'active' : '' }}">
                                    <a href="{{ route('admin.basic_settings.general_settings') }}">
                                        <span class="sub-item">{{ __('General Settings') }}</span>
                                    </a>
                                </li>

                                <li class="submenu">
                                    <a data-toggle="collapse" href="#mail_settings"
                                        aria-expanded="{{ request()->routeIs('admin.basic_settings.mail_from_admin') || request()->routeIs('admin.basic_settings.mail_to_admin') || request()->routeIs('admin.basic_settings.mail_templates') || request()->routeIs('admin.basic_settings.edit_mail_template') ? 'true' : 'false' }}">
                                        <span class="sub-item">{{ __('Email Settings') }}</span>
                                        <span class="caret"></span>
                                    </a>

                                    <div id="mail_settings"
                                        class="collapse
                    @if (request()->routeIs('admin.basic_settings.mail_from_admin')) show
                    @elseif (request()->routeIs('admin.basic_settings.mail_to_admin')) show
                    @elseif (request()->routeIs('admin.basic_settings.mail_templates')) show
                    @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) show @endif">
                                        <ul class="nav nav-collapse subnav">
                                            <li
                                                class="{{ request()->routeIs('admin.basic_settings.mail_from_admin') ? 'active' : '' }}">
                                                <a href="{{ route('admin.basic_settings.mail_from_admin') }}">
                                                    <span class="sub-item">{{ __('Mail From Admin') }}</span>
                                                </a>
                                            </li>

                                            <li
                                                class="{{ request()->routeIs('admin.basic_settings.mail_to_admin') ? 'active' : '' }}">
                                                <a href="{{ route('admin.basic_settings.mail_to_admin') }}">
                                                    <span class="sub-item">{{ __('Mail To Admin') }}</span>
                                                </a>
                                            </li>

                                            <li
                                                class="@if (request()->routeIs('admin.basic_settings.mail_templates')) active
                        @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) active @endif">
                                                <a href="{{ route('admin.basic_settings.mail_templates') }}">
                                                    <span class="sub-item">{{ __('Mail Templates') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.basic_settings.breadcrumb') ? 'active' : '' }}">
                                    <a href="{{ route('admin.basic_settings.breadcrumb') }}">
                                        <span class="sub-item">{{ __('Breadcrumb') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.basic_settings.page_headings') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.basic_settings.page_headings', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Page Headings') }}</span>
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('admin.basic_settings.plugins') ? 'active' : '' }}">
                                    <a href="{{ route('admin.basic_settings.plugins') }}">
                                        <span class="sub-item">{{ __('Plugins') }}</span>
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('admin.basic_settings.seo') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.basic_settings.seo', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('SEO Informations') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.basic_settings.maintenance_mode') ? 'active' : '' }}">
                                    <a href="{{ route('admin.basic_settings.maintenance_mode') }}">
                                        <span class="sub-item">{{ __('Maintenance Mode') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.basic_settings.cookie_alert') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.basic_settings.cookie_alert', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Cookie Alert') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.basic_settings.footer_logo') ? 'active' : '' }}">
                                    <a href="{{ route('admin.basic_settings.footer_logo') }}">
                                        <span class="sub-item">{{ __('Footer Logo') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.basic_settings.social_medias') ? 'active' : '' }}">
                                    <a href="{{ route('admin.basic_settings.social_medias') }}">
                                        <span class="sub-item">{{ __('Social Medias') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- pwa setting --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('PWA Settings', $rolePermissions)))
                    <li
                        class="nav-item
          @if (request()->routeIs('admin.pwa')) active 
          @elseif (request()->routeIs('admin.pwa.scanner')) active @endif">
                        <a data-toggle="collapse" href="#pwa_setting">
                            <i class="fab fa-app-store-ios"></i>
                            <p>{{ __('PWA Settings') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="pwa_setting"
                            class="collapse
            @if (request()->routeIs('admin.pwa')) show
            @elseif (request()->routeIs('admin.pwa.scanner')) show @endif
            ">
                            <ul class="nav nav-collapse">
                                <li class="{{ request()->routeIs('admin.pwa.scanner') ? 'active' : '' }}">
                                    <a href="{{ route('admin.pwa.scanner') }}">
                                        <span class="sub-item">{{ __('PWA Scanner Setting') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- admin --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Admin Management', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.admin_management.role_permissions')) active
            @elseif (request()->routeIs('admin.admin_management.role.permissions')) active
            @elseif (request()->routeIs('admin.admin_management.registered_admins')) active @endif">
                        <a data-toggle="collapse" href="#admin">
                            <i class="fal fa-users-cog"></i>
                            <p>{{ __('Admin Management') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="admin"
                            class="collapse
              @if (request()->routeIs('admin.admin_management.role_permissions')) show
              @elseif (request()->routeIs('admin.admin_management.role.permissions')) show
              @elseif (request()->routeIs('admin.admin_management.registered_admins')) show @endif">
                            <ul class="nav nav-collapse">
                                <li
                                    class="@if (request()->routeIs('admin.admin_management.role_permissions')) active
                  @elseif (request()->routeIs('admin.admin_management.role.permissions')) active @endif">
                                    <a href="{{ route('admin.admin_management.role_permissions') }}">
                                        <span class="sub-item">{{ __('Role & Permissions') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('admin.admin_management.registered_admins') ? 'active' : '' }}">
                                    <a href="{{ route('admin.admin_management.registered_admins') }}">
                                        <span class="sub-item">{{ __('Registered Admins') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- language --}}
                @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Language Management', $rolePermissions)))
                    <li
                        class="nav-item @if (request()->routeIs('admin.language_management')) active
            @elseif (request()->routeIs('admin.language_management.edit_keyword')) active @endif">
                        <a href="{{ route('admin.language_management') }}">
                            <i class="fal fa-language"></i>
                            <p>{{ __('Language Management') }}</p>
                        </a>
                    </li>


                    <li
                        class="nav-item @if (request()->routeIs('admin.edit_admin_keywords')) active
            @elseif (request()->routeIs('admin.edit_admin_keywords')) active @endif">
                        <a href="{{ route('admin.edit_admin_keywords') }}">
                            <i class="fal fa-language"></i>
                            <p>{{ __('Admin Language Keywords') }}</p>
                        </a>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</div>
