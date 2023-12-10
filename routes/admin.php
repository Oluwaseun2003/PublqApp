<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackEnd\Event\CategoryController;

/*
|--------------------------------------------------------------------------
| User Interface Routes
|--------------------------------------------------------------------------
*/

Route::get('admin/get-state-city/{id}', 'BackEnd\Event\EventController@city_state')->name('get.city.state');

Route::prefix('/admin')->middleware(['auth:admin', 'adminLang'])->group(function () {
  // admin redirect to dashboard route
  Route::get('/dashboard', 'BackEnd\AdminController@redirectToDashboard')->name('admin.dashboard');
  Route::group(['middleware' => 'permission:Transaction'], function () {
    Route::get('/transcation', 'BackEnd\AdminController@transcation')->name('admin.transcation');
    Route::post('/transcation/delete', 'BackEnd\AdminController@destroy')->name('admin.transcation.delete');
    Route::post('/transcation/bulk-delete', 'BackEnd\AdminController@bulk_destroy')->name('admin.transcation.bulk_delete');
  });


  // change admin-panel theme (dark/light) route
  Route::post('/change-theme', 'BackEnd\AdminController@changeTheme')->name('admin.change_theme');

  // admin profile settings route start
  Route::get('/edit-profile', 'BackEnd\AdminController@editProfile')->name('admin.edit_profile');

  Route::post('/update-profile', 'BackEnd\AdminController@updateProfile')->name('admin.update_profile');

  Route::get('/change-password', 'BackEnd\AdminController@changePassword')->name('admin.change_password');

  Route::post('/update-password', 'BackEnd\AdminController@updatePassword')->name('admin.update_password');
  // admin profile settings route end

  // admin logout attempt route
  Route::get('/logout', 'BackEnd\AdminController@logout')->name('admin.logout');


  // admin management route start
  Route::prefix('/admin-management')->middleware('permission:Admin Management')->group(function () {
    Route::get('/role-permissions', 'BackEnd\Administrator\RolePermissionController@index')->name('admin.admin_management.role_permissions');

    Route::post('/store-role', 'BackEnd\Administrator\RolePermissionController@store')->name('admin.admin_management.store_role');

    Route::get('/role/{id}/permissions', 'BackEnd\Administrator\RolePermissionController@permissions')->name('admin.admin_management.role.permissions');

    Route::post('/role/{id}/update-permissions', 'BackEnd\Administrator\RolePermissionController@updatePermissions')->name('admin.admin_management.role.update_permissions');

    Route::post('/update-role', 'BackEnd\Administrator\RolePermissionController@update')->name('admin.admin_management.update_role');

    Route::post('/delete-role/{id}', 'BackEnd\Administrator\RolePermissionController@destroy')->name('admin.admin_management.delete_role');

    Route::get('/registered-admins', 'BackEnd\Administrator\SiteAdminController@index')->name('admin.admin_management.registered_admins');

    Route::post('/store-admin', 'BackEnd\Administrator\SiteAdminController@store')->name('admin.admin_management.store_admin');

    Route::post('/admin/{id}/update-status', 'BackEnd\Administrator\SiteAdminController@updateStatus')->name('admin.admin_management.admin.update_status');

    Route::post('/update-admin', 'BackEnd\Administrator\SiteAdminController@update')->name('admin.admin_management.update_admin');

    Route::post('/delete-admin/{id}', 'BackEnd\Administrator\SiteAdminController@destroy')->name('admin.admin_management.delete_admin');
  });
  // admin management route end

  Route::prefix('/admin')->middleware(['auth:admin', 'permission:PWA Settings'])->group(function () {
    Route::get('pwa', 'BackEnd\BasicSettings\BasicController@pwa')->name('admin.pwa');
    Route::post('/pwa/post', 'BackEnd\BasicSettings\BasicController@updatepwa')->name('admin.pwa.update');
    Route::get('pwa/scanner', 'BackEnd\BasicSettings\BasicController@pwa_scanner')->name('admin.pwa.scanner');
    Route::post('/pwa/scanner/post', 'BackEnd\BasicSettings\BasicController@updatepwaScanner')->name('admin.pwa.scanner.update');
  });


  Route::get('/monthly-profit', 'BackEnd\AdminController@monthly_profit')->name('admin.monthly_profit');
  Route::get('/monthly-earning', 'BackEnd\AdminController@monthly_earning')->name('admin.monthly_earning');

  Route::group(['middleware' => 'permission:Event Management'], function () {
    Route::get('event-management/events/', 'BackEnd\Event\EventController@index')->name('admin.event_management.event');
    Route::get('add-event/', 'BackEnd\Event\EventController@add_event')->name('add.event.event');
    Route::get('choose-event-type/', 'BackEnd\Event\EventController@choose_event_type')->name('admin.choose-event-type');
    Route::post('event-imagesstore', 'BackEnd\Event\EventController@gallerystore')->name('admin.event.imagesstore');
    Route::post('event-imagermv', 'BackEnd\Event\EventController@imagermv')->name('admin.event.imagermv');
    Route::post('event-store', 'BackEnd\Event\EventController@store')->name('admin.event_management.store_event');
    Route::post('/event/{id}/update-status', 'BackEnd\Event\EventController@updateStatus')->name('admin.event_management.event.event_status');
    Route::post('/event/{id}/update-featured', 'BackEnd\Event\EventController@updateFeatured')->name('admin.event_management.event.update_featured');
    Route::post('/delete-event/{id}', 'BackEnd\Event\EventController@destroy')->name('admin.event_management.delete_event');
    Route::get('/edit-event/{id}', 'BackEnd\Event\EventController@edit')->name('admin.event_management.edit_event');
    Route::post('/event-img-dbrmv', 'BackEnd\Event\EventController@imagedbrmv')->name('admin.event.imgdbrmv');

    Route::get('/delete-date/{id}', 'BackEnd\Event\EventController@deleteDate')->name('admin.event.delete.date');

    Route::get('/event-images/{id}', 'BackEnd\Event\EventController@images')->name('admin.event.images');
    Route::post('/event-update', 'BackEnd\Event\EventController@update')->name('admin.event.update');
    Route::post('bulk/delete/event', 'BackEnd\Event\EventController@bulk_delete')->name('admin.event_management.bulk_delete_event');

    Route::get('event/ticket', 'BackEnd\Event\TicketController@index')->name('admin.event.ticket');
    Route::get('event/add-ticket', 'BackEnd\Event\TicketController@create')->name('admin.event.add.ticket');
    Route::post('event/ticket/store-ticket', 'BackEnd\Event\TicketController@store')->name('admin.ticket_management.store_ticket');
    Route::get('event/edit/ticket', 'BackEnd\Event\TicketController@edit')->name('admin.event.edit.ticket');
    Route::post('event/ticket/delete-ticket', 'BackEnd\Event\TicketController@destroy')->name('admin.ticket_management.delete_ticket');
    Route::get('delete-variation/{id}', 'BackEnd\Event\TicketController@delete_variation')->name('delete.variation');
    Route::post('ticket_management/update/ticket', 'BackEnd\Event\TicketController@update')->name('admin.ticket_management.update_ticket');
    Route::post('bulk/delete/bulk/event/ticket', 'BackEnd\Event\TicketController@bulk_delete')->name('admin.event_management.bulk_delete_event_ticket');


    // event route start
    Route::prefix('/event-management')->group(function () {

      Route::get('/categories', 'BackEnd\Event\CategoryController@index')->name('admin.event_management.categories');

      Route::post('/store-category', 'BackEnd\Event\CategoryController@store')->name('admin.event_management.store_category');

      Route::post('/category/{id}/update-featured', 'BackEnd\Event\CategoryController@updateFeatured')->name('admin.event_management.category.update_featured');

      Route::put('/update-category', 'BackEnd\Event\CategoryController@update')->name('admin.event_management.update_category');

      Route::post('/delete-category/{id}', 'BackEnd\Event\CategoryController@destroy')->name('admin.event_management.delete_category');

      Route::post('/bulk-delete-category', 'BackEnd\Event\CategoryController@bulkDestroy')->name('admin.event_management.bulk_delete_category');
    });
  });

  Route::group(['middleware' => 'permission:Event Bookings'], function () {
    Route::get('/coupons', 'BackEnd\Event\CouponController@index')->name('admin.event_management.coupons');
    Route::post('/store-coupon', 'BackEnd\Event\CouponController@store')->name('admin.event_management.store_coupon');
    Route::post('/update-coupon', 'BackEnd\Event\CouponController@update')->name('admin.event_management.update_coupon');
    Route::post('/delete-coupon/{id}', 'BackEnd\Event\CouponController@destroy')->name('admin.event_management.delete_coupon');

    Route::get('/tax-commission', 'BackEnd\BasicSettings\BasicController@taxCommission')->name('admin.event_booking.settings.tax_commission');

    Route::post('/update-tax-commission', 'BackEnd\BasicSettings\BasicController@updateEventTaxCommission')->name('admin.event_booking.settings.update_tax_commission');

    Route::get('event-booking', 'BackEnd\Event\EventBookingController@index')->name('admin.event.booking');
    Route::post('event-booking/update/payment-status/{id}', 'BackEnd\Event\EventBookingController@updatePaymentStatus')->name('admin.event_booking.update_payment_status');
    Route::get('event-booking/details/{id}', 'BackEnd\Event\EventBookingController@show')->name('admin.event_booking.details');
    Route::post('/{id}/delete', 'BackEnd\Event\EventBookingController@destroy')->name('admin.event_booking.delete');
    Route::post('/event-booking/bulk-delete', 'BackEnd\Event\EventBookingController@bulkDestroy')->name('admin.event_booking.bulk_delete');

    Route::get('/event-booking/report', 'BackEnd\Event\EventBookingController@report')->name('admin.event_booking.report');
    Route::get('/event-booking/export', 'BackEnd\Event\EventBookingController@export')->name('admin.event_bookings.export');
  });


  Route::prefix('/home-page')->middleware('permission:Home Page')->group(function () {
    Route::get('/event-features-section', 'BackEnd\HomePage\EventFeatureController@index')->name('admin.home_page.event_features_section');
    Route::post('/update-event-features-section', 'BackEnd\HomePage\EventFeatureController@update')->name('admin.home_page.update_event_feature_section');
    Route::post('/store/event/feature', 'BackEnd\HomePage\EventFeatureController@store')->name('admin.home_page.store_event_feature');
    Route::put('/update/event/feature', 'BackEnd\HomePage\EventFeatureController@update_feature')->name('admin.home_page.update_event_feature');
    Route::post('delete-event-feture/{id}', 'BackEnd\HomePage\EventFeatureController@delete')->name('admin.home_page.delete_event_feature');
    Route::post('bulk-delete-event-feture', 'BackEnd\HomePage\EventFeatureController@bulk_delete')->name('admin.home_page.bulk_delete_event_feature');

    Route::get('/how-work', 'BackEnd\HomePage\HowWorkController@index')->name('admin.home_page.how.work');
    Route::post('/update-how-work', 'BackEnd\HomePage\HowWorkController@update')->name('admin.home_page.update_how_work');
    Route::post('/store/how-work/item', 'BackEnd\HomePage\HowWorkController@store')->name('admin.home_page.store_how_work_item');
    Route::put('/update/how-work/item', 'BackEnd\HomePage\HowWorkController@update_feature')->name('admin.home_page.update_how_work_item');
    Route::post('delete-how-work-item/{id}', 'BackEnd\HomePage\HowWorkController@delete')->name('admin.home_page.delete_how_work_item');
    Route::post('bulk-delete-how-work/item', 'BackEnd\HomePage\HowWorkController@bulk_delete')->name('admin.home_page.bulk_delete_how_work_item');

    Route::get('/partner', 'BackEnd\HomePage\PartnerController@index')->name('admin.home_page.partner');
    Route::post('/update-partner-section', 'BackEnd\HomePage\PartnerController@update')->name('admin.home_page.update_partner_section');
    Route::post('/store/partner', 'BackEnd\HomePage\PartnerController@store')->name('admin.home_page.store_partner');
    Route::put('/update/partner', 'BackEnd\HomePage\PartnerController@update_partner')->name('admin.home_page.update_partner');
    Route::post('delete-partner/{id}', 'BackEnd\HomePage\PartnerController@delete')->name('admin.home_page.delete_partner');
    Route::post('bulk-delete-how-work/item', 'BackEnd\HomePage\PartnerController@bulk_delete')->name('admin.home_page.bulk_delete_how_work_item');
  });

  Route::group(['middleware' => 'permission:Withdraw Method'], function () {
    Route::get('withdraw/payment-methods', 'BackEnd\WithdrawPaymentMethodController@index')->name('admin.withdraw.payment_method');
    Route::post('withdraw/payment-methods/store', 'BackEnd\WithdrawPaymentMethodController@store')->name('admin.withdraw_payment_method.store');
    Route::put('withdraw/payment-methods/update', 'BackEnd\WithdrawPaymentMethodController@update')->name('admin.withdraw_payment_method.update');
    Route::post('withdraw/payment-methods/delete/{id}', 'BackEnd\WithdrawPaymentMethodController@destroy')->name('admin.withdraw_payment_method.delete');

    Route::get('withdraw/payment-method/input', 'BackEnd\WithdrawPaymentMethodInputController@index')->name('admin.withdraw_payment_method.mange_input');
    Route::post('withdraw/payment-method/input-store', 'BackEnd\WithdrawPaymentMethodInputController@store')->name('admin.withdraw_payment_method.store_input');
    Route::get('withdraw/payment-method/input-edit/{id}', 'BackEnd\WithdrawPaymentMethodInputController@edit')->name('admin.withdraw_payment_method.edit_input');
    Route::get('withdraw/payment-method/input-edit/{id}', 'BackEnd\WithdrawPaymentMethodInputController@edit')->name('admin.withdraw_payment_method.edit_input');
    Route::post('withdraw/payment-method/input-update', 'BackEnd\WithdrawPaymentMethodInputController@update')->name('admin.withdraw_payment_method.update_input');
    Route::post('withdraw/payment-method/order-update', 'BackEnd\WithdrawPaymentMethodInputController@order_update')->name('admin.withdraw_payment_method.order_update');
    Route::get('withdraw/payment-method/input-option/{id}', 'BackEnd\WithdrawPaymentMethodInputController@get_options')->name('admin.withdraw_payment_method.options');
    Route::post('withdraw/payment-method/input-delete', 'BackEnd\WithdrawPaymentMethodInputController@delete')->name('admin.withdraw_payment_method.options_delete');

    Route::get('withdraw/withdraw-request', 'BackEnd\WithdrawController@index')->name('admin.withdraw.withdraw_request');
    Route::post('withdraw/withdraw-request/delete', 'BackEnd\WithdrawController@delete')->name('admin.witdraw.delete_withdraw');
    Route::get('withdraw/withdraw-request/approve/{id}', 'BackEnd\WithdrawController@approve')->name('admin.witdraw.approve_withdraw');
    Route::get('withdraw/withdraw-request/decline/{id}', 'BackEnd\WithdrawController@decline')->name('admin.witdraw.decline_withdraw');
  });



  Route::get('send-mail-template',  'BackEnd\Organizer\OrganizerManagementController@send_mail_template')->name('send.mail-tempalte');

  // organizer management route start
  Route::prefix('/organizer-management')->middleware('permission:Organizer Mangement')->group(function () {

    Route::get('/settings', 'BackEnd\Organizer\OrganizerManagementController@settings')->name('admin.organizer_management.settings');
    Route::post('/settings/update', 'BackEnd\Organizer\OrganizerManagementController@update_setting')->name('admin.organizer_management.setting.update');

    Route::get('/add-organzer', 'BackEnd\Organizer\OrganizerManagementController@add')->name('admin.organizer_management.add_organizer');
    Route::post('/save-organzer', 'BackEnd\Organizer\OrganizerManagementController@create')->name('admin.organizer_management.save-organizer');

    Route::get('/registered-organizers', 'BackEnd\Organizer\OrganizerManagementController@index')->name('admin.organizer_management.registered_organizer');



    Route::prefix('/organizer/{id}')->group(function () {
      Route::post('/update-email-status', 'BackEnd\Organizer\OrganizerManagementController@updateEmailStatus')->name('admin.organizer_management.organizer.update_email_status');

      Route::post('/update-account-status', 'BackEnd\Organizer\OrganizerManagementController@updateAccountStatus')->name('admin.organizer_management.organizer.update_account_status');

      Route::get('/details', 'BackEnd\Organizer\OrganizerManagementController@show')->name('admin.organizer_management.organizer_details');

      Route::get('/edit', 'BackEnd\Organizer\OrganizerManagementController@edit')->name('admin.edit_management.organizer_edit');

      Route::post('/update', 'BackEnd\Organizer\OrganizerManagementController@update')->name('admin.organizer_management.organizer.update_organizer');

      Route::post('/update/organizer/balance', 'BackEnd\Organizer\OrganizerManagementController@update_organizer_balance')->name('admin.organizer_management.update_organizer_balance');




      Route::get('/change-password', 'BackEnd\Organizer\OrganizerManagementController@changePassword')->name('admin.organizer_management.organizer.change_password');

      Route::post('/update-password', 'BackEnd\Organizer\OrganizerManagementController@updatePassword')->name('admin.organizer_management.organizer.update_password');

      Route::post('/delete', 'BackEnd\Organizer\OrganizerManagementController@destroy')->name('admin.organizer_management.organizer.delete');

      Route::get('/secret-login', 'BackEnd\Organizer\OrganizerManagementController@secret_login')->name('admin.organizer_management.organizer.secret_login');
    });

    Route::post('/bulk-delete-user', 'BackEnd\Organizer\OrganizerManagementController@bulkDestroy')->name('admin.organizer_management.bulk_delete_organizer');

    Route::get('/subscribers', 'BackEnd\User\SubscriberController@index')->name('admin.user_management.subscribers');

    Route::post('/subscriber/{id}/delete', 'BackEnd\User\SubscriberController@destroy')->name('admin.user_management.subscriber.delete');

    Route::post('/bulk-delete-subscriber', 'BackEnd\User\SubscriberController@bulkDestroy')->name('admin.user_management.bulk_delete_subscriber');

    Route::get('/mail-for-subscribers', 'BackEnd\User\SubscriberController@writeEmail')->name('admin.user_management.mail_for_subscribers');

    Route::post('/subscribers/send-email', 'BackEnd\User\SubscriberController@sendEmail')->name('admin.user_management.subscribers.send_email');

    Route::prefix('/push-notification')->group(function () {
      Route::get('/settings', 'BackEnd\User\PushNotificationController@settings')->name('admin.user_management.push_notification.settings');

      Route::post('/update-settings', 'BackEnd\User\PushNotificationController@updateSettings')->name('admin.user_management.push_notification.update_settings');

      Route::get('/notification-for-visitors', 'BackEnd\User\PushNotificationController@writeNotification')->name('admin.user_management.push_notification.notification_for_visitors');

      Route::post('/send-notification', 'BackEnd\User\PushNotificationController@sendNotification')->name('admin.user_management.push_notification.send_notification');
    });
  });
  // organizer management route end

  // organizer management route start
  Route::prefix('/customer-management')->middleware('permission:Customer Management')->group(function () {

    Route::get('/registered-customer', 'BackEnd\CustomerManagementController@index')->name('admin.organizer_management.registered_customer');

    Route::get('/add-customer', 'BackEnd\CustomerManagementController@create')->name('admin.organizer_management.add_customer');

    Route::post('/store-customer', 'BackEnd\CustomerManagementController@store')->name('admin.organizer_management.store_customer');

    Route::prefix('/customer/{id}')->group(function () {
      Route::post('/update-email-status', 'BackEnd\CustomerManagementController@updateEmailStatus')->name('admin.customer_management.customer.update_email_status');

      Route::post('/update-account-status', 'BackEnd\CustomerManagementController@updateAccountStatus')->name('admin.customer_management.customer.update_account_status');

      Route::get('/details', 'BackEnd\CustomerManagementController@show')->name('admin.customer_management.customer_details');

      Route::get('/edit', 'BackEnd\CustomerManagementController@edit')->name('admin.customer_management.customer_edit');

      Route::post('/update', 'BackEnd\CustomerManagementController@update')->name('admin.customer_management.customer.update_customer');

      Route::get('/change-password', 'BackEnd\CustomerManagementController@changePassword')->name('admin.customer_management.customer.change_password');

      Route::post('/update-password', 'BackEnd\CustomerManagementController@updatePassword')->name('admin.customer_management.customer.update_password');

      Route::post('/customer-delete', 'BackEnd\CustomerManagementController@destroy')->name('admin.customer_management.customer_delete');

      Route::get('/secret-login', 'BackEnd\CustomerManagementController@secret_login')->name('admin.customer_management.secret_login');
    });
  });
  Route::post('bulk/delete-customer', 'BackEnd\CustomerManagementController@bulkDestroy')->name('admin.customer_management.bulk_delete_customer');
  // organizer management route end


  #====support tickets ============
  Route::group(['middleware' => 'permission:Support Ticket'], function () {
    Route::get('ticket/setting', 'BackEnd\SupportTicketController@setting')->name('admin.support_ticket.setting');
    Route::post('ticket/setting/update', 'BackEnd\SupportTicketController@update_setting')->name('admin.support_ticket.update_setting');
    Route::get('support/tickets', 'BackEnd\SupportTicketController@index')->name('admin.support_tickets');
    Route::get('support/message/{id}', 'BackEnd\SupportTicketController@message')->name('admin.support_tickets.message');
    Route::post('support-ticket/zip-upload', 'BackEnd\SupportTicketController@zip_file_upload')->name('admin.support_ticket.zip_file.upload');
    Route::post('support-ticket/reply/{id}', 'BackEnd\SupportTicketController@ticketreply')->name('admin.support_ticket.reply');
    Route::post('support-ticket/closed/{id}', 'BackEnd\SupportTicketController@ticket_closed')->name('admin.support_ticket.close');
    Route::post('support-ticket/assign-stuff/{id}', 'BackEnd\SupportTicketController@assign_stuff')->name('assign_stuff.supoort.ticket');

    Route::get('support-ticket/unassign-stuff/{id}', 'BackEnd\SupportTicketController@unassign_stuff')->name('admin.support_tickets.unassign');

    Route::post('support-ticket/delete/{id}', 'BackEnd\SupportTicketController@delete')->name('admin.support_tickets.delete');
    Route::post('support-ticket/bulk/delete/', 'BackEnd\SupportTicketController@bulk_delete')->name('admin.support_tickets.bulk_delete');
  });



  //shop related routes are goes here=====
  Route::get('product/setting', 'BackEnd\ShopManagement\ProductController@settings')->name('admin.product.setting');
  Route::post('product/setting/update', 'BackEnd\ShopManagement\ProductController@setting_update')->name('admin.product.setting.update');



  Route::prefix('shop-management')->middleware(['permission:Shop Management'])->group(function () {
    Route::get('/shipping', 'BackEnd\ShopManagement\SettingController@index')->name('admin.shop_management.shipping_charge');
    Route::post('/shipping/store', 'BackEnd\ShopManagement\SettingController@store')->name('admin.shop_management.store_shipping');
    Route::post('/shipping/delete/', 'BackEnd\ShopManagement\SettingController@delete')->name('admin.shop_management.delete_shipping');
    Route::post('/shipping/bulk-delete/', 'BackEnd\ShopManagement\SettingController@bulkdelete')->name('admin.shop_management.bulk_delete_shipping_charge');
    Route::put('/shipping/update/', 'BackEnd\ShopManagement\SettingController@update')->name('admin.shop_management.update_shipping');

    Route::get('/coupon', 'BackEnd\ShopManagement\ShopCouponController@index')->name('admin.shop_management.coupon');
    Route::post('/coupon/store', 'BackEnd\ShopManagement\ShopCouponController@store')->name('admin.shop_management.store_coupon');
    Route::put('/coupon/update', 'BackEnd\ShopManagement\ShopCouponController@update')->name('admin.shop_management.update_coupon');
    Route::post('/coupon/delete', 'BackEnd\ShopManagement\ShopCouponController@destroy')->name('admin.shop_management.delete_coupon');
    Route::post('/coupon/bulk-delete', 'BackEnd\ShopManagement\ShopCouponController@bulk_destroy')->name('admin.shop_management.bulk_delete_coupon');

    Route::get('/category', 'BackEnd\ShopManagement\CategoryController@index')->name('admin.shop_management.category');
    Route::post('/category/store', 'BackEnd\ShopManagement\CategoryController@store')->name('admin.shop_management.store_category');
    Route::post('/category/update/feature/{id}', 'BackEnd\ShopManagement\CategoryController@update_featured')->name('admin.shop_management.update_category_feature');
    Route::put('/category/update', 'BackEnd\ShopManagement\CategoryController@update')->name('admin.shop_management.update_category');
    Route::post('/category/delete/{id}', 'BackEnd\ShopManagement\CategoryController@delete')->name('admin.shop_management.delete_category');
    Route::post('/category/bulk-delete/', 'BackEnd\ShopManagement\CategoryController@bulk_delete')->name('admin.shop_management.bulk_delete_category');

    Route::get('product/type', 'BackEnd\ShopManagement\ProductController@index')->name('admin.shop_management.product_type');
    Route::get('product/create', 'BackEnd\ShopManagement\ProductController@create')->name('admin.shop_management.product.create');
    Route::post('product/img-store', 'BackEnd\ShopManagement\ProductController@imgstore')->name('admin.shop_management.product.imgstore');
    Route::post('product/img-remove', 'BackEnd\ShopManagement\ProductController@imgrmv')->name('admin.shop_management.product.imgrmv');
    Route::post('product/store', 'BackEnd\ShopManagement\ProductController@store')->name('admin.shop_management.product.store');
    Route::get('products', 'BackEnd\ShopManagement\ProductController@show')->name('admin.shop_management.products');
    Route::post('product/status-update', 'BackEnd\ShopManagement\ProductController@status_update')->name('admin.shop_management.product.status_update');
    Route::post('product/feature-update', 'BackEnd\ShopManagement\ProductController@feature_update')->name('admin.shop_management.product.update_feature');
    Route::get('product/edit', 'BackEnd\ShopManagement\ProductController@edit')->name('admin.shop_management.product.edit');
    Route::get('product/images/{id}', 'BackEnd\ShopManagement\ProductController@load_images')->name('admin.shop_management.product.images');
    Route::post('product/destroy', 'BackEnd\ShopManagement\ProductController@destroy')->name('admin.shop_management.product.destroy');
    Route::post('product/bulk-destroy', 'BackEnd\ShopManagement\ProductController@bulk_destroy')->name('admin.shop_management.product.bulk_delete');

    Route::post('/product/img-dbrmv', 'BackEnd\ShopManagement\ProductController@imagedbrmv')->name('admin.shop_management.imgdbrmv');
    Route::post('/product/update', 'BackEnd\ShopManagement\ProductController@update')->name('admin.shop_management.product.update');

    Route::get('/orders', 'BackEnd\ShopManagement\ProductOrderController@index')->name('admin.product.order');
    Route::post('/orders/delete/{id}', 'BackEnd\ShopManagement\ProductOrderController@delete')->name('admin.product.order.delete');
    Route::post('/orders/bulk-delete/', 'BackEnd\ShopManagement\ProductOrderController@bulk_delete')->name('admin.product.order.bulk_delete');
    Route::post('/orders/update-status/{id}', 'BackEnd\ShopManagement\ProductOrderController@updateStatus')->name('admin.order.update_payment_status');

    Route::post('/orders/update-order-status/{id}', 'BackEnd\ShopManagement\ProductOrderController@updateOrderStatus')->name('admin.order.update_order_status');

    Route::get('/order/details/{id}', 'BackEnd\ShopManagement\ProductOrderController@details')->name('admin.product_order.details');

    Route::get('/product-order/report', 'BackEnd\ShopManagement\ProductOrderController@report')->name('admin.product_order.report');
    Route::get('/product-order/export', 'BackEnd\ShopManagement\ProductOrderController@export')->name('admin.product_order.export');
  });



  // language management route start
  Route::get('/edit-keywords', 'BackEnd\LanguageController@adminKeywordsEdit')->name('admin.edit_admin_keywords');
  Route::post('/update-keywords', 'BackEnd\LanguageController@adminKeywordsUpdate')->name('admin.update_admin_keywords');

  Route::prefix('/language-management')->middleware('permission:Language Management')->group(function () {
    Route::get('', 'BackEnd\LanguageController@index')->name('admin.language_management');

    Route::post('/store-language', 'BackEnd\LanguageController@store')->name('admin.language_management.store_language');

    Route::post('/{id}/make-default-language', 'BackEnd\LanguageController@makeDefault')->name('admin.language_management.make_default_language');

    Route::post('/update-language', 'BackEnd\LanguageController@update')->name('admin.language_management.update_language');

    Route::get('/{id}/edit-keyword', 'BackEnd\LanguageController@editKeyword')->name('admin.language_management.edit_keyword');

    Route::post('add-keyword', 'BackEnd\LanguageController@addKeyword')->name('admin.language_management.add_keyword');

    Route::post('/{id}/update-keyword', 'BackEnd\LanguageController@updateKeyword')->name('admin.language_management.update_keyword');

    Route::post('/{id}/delete-language', 'BackEnd\LanguageController@destroy')->name('admin.language_management.delete_language');

    Route::get('/{id}/check-rtl', 'BackEnd\LanguageController@checkRTL');
  });
  // language management route end


  Route::prefix('/basic-settings')->middleware('permission:Basic Settings')->group(function () {
    // basic settings favicon route
    Route::get('/favicon', 'BackEnd\BasicSettings\BasicController@favicon')->name('admin.basic_settings.favicon');

    Route::post('/update-favicon', 'BackEnd\BasicSettings\BasicController@updateFavicon')->name('admin.basic_settings.update_favicon');

    // basic settings logo route
    Route::get('/logo', 'BackEnd\BasicSettings\BasicController@logo')->name('admin.basic_settings.logo');

    Route::post('/update-logo', 'BackEnd\BasicSettings\BasicController@updateLogo')->name('admin.basic_settings.update_logo');

    // basic settings information route
    Route::get('/contact-page', 'BackEnd\BasicSettings\BasicController@information')->name('admin.basic_settings.contact_page');

    Route::post('/update-info', 'BackEnd\BasicSettings\BasicController@updateInfo')->name('admin.basic_settings.update_info');

    Route::get('/general-settings', 'BackEnd\BasicSettings\BasicController@general_settings')->name('admin.basic_settings.general_settings');

    Route::post('/update-general-settings', 'BackEnd\BasicSettings\BasicController@update_general_setting')->name('admin.basic_settings.general_settings.update');

    // basic settings (theme & home) route
    Route::get('/theme-and-home', 'BackEnd\BasicSettings\BasicController@themeAndHome')->name('admin.basic_settings.theme_and_home');

    Route::post('/update-theme-and-home', 'BackEnd\BasicSettings\BasicController@updateThemeAndHome')->name('admin.basic_settings.update_theme_and_home');

    // basic settings currency route
    Route::get(
      '/currency',
      'BackEnd\BasicSettings\BasicController@currency'
    )->name('admin.basic_settings.currency');

    Route::post('/update-currency', 'BackEnd\BasicSettings\BasicController@updateCurrency')->name('admin.basic_settings.update_currency');

    // basic settings appearance route
    Route::get(
      '/appearance',
      'BackEnd\BasicSettings\BasicController@appearance'
    )->name('admin.basic_settings.appearance');

    Route::post('/update-appearance', 'BackEnd\BasicSettings\BasicController@updateAppearance')->name('admin.basic_settings.update_appearance');

    // basic settings mail route start
    Route::get('/mail-from-admin', 'BackEnd\BasicSettings\BasicController@mailFromAdmin')->name('admin.basic_settings.mail_from_admin');

    Route::post('/update-mail-from-admin', 'BackEnd\BasicSettings\BasicController@updateMailFromAdmin')->name('admin.basic_settings.update_mail_from_admin');

    Route::get('/mail-to-admin', 'BackEnd\BasicSettings\BasicController@mailToAdmin')->name('admin.basic_settings.mail_to_admin');

    Route::post('/update-mail-to-admin', 'BackEnd\BasicSettings\BasicController@updateMailToAdmin')->name('admin.basic_settings.update_mail_to_admin');

    Route::get('/mail-templates', 'BackEnd\BasicSettings\MailTemplateController@index')->name('admin.basic_settings.mail_templates');

    Route::get('/edit-mail-template/{id}', 'BackEnd\BasicSettings\MailTemplateController@edit')->name('admin.basic_settings.edit_mail_template');

    Route::post('/update-mail-template/{id}', 'BackEnd\BasicSettings\MailTemplateController@update')->name('admin.basic_settings.update_mail_template');
    // basic settings mail route end

    // basic settings breadcrumb route
    Route::get(
      '/breadcrumb',
      'BackEnd\BasicSettings\BasicController@breadcrumb'
    )->name('admin.basic_settings.breadcrumb');

    Route::post('/update-breadcrumb', 'BackEnd\BasicSettings\BasicController@updateBreadcrumb')->name('admin.basic_settings.update_breadcrumb');

    // basic settings page-headings route
    Route::get('/page-headings', 'BackEnd\BasicSettings\PageHeadingController@pageHeadings')->name('admin.basic_settings.page_headings');

    Route::post('/update-page-headings', 'BackEnd\BasicSettings\PageHeadingController@updatePageHeadings')->name('admin.basic_settings.update_page_headings');

    // basic settings plugins route start
    Route::get('/plugins', 'BackEnd\BasicSettings\BasicController@plugins')->name('admin.basic_settings.plugins');

    Route::post('/update-recaptcha', 'BackEnd\BasicSettings\BasicController@updateRecaptcha')->name('admin.basic_settings.update_recaptcha');

    Route::post('/update-disqus', 'BackEnd\BasicSettings\BasicController@updateDisqus')->name('admin.basic_settings.update_disqus');

    Route::post('/update-facebook', 'BackEnd\BasicSettings\BasicController@updateFacebook')->name('admin.basic_settings.update_facebook');

    Route::post('/update-google', 'BackEnd\BasicSettings\BasicController@updateGoogle')->name('admin.basic_settings.update_google');

    Route::post('/update-whatsapp', 'BackEnd\BasicSettings\BasicController@updateWhatsApp')->name('admin.basic_settings.update_whatsapp');
    // basic settings plugins route end

    // basic settings seo route
    Route::get('/seo', 'BackEnd\BasicSettings\SEOController@index')->name('admin.basic_settings.seo');

    Route::post(
      '/update-seo',
      'BackEnd\BasicSettings\SEOController@update'
    )->name('admin.basic_settings.update_seo');

    // basic settings maintenance-mode route
    Route::get('/maintenance-mode', 'BackEnd\BasicSettings\BasicController@maintenance')->name('admin.basic_settings.maintenance_mode');

    Route::post('/update-maintenance-mode', 'BackEnd\BasicSettings\BasicController@updateMaintenance')->name('admin.basic_settings.update_maintenance_mode');

    // basic settings cookie-alert route
    Route::get('/cookie-alert', 'BackEnd\BasicSettings\CookieAlertController@cookieAlert')->name('admin.basic_settings.cookie_alert');

    Route::post('/update-cookie-alert', 'BackEnd\BasicSettings\CookieAlertController@updateCookieAlert')->name('admin.basic_settings.update_cookie_alert');

    // basic settings footer-logo route
    Route::get('/footer-logo', 'BackEnd\BasicSettings\BasicController@footerLogo')->name('admin.basic_settings.footer_logo');

    Route::post('/update-footer-logo', 'BackEnd\BasicSettings\BasicController@updateFooterLogo')->name('admin.basic_settings.update_footer_logo');

    // basic-settings social-media route
    Route::get('/social-medias', 'BackEnd\BasicSettings\SocialMediaController@index')->name('admin.basic_settings.social_medias');

    Route::post('/store-social-media', 'BackEnd\BasicSettings\SocialMediaController@store')->name('admin.basic_settings.store_social_media');

    Route::put('/update-social-media', 'BackEnd\BasicSettings\SocialMediaController@update')->name('admin.basic_settings.update_social_media');

    Route::post('/delete-social-media/{id}', 'BackEnd\BasicSettings\SocialMediaController@destroy')->name('admin.basic_settings.delete_social_media');
  });


  // announcement-popup route start
  Route::prefix('/announcement-popups')->middleware('permission:Announcement Popups')->group(function () {
    Route::get('', 'BackEnd\PopupController@index')->name('admin.announcement_popups');

    Route::get('/select-popup-type', 'BackEnd\PopupController@popupType')->name('admin.announcement_popups.select_popup_type');

    Route::get('/create-popup/{type}', 'BackEnd\PopupController@create')->name('admin.announcement_popups.create_popup');

    Route::post('/store-popup', 'BackEnd\PopupController@store')->name('admin.announcement_popups.store_popup');

    Route::post('/popup/{id}/update-status', 'BackEnd\PopupController@updateStatus')->name('admin.announcement_popups.update_popup_status');

    Route::get('/edit-popup/{id}', 'BackEnd\PopupController@edit')->name('admin.announcement_popups.edit_popup');

    Route::post('/update-popup/{id}', 'BackEnd\PopupController@update')->name('admin.announcement_popups.update_popup');

    Route::post('/delete-popup/{id}', 'BackEnd\PopupController@destroy')->name('admin.announcement_popups.delete_popup');

    Route::post('/bulk-delete-popup', 'BackEnd\PopupController@bulkDestroy')->name('admin.announcement_popups.bulk_delete_popup');
  });
  // announcement-popup route end


  // menu-builder route start
  Route::prefix('/menu-builder')->middleware('permission:Menu Builder')->group(function () {
    Route::get('', 'BackEnd\MenuBuilderController@index')->name('admin.menu_builder');

    Route::post('/update-menus', 'BackEnd\MenuBuilderController@update')->name('admin.menu_builder.update_menus');
  });
  // menu-builder route end


  // home-page route start
  Route::prefix('/home-page')->middleware('permission:Home Page')->group(function () {
    // hero section
    Route::get('/hero-section', 'BackEnd\HomePage\HeroController@index')->name('admin.home_page.hero_section');

    Route::post('/update-hero-section', 'BackEnd\HomePage\HeroController@update')->name('admin.home_page.update_hero_section');

    // section title
    Route::get('/section-titles', 'BackEnd\HomePage\SectionTitleController@index')->name('admin.home_page.section_titles');

    Route::post('/update-section-titles', 'BackEnd\HomePage\SectionTitleController@update')->name('admin.home_page.update_section_title');



    // features section
    Route::get('/features-section', 'BackEnd\HomePage\FeatureController@index')->name('admin.home_page.features_section');

    Route::post('/update-feature-section-image', 'BackEnd\HomePage\FeatureController@updateImage')->name('admin.home_page.update_feature_section_image');

    Route::post('/store-feature', 'BackEnd\HomePage\FeatureController@store')->name('admin.home_page.store_feature');

    Route::put('/update-feature', 'BackEnd\HomePage\FeatureController@update')->name('admin.home_page.update_feature');

    Route::post('/delete-feature/{id}', 'BackEnd\HomePage\FeatureController@destroy')->name('admin.home_page.delete_feature');

    Route::post('/bulk-delete-feature', 'BackEnd\HomePage\FeatureController@bulkDestroy')->name('admin.home_page.bulk_delete_feature');


    // testimonials section
    Route::get('/testimonials-section', 'BackEnd\HomePage\TestimonialController@index')->name('admin.home_page.testimonials_section');

    Route::post('/update-testimonial-section-image', 'BackEnd\HomePage\TestimonialController@updateImage')->name('admin.home_page.update_testimonial_section_image');

    Route::post('/store-testimonial', 'BackEnd\HomePage\TestimonialController@store')->name('admin.home_page.store_testimonial');

    Route::post('/update-testimonial', 'BackEnd\HomePage\TestimonialController@update')->name('admin.home_page.update_testimonial');

    Route::post('/delete-testimonial/{id}', 'BackEnd\HomePage\TestimonialController@destroy')->name('admin.home_page.delete_testimonial');

    Route::post('/bulk-delete-testimonial', 'BackEnd\HomePage\TestimonialController@bulkDestroy')->name('admin.home_page.bulk_delete_testimonial');

    // about-us section
    Route::get('/about-us-section', 'BackEnd\HomePage\AboutUsController@index')->name('admin.home_page.about_us_section');

    Route::post('/update-about-us-section', 'BackEnd\HomePage\AboutUsController@update')->name('admin.home_page.update_about_us_section');

    // section customization
    Route::get('/section-customization', 'BackEnd\HomePage\SectionController@index')->name('admin.home_page.section_customization');

    Route::post('/update-section-status', 'BackEnd\HomePage\SectionController@update')->name('admin.home_page.update_section_status');
  });
  // home-page route end


  // payment-gateway route start
  Route::prefix('/payment-gateways')->middleware('permission:Payment Gateways')->group(function () {
    Route::get('/online-gateways', 'BackEnd\PaymentGateway\OnlineGatewayController@index')->name('admin.payment_gateways.online_gateways');

    Route::post('/update-paypal-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updatePayPalInfo')->name('admin.payment_gateways.update_paypal_info');

    Route::post('/update-instamojo-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateInstamojoInfo')->name('admin.payment_gateways.update_instamojo_info');

    Route::post('/update-paystack-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updatePaystackInfo')->name('admin.payment_gateways.update_paystack_info');

    Route::post('/update-flutterwave-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateFlutterwaveInfo')->name('admin.payment_gateways.update_flutterwave_info');

    Route::post('/update-razorpay-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateRazorpayInfo')->name('admin.payment_gateways.update_razorpay_info');

    Route::post('/update-mercadopago-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateMercadoPagoInfo')->name('admin.payment_gateways.update_mercadopago_info');

    Route::post('/update-mollie-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateMollieInfo')->name('admin.payment_gateways.update_mollie_info');

    Route::post('/update-stripe-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateStripeInfo')->name('admin.payment_gateways.update_stripe_info');

    Route::post('/update-paytm-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updatePaytmInfo')->name('admin.payment_gateways.update_paytm_info');

    Route::get('/offline-gateways', 'BackEnd\PaymentGateway\OfflineGatewayController@index')->name('admin.payment_gateways.offline_gateways');

    Route::post('/store-offline-gateway', 'BackEnd\PaymentGateway\OfflineGatewayController@store')->name('admin.payment_gateways.store_offline_gateway');

    Route::post('/update-status/{id}', 'BackEnd\PaymentGateway\OfflineGatewayController@updateStatus')->name('admin.payment_gateways.update_status');

    Route::post('/update-offline-gateway', 'BackEnd\PaymentGateway\OfflineGatewayController@update')->name('admin.payment_gateways.update_offline_gateway');

    Route::post('/delete-offline-gateway/{id}', 'BackEnd\PaymentGateway\OfflineGatewayController@destroy')->name('admin.payment_gateways.delete_offline_gateway');
  });
  // payment-gateway route end


  // blog route start
  Route::prefix('/blog-management')->middleware('permission:Blog Management')->group(function () {
    Route::get(
      '/categories',
      'BackEnd\Journal\CategoryController@index'
    )->name('admin.blog_management.categories');

    Route::post('/store-category', 'BackEnd\Journal\CategoryController@store')->name('admin.blog_management.store_category');

    Route::put('/update-category', 'BackEnd\Journal\CategoryController@update')->name('admin.blog_management.update_category');

    Route::post('/delete-category/{id}', 'BackEnd\Journal\CategoryController@destroy')->name('admin.blog_management.delete_category');

    Route::post('/bulk-delete-category', 'BackEnd\Journal\CategoryController@bulkDestroy')->name('admin.blog_management.bulk_delete_category');

    Route::get('/blogs', 'BackEnd\Journal\BlogController@index')->name('admin.blog_management.blogs');

    Route::get('/create-blog', 'BackEnd\Journal\BlogController@create')->name('admin.blog_management.create_blog');

    Route::post('/store-blog', 'BackEnd\Journal\BlogController@store')->name('admin.blog_management.store_blog');

    Route::get('/edit-blog/{id}', 'BackEnd\Journal\BlogController@edit')->name('admin.blog_management.edit_blog');

    Route::post('/update-blog/{id}', 'BackEnd\Journal\BlogController@update')->name('admin.blog_management.update_blog');

    Route::post('/delete-blog/{id}', 'BackEnd\Journal\BlogController@destroy')->name('admin.blog_management.delete_blog');

    Route::post('/bulk-delete-blog', 'BackEnd\Journal\BlogController@bulkDestroy')->name('admin.blog_management.bulk_delete_blog');
  });
  // blog route end


  // faq route start
  Route::prefix('/faq-management')->middleware('permission:FAQ Management')->group(function () {
    Route::get('', 'BackEnd\FaqController@index')->name('admin.faq_management');

    Route::post('/store-faq', 'BackEnd\FaqController@store')->name('admin.faq_management.store_faq');

    Route::post('/update-faq', 'BackEnd\FaqController@update')->name('admin.faq_management.update_faq');

    Route::post('/delete-faq/{id}', 'BackEnd\FaqController@destroy')->name('admin.faq_management.delete_faq');

    Route::post('/bulk-delete-faq', 'BackEnd\FaqController@bulkDestroy')->name('admin.faq_management.bulk_delete_faq');
  });
  // faq route end

  // custom-pages route start
  Route::prefix('/custom-pages')->middleware('permission:Custom Pages')->group(function () {
    Route::get('', 'BackEnd\CustomPageController@index')->name('admin.custom_pages');

    Route::get('/create-page', 'BackEnd\CustomPageController@create')->name('admin.custom_pages.create_page');

    Route::post('/store-page', 'BackEnd\CustomPageController@store')->name('admin.custom_pages.store_page');

    Route::get('/edit-page/{id}', 'BackEnd\CustomPageController@edit')->name('admin.custom_pages.edit_page');

    Route::post('/update-page/{id}', 'BackEnd\CustomPageController@update')->name('admin.custom_pages.update_page');

    Route::post('/delete-page/{id}', 'BackEnd\CustomPageController@destroy')->name('admin.custom_pages.delete_page');

    Route::post('/bulk-delete-page', 'BackEnd\CustomPageController@bulkDestroy')->name('admin.custom_pages.bulk_delete_page');
  });
  // custom-pages route end

  // advertise route start
  Route::prefix('/advertise')->middleware('permission:Advertise')->group(function () {
    Route::get('/settings', 'BackEnd\BasicSettings\BasicController@advertiseSettings')->name('admin.advertise.settings');

    Route::post('/update-settings', 'BackEnd\BasicSettings\BasicController@updateAdvertiseSettings')->name('admin.advertise.update_settings');

    Route::get('/advertisements', 'BackEnd\AdvertisementController@index')->name('admin.advertise.advertisements');

    Route::post('/store-advertisement', 'BackEnd\AdvertisementController@store')->name('admin.advertise.store_advertisement');

    Route::post('/update-advertisement', 'BackEnd\AdvertisementController@update')->name('admin.advertise.update_advertisement');

    Route::post('/delete-advertisement/{id}', 'BackEnd\AdvertisementController@destroy')->name('admin.advertise.delete_advertisement');

    Route::post('/bulk-delete-advertisement', 'BackEnd\AdvertisementController@bulkDestroy')->name('admin.advertise.bulk_delete_advertisement');
  });
  // advertise route end


  // footer route start
  Route::prefix('/footer')->middleware('permission:Footer')->group(function () {
    Route::get('/content', 'BackEnd\Footer\ContentController@index')->name('admin.footer.content');

    Route::post('/update-content', 'BackEnd\Footer\ContentController@update')->name('admin.footer.update_content');

    Route::get('/quick-links', 'BackEnd\Footer\QuickLinkController@index')->name('admin.footer.quick_links');

    Route::post('/create-quick-link', 'BackEnd\Footer\QuickLinkController@store')->name('admin.footer.create_quick_link');

    Route::post('/update-quick-link', 'BackEnd\Footer\QuickLinkController@update')->name('admin.footer.update_quick_link');

    Route::post('/delete-quick-link/{id}', 'BackEnd\Footer\QuickLinkController@destroy')->name('admin.footer.delete_quick_link');

    Route::get('contact-page', 'BackEnd\ContactController@index')->name('admin.contact.page');
    Route::post('update/contact-page/{lagnid}', 'BackEnd\ContactController@update')->name('admin.update.contact_page');
  });
  // footer route end


  // subscriber route start
  Route::get('/subscribers', 'BackEnd\User\SubscriberController@index')->name('admin.user_management.subscribers');

  Route::post(
    '/subscriber/{id}/delete',
    'BackEnd\User\SubscriberController@destroy'
  )->name('admin.user_management.subscriber.delete');

  Route::post(
    '/bulk-delete-subscriber',
    'BackEnd\User\SubscriberController@bulkDestroy'
  )->name('admin.user_management.bulk_delete_subscriber');

  Route::get('/mail-for-subscribers', 'BackEnd\User\SubscriberController@writeEmail')->name('admin.user_management.mail_for_subscribers');

  Route::post(
    '/subscribers/send-email',
    'BackEnd\User\SubscriberController@sendEmail'
  )->name('admin.user_management.subscribers.send_email');

  Route::prefix('/push-notification')->group(function () {
    Route::get('/settings', 'BackEnd\User\PushNotificationController@settings')->name('admin.user_management.push_notification.settings');

    Route::post('/update-settings', 'BackEnd\User\PushNotificationController@updateSettings')->name('admin.user_management.push_notification.update_settings');

    Route::get('/notification-for-visitors', 'BackEnd\User\PushNotificationController@writeNotification')->name('admin.user_management.push_notification.notification_for_visitors');

    Route::post('/send-notification', 'BackEnd\User\PushNotificationController@sendNotification')->name('admin.user_management.push_notification.send_notification');
  });
  // subscriber route end


  // upload image in summernote route
  Route::prefix('/summernote')->group(function () {
    Route::post('/upload-image', 'BackEnd\SummernoteController@upload');

    Route::post('/remove-image', 'BackEnd\SummernoteController@remove');
  });
});
