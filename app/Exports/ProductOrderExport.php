<?php

namespace App\Exports;

use App\Models\BasicSettings\Basic;
use App\Models\Language;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\FromCollection;

class ProductOrderExport implements  FromCollection, WithHeadings, WithMapping
{
  public $orders;

  public function __construct($orders)
  {
    $this->orders = $orders;
  }
  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return $this->orders;
  }

  public function map($orders): array
  {
    $bs = Basic::firstOrFail();
    $deLang = Language::where('is_default', 1)->first();

    return [
      '#'.$orders->order_number,
      $orders->billing_fname.' '.$orders->billing_lname,

      ($bs->currencySymbolPosition == 'left' ? $bs->currencySymbol : '') . $orders->discount . ($bs->currencySymbolPosition == 'right' ? $bs->currencySymbol : ''),

      ($bs->currencySymbolPosition == 'left' ? $bs->currencySymbol : '') . (empty($orders->shipping_charge) ? 0 : $orders->shipping_charge) . ($bs->currencySymbolPosition == 'right' ? $bs->currencySymbol : ''),

      ($bs->currencySymbolPosition == 'left' ? $bs->currencySymbol : '') . (empty($orders->total) ? 0 : $orders->total) . ($bs->currencySymbolPosition == 'right' ? $bs->currencySymbol : ''),

      $orders->method,
      $orders->payment_status,
      $orders->created_at
    ];
  }

  public function headings(): array
  {
    return [
      'Order Id', 'Customer Name', 'Discount', 'Shipping Charge', 'Total', 'Gateway', 'Payment Status', 'Date'
    ];
  }
}
