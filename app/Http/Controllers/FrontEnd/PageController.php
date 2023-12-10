<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\CustomPage\Page;
use App\Models\CustomPage\PageContent;
use Illuminate\Http\Request;

class PageController extends Controller
{
  public function page($slug)
  {
    $language = $this->getLanguage();

    $queryResult['bgImg'] = $this->getBreadcrumb();

    $pageId = PageContent::where('slug', $slug)->firstOrFail()->page_id;

    $queryResult['pageInfo'] = Page::join('page_contents', 'pages.id', '=', 'page_contents.page_id')
      ->where('pages.status', '=', 1)
      ->where('page_contents.language_id', '=', $language->id)
      ->where('page_contents.page_id', '=', $pageId)
      ->firstOrFail();

    return view('frontend.custom-page', $queryResult);
  }
}
