<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\MenuBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class MenuBuilderController extends Controller
{
  public function index(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // set the selected language as locale
    App::setLocale($language->code);

    // now, get the custom pages of that language from db
    $information['customPages'] = DB::table('pages')
      ->join('page_contents', 'pages.id', '=', 'page_contents.page_id')
      ->where('page_contents.language_id', '=', $language->id)
      ->orderByDesc('pages.id')
      ->get();

    // then, get the menus
    $websiteMenuInfo = $language->menuInfo;

    if (is_null($websiteMenuInfo)) {
      $information['menuData'] = '';
    } else {
      $information['menuData'] = $websiteMenuInfo->menus;
    }

    // also, get all the languages from db
    $information['langs'] = Language::all();
    return view('backend.menu-builder', $information);
  }

  public function update(Request $request)
  {
    MenuBuilder::updateOrCreate(
      ['language_id' => $request['languageId']],
      ['language_id' => $request['languageId'], 'menus' => $request['str']]
    );

    return response()->json(['message' => 'Updated Successfully'], 200);
  }
}
