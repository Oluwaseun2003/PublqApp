<?php

namespace App\Exports;

use App\Models\BasicSettings\Basic;
use App\Models\Language;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\FromCollection;

class BookingExport implements FromCollection, WithHeadings, WithMapping
{
  public $bookings;

  public function __construct($bookings)
  {
    $this->bookings = $bookings;
  }
  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return $this->bookings;
  }

  public function map($bookings): array
  {
    $bs = Basic::firstOrFail();
    $deLang = Language::where('is_default', 1)->first();

    return [
      $bookings->booking_id,
      $bookings->title,
      $bookings->customerfname.' '.$bookings->customerlname,

      ($bs->currencySymbolPosition == 'left' ? $bs->currencySymbol : '') . $bookings->discount . ($bs->currencySymbolPosition == 'right' ? $bs->currencySymbol : ''),

      ($bs->currencySymbolPosition == 'left' ? $bs->currencySymbol : '') . (empty($bookings->early_bird_discount) ? 0 : $bookings->early_bird_discount) . ($bs->currencySymbolPosition == 'right' ? $bs->currencySymbol : ''),

      $bookings->quantity,

      ($bs->currencySymbolPosition == 'left' ? $bs->currencySymbol : '') . (empty($bookings->price) ? 0 : $bookings->price) . ($bs->currencySymbolPosition == 'right' ? $bs->currencySymbol : ''),

      $bookings->fname.' '.$bookings->lname,
      $bookings->email,
      $bookings->phone,
      $bookings->city,
      $bookings->state,
      $bookings->country,
      $bookings->zip_code,
      $bookings->paymentMethod,
      $bookings->paymentStatus,
      $bookings->created_at
    ];
  }

  public function headings(): array
  {
    return [
      'Booking Id', 'Event', 'Customer Name', 'Discount', 'Early Bird Discount', 'Quantity', 'Total', 'Name', 'Email', 'Phone', 'City', 'State', 'Country', 'Zip Code', 'Gateway', 'Payment Status', 'Date'
    ];
  }
}
