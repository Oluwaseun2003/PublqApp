<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Interface Routes
|--------------------------------------------------------------------------
*/

Route::get('organizer/pwa/', 'BackEnd\Organizer\OrganizerController@pwa')->name('organizer.pwa');
Route::post('organizer/check-qrcode/', 'BackEnd\Organizer\OrganizerController@check_qrcode')->name('check-qrcode');

Route::get('organizers/email/verify', 'BackEnd\Organizer\OrganizerController@confirm_email');

Route::prefix('/organizer')->group(function () {
  Route::middleware('guest:organizer', 'change.lang', 'adminLang')->group(function () {
    Route::get('/login', 'BackEnd\Organizer\OrganizerController@login')->name('organizer.login');
    Route::get('/signup', 'BackEnd\Organizer\OrganizerController@signup')->name('organizer.signup');
    Route::post('/create', 'BackEnd\Organizer\OrganizerController@create')->name('organizer.create');
    Route::post('/store', 'BackEnd\Organizer\OrganizerController@authentication')->name('organizer.authentication');
    Route::get('/forget-password', 'BackEnd\Organizer\OrganizerController@forget_passord')->name('organizer.forget.password');
    Route::post('/send-forget-mail', 'BackEnd\Organizer\OrganizerController@forget_mail')->name('organizer.forget.mail');
    Route::get('/reset-password', 'BackEnd\Organizer\OrganizerController@reset_password')->name('organizer.reset.password');
    Route::post('/update-forget-password', 'BackEnd\Organizer\OrganizerController@update_password')->name('organizer.update-forget-password');
  });

  Route::get('/logout', 'BackEnd\Organizer\OrganizerController@logout')->name('organizer.logout');
  Route::get('/change-password', 'BackEnd\Organizer\OrganizerController@change_password')->name('organizer.change.password');
  Route::post('/update-password', 'BackEnd\Organizer\OrganizerController@updated_password')->name('organizer.update_password');
});

Route::prefix('/organizer')->middleware('auth:organizer', 'Deactive:organizer', 'EmailStatus:organizer', 'adminLang')->group(function () {
  Route::get('/dashboard', 'BackEnd\Organizer\OrganizerController@index')->name('organizer.dashboard');
  Route::get('monthly-income', 'BackEnd\Organizer\OrganizerController@monthly_income')->name('organizer.monthly_income');
  Route::get('/transaction', 'BackEnd\Organizer\OrganizerController@transaction')->name('organizer.transcation');
  Route::post('/transcation/delete', 'BackEnd\Organizer\OrganizerController@destroy')->name('organizer.transcation.delete');
  Route::post('/transcation/bulk-delete', 'BackEnd\Organizer\OrganizerController@bulk_destroy')->name('organizer.transcation.bulk_delete');

  // change admin-panel theme (dark/light) route
  Route::post('/change-theme', 'BackEnd\Organizer\OrganizerController@changeTheme')->name('organizer.change_theme');

  Route::get('/edit-profile', 'BackEnd\Organizer\OrganizerController@edit_profile')->name('organizer.edit.profile');
  Route::post('/organizer-update-profile', 'BackEnd\Organizer\OrganizerController@update_profile')->name('organizer.update_profile');

  Route::get('/verify/email', 'BackEnd\Organizer\OrganizerController@verify_email')->name('organizer.verify.email');
  Route::post('/send-verify/link', 'BackEnd\Organizer\OrganizerController@send_link')->name('organizer.send.verify.link');
  Route::get('/email/verify', 'BackEnd\Organizer\OrganizerController@confirm_email');

  Route::get('event-management/events/', 'BackEnd\Organizer\EventController@index')->name('organizer.event_management.event');
  Route::get('choose-event-type/', 'BackEnd\Organizer\EventController@choose_event_type')->name('choose-event-type');
  Route::get('add-event/', 'BackEnd\Organizer\EventController@add_event')->name('organizer.add.event.event');
  Route::post('event-imagesstore', 'BackEnd\Organizer\EventController@gallerystore')->name('organizer.event.imagesstore');
  Route::post('event-imagermv', 'BackEnd\Organizer\EventController@imagermv')->name('organizer.event.imagermv');
  Route::post('event-store', 'BackEnd\Organizer\EventController@store')->name('organizer.event_management.store_event');
  Route::post('/event/{id}/update-status', 'BackEnd\Organizer\EventController@updateStatus')->name('organizer.event_management.event.event_status');
  Route::post('/event/{id}/update-featured', 'BackEnd\Organizer\EventController@updateFeatured')->name('organizer.event_management.event.update_featured');
  Route::post('/delete-event/{id}', 'BackEnd\Organizer\EventController@destroy')->name('organizer.event_management.delete_event');
  Route::get('/edit-event/{id}', 'BackEnd\Organizer\EventController@edit')->name('organizer.event_management.edit_event');
  Route::post('/event-img-dbrmv', 'BackEnd\Organizer\EventController@imagedbrmv')->name('organizer.event.imgdbrmv');
  Route::get('/event-images/{id}', 'BackEnd\Organizer\EventController@images')->name('organizer.event.images');
  Route::post('/event-update', 'BackEnd\Organizer\EventController@update')->name('organizer.event.update');
  Route::post('bulk/delete/event', 'BackEnd\Organizer\EventController@bulk_delete')->name('organizer.event_management.bulk_delete_event');


  Route::get('event/ticket', 'BackEnd\Organizer\TicketController@index')->name('organizer.event.ticket');
  Route::get('event/add-ticket', 'BackEnd\Organizer\TicketController@create')->name('organizer.event.add.ticket');
  Route::post('event/ticket/store-ticket', 'BackEnd\Organizer\TicketController@store')->name('organizer.ticket_management.store_ticket');
  Route::get('event/edit/ticket', 'BackEnd\Organizer\TicketController@edit')->name('organizer.event.edit.ticket');
  Route::post('event/ticket/delete-ticket', 'BackEnd\Organizer\TicketController@destroy')->name('organizer.ticket_management.delete_ticket');
  Route::get('delete-variation/{id}', 'BackEnd\Organizer\TicketController@delete_variation')->name('organizer.delete.variation');
  Route::post('ticket_management/update/ticket', 'BackEnd\Organizer\TicketController@update')->name('organizer.ticket_management.update_ticket');
  Route::post('bulk/delete/bulk/event/ticket', 'BackEnd\Organizer\TicketController@bulk_delete')->name('organizer.event_management.bulk_delete_event_ticket');

  Route::get('withdraw', 'BackEnd\Organizer\OrganizerWithdrawController@index')->name('organizer.withdraw');
  Route::get('withdraw/create', 'BackEnd\Organizer\OrganizerWithdrawController@create')->name('organizer.withdraw.create');
  Route::get('/get-withdraw-method/input/{id}', 'BackEnd\Organizer\OrganizerWithdrawController@get_inputs');

  Route::get('withdraw/balance-calculation/{method}/{amount}', 'BackEnd\Organizer\OrganizerWithdrawController@balance_calculation');

  Route::post('/withdraw/send-request', 'BackEnd\Organizer\OrganizerWithdrawController@send_request')->name('organizer.withdraw.send-request');
  Route::post('/withdraw/witdraw/bulk-delete', 'BackEnd\Organizer\OrganizerWithdrawController@bulkDelete')->name('organizer.witdraw.bulk_delete_withdraw');
  Route::post('/withdraw/witdraw/delete', 'BackEnd\Organizer\OrganizerWithdrawController@Delete')->name('organizer.witdraw.delete_withdraw');

  Route::get('event-booking', 'BackEnd\Organizer\EventBookingController@index')->name('organizer.event.booking');
  Route::post('event-booking/update/payment-status/{id}', 'BackEnd\Organizer\EventBookingController@updatePaymentStatus')->name('organizer.event_booking.update_payment_status');
  Route::get('event-booking/details/{id}', 'BackEnd\Organizer\EventBookingController@show')->name('organizer.event_booking.details');
  Route::post('/{id}/delete', 'BackEnd\Organizer\EventBookingController@destroy')->name('organizer.event_booking.delete');
  Route::post('/event-booking/bulk-delete', 'BackEnd\Organizer\EventBookingController@bulkDestroy')->name('organizer.event_booking.bulk_delete');
  Route::get('/event-booking/report', 'BackEnd\Organizer\EventBookingController@report')->name('organizer.event_booking.report');
  Route::get('/event-booking/export', 'BackEnd\Organizer\EventBookingController@export')->name('organizer.event_bookings.export');


  /*
  |---------------------------------------------
  |support ticket
  |---------------------------------------------
  */


  Route::prefix('support-tikcet')->group(function () {
    Route::get('create', 'BackEnd\Organizer\SupportTicketController@create')->name('organizer.support_ticket.create');
    Route::post('/store', 'BackEnd\Organizer\SupportTicketController@store')->name('organizer.support_ticket.store');
    Route::get('tickets', 'BackEnd\Organizer\SupportTicketController@index')->name('organizer.support_tickets');
    Route::get('/message/{id}', 'BackEnd\Organizer\SupportTicketController@message')->name('organizer.support_tickets.message');
    Route::post('/zip-upload', 'BackEnd\Organizer\SupportTicketController@zip_file_upload')->name('organizer.support_ticket.zip_file.upload');
    Route::post('/reply/{id}', 'BackEnd\Organizer\SupportTicketController@ticketreply')->name('organizer.support_ticket.reply');

    Route::post('/delete/{id}', 'BackEnd\Organizer\SupportTicketController@delete')->name('organizer.support_tickets.delete');
    Route::post('/bulk/delete/', 'BackEnd\Organizer\SupportTicketController@bulk_delete')->name('organizer.support_tickets.bulk_delete');
  });
});
