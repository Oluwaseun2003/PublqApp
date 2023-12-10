<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;

class FaqController extends Controller
{
  public function faqs()
  {
    $language = $this->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_faq', 'meta_description_faq')->first();

    $queryResult['pageHeading'] = $this->getPageHeading($language);

    $queryResult['bgImg'] = $this->getBreadcrumb();

    $queryResult['faqs'] = FAQ::where('language_id', $language->id)->orderBy('serial_number', 'asc')->get();

    return view('frontend.faqs', $queryResult);
  }
}
