<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Language\StoreRequest;
use App\Http\Requests\Language\UpdateRequest;
use App\Models\CustomPage\Page;
use App\Models\CustomPage\PageContent;
use App\Models\Event\EventContent;
use App\Models\Journal\Blog;
use App\Models\Journal\BlogInformation;
use App\Models\Language;
use App\Models\MenuBuilder;
use App\Models\ShopManagement\ProductContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $languages = Language::all();

    return view('backend.language.index', compact('languages'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreRequest $request)
  {
    // get all the keywords from the default file of language
    $data = file_get_contents(resource_path('lang/') . 'default.json');

    // make a new json file for the new language
    $fileName = strtolower($request->code) . '.json';

    // create the path where the new language json file will be stored
    $fileLocated = resource_path('lang/') . $fileName;

    // finally, put the keywords in the new json file and store the file in lang folder
    file_put_contents($fileLocated, $data);

    // then, store data in db
    $language = Language::create([
      'code' => strtolower($request->code),
      'direction' => $request->direction,
      'name' => $request->name
    ]);

    $menus = [];

    $data = [];

    $data[] = ['text' => 'Home', "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "home"];

    $data[] = [
      'text' => 'Events', "href" => "events", "icon" => "empty", "target" => "_self", "title" => "", "type" => "events"
    ];

    $data[] = [
      'text' => 'Shop', "href" => "shop", "icon" => "empty", "target" => "_self", "title" => "", "type" => "shop"
    ];
    $data[] = [
      'text' => 'Organizers',  "icon" => "empty", "target" => "_self", "title" => "", "type" => "organizers"
    ];
    $data[] = [
      'text' => 'Cart', "href" => "shop/cart", "icon" => "empty", "target" => "_self", "title" => "", "type" => "cart"
    ];
    $data[] = [
      'text' => 'Blog', "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "blog"
    ];
    $data[] = [
      'text' => 'Contact', "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "contact"
    ];

    MenuBuilder::create([
      'language_id' => $language->id,
      'menus' => json_encode($data, true),
    ]);


    Session::flash('success', 'Added Successfully');

    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Make a default language for this system.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function makeDefault($id)
  {
    // first, make other languages to non-default language
    Language::where('is_default', 1)->update(['is_default' => 0]);

    // second, make the selected language to default language
    $language = Language::find($id);

    $language->update(['is_default' => 1]);

    return back()->with('success', $language->name . ' ' . 'is set as default language.');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateRequest $request)
  {
    $language = Language::find($request->id);

    if ($language->code !== $request->code) {
      /**
       * get all the keywords from the previous file,
       * which was named using previous language code
       */
      $data = file_get_contents(resource_path('lang/') . $language->code . '.json');

      // make a new json file for the new language (code)
      $fileName = strtolower($request->code) . '.json';

      // create the path where the new language (code) json file will be stored
      $fileLocated = resource_path('lang/') . $fileName;

      // then, put the keywords in the new json file and store the file in lang folder
      file_put_contents($fileLocated, $data);

      // now, delete the previous language code file
      @unlink(resource_path('lang/') . $language->code . '.json');

      // finally, update the info in db
      $language->update([
        'code' => strtolower($request->code),
        'direction' => $request->direction,
        'name' => $request->name
      ]);
    } else {
      $language->update([
        'code' => strtolower($request->code),
        'direction' => $request->direction,
        'name' => $request->name
      ]);
    }

    Session::flash('success', 'Updated Successfully');

    return response()->json(['status' => 'success'], 200);
  }

  public function addKeyword(Request $request)
  {
    $rules = [
      'keyword' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }
    $languages = Language::get();
    foreach ($languages as $language) {
      // get all the keywords of the selected language
      $jsonData = file_get_contents(resource_path('lang/') . $language->code . '.json');

      // convert json encoded string into a php associative array
      $keywords = json_decode($jsonData, true);
      $datas = [];
      $datas[$request->keyword] = $request->keyword;

      foreach ($keywords as $key => $keyword) {
        $datas[$key] = $keyword;
      }
      //put data
      $jsonData = json_encode($datas);

      $fileLocated = resource_path('lang/') . $language->code . '.json';

      // put all the keywords in the selected language file
      file_put_contents($fileLocated, $jsonData);
    }

    //for default json
    // get all the keywords of the selected language
    $jsonData = file_get_contents(resource_path('lang/') . 'default.json');

    // convert json encoded string into a php associative array
    $keywords = json_decode($jsonData, true);
    $datas = [];
    $datas[$request->keyword] = $request->keyword;

    foreach ($keywords as $key => $keyword) {
      $datas[$key] = $keyword;
    }
    //put data
    $jsonData = json_encode($datas);

    $fileLocated = resource_path('lang/') . 'default.json';

    // put all the keywords in the selected language file
    file_put_contents($fileLocated, $jsonData);

    Session::flash('success', 'Added Successfully');

    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Display all the keywords of specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function editKeyword($id)
  {
    $language = Language::findOrFail($id);

    // get all the keywords of the selected language
    $jsonData = file_get_contents(resource_path('lang/') . $language->code . '.json');

    // convert json encoded string into a php associative array
    $keywords = json_decode($jsonData, true);

    return view('backend.language.edit-keyword', compact('language', 'keywords'));
  }

  /**
   * Update the keywords of specified resource in respective json file.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function updateKeyword(Request $request, $id)
  {
    $arrData = $request['keyValues'];

    // first, check each key has value or not
    foreach ($arrData as $key => $value) {
      if ($value == null) {
        Session::flash('warning', 'Value is required for "' . $key . '" key.');

        return redirect()->back();
      }
    }

    $jsonData = json_encode($arrData);

    $language = Language::find($id);

    $fileLocated = resource_path('lang/') . $language->code . '.json';

    // put all the keywords in the selected language file
    file_put_contents($fileLocated, $jsonData);

    Session::flash('success', $language->name . ' language\'s keywords updated successfully!');

    return redirect()->back();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $language = Language::find($id);

    if ($language->is_default == 1) {
      return redirect()->back()->with('warning', 'Default language cannot be delete.');
    } else {

      /**
       * event category delete
       */

      $event_categories = $language->event_category()->get();

      foreach ($event_categories as $category) {
        //events
        $event_contents = EventContent::where('event_category_id', $category->id)->get();
        foreach ($event_contents as $event_content) {
          $event_content->delete();
        }
        @unlink(public_path('assets/admin/img/event-category/') . $category->image);
        $category->delete();
      }

      //event contents
      $event_contents = $language->event_content()->get();
      foreach ($event_contents as $event_content) {
        $event_content->delete();
      }
      //event contents
      $event_features = $language->event_features()->get();
      foreach ($event_features as $event_feature) {
        $event_feature->delete();
      }
      //event event_feature_sec
      $event_feature_section = $language->event_feature_section()->get();
      foreach ($event_feature_section as $event_feature_sec) {
        $event_feature_sec->delete();
      }
      //contact_pages
      $contact_pages = $language->contact_pages()->get();
      foreach ($contact_pages as $contact_page) {
        $contact_page->delete();
      }

      //product categories
      $product_categories = $language->product_categories()->get();

      foreach ($product_categories as $product_category) {
        @unlink(public_path('assets/admin/img/product/category/') . $product_category->image);
        $product_contents = ProductContent::where('category_id', $product_category->id)->get();
        foreach ($product_contents as $product_content) {
          $product_content->delete();
        }
      }

      //product contents
      $product_contents = $language->product_contents()->get();

      foreach ($product_contents as $product_content) {
        $product_content->delete();
      }

      /**
       * delete 'about us section' info
       */
      $aboutUsSec = $language->aboutUsSec()->first();

      if (!empty($aboutUsSec)) {
        @unlink(public_path('assets/admin/img/about-us-section/') . $aboutUsSec->image);
        $aboutUsSec->delete();
      }

      /* *
      * how works setions
      */
      $how_works = $language->how_works()->get();
      foreach ($how_works as $how_work) {
        $how_work->delete();
      }
      /* *
      *  how_work_items
      */
      $how_work_items = $language->how_work_items()->get();
      foreach ($how_work_items as $how_work_item) {
        $how_work_item->delete();
      }

      /**
       * delete 'blog infos'
       */
      $blogInfos = $language->blogInformation()->get();

      if (count($blogInfos) > 0) {
        foreach ($blogInfos as $blogData) {
          $blogInfo = $blogData;
          $blogData->delete();

          // delete the blog if, this blog does not contain any other blog information in any other language
          $otherBlogInfos = BlogInformation::query()->where('language_id', '<>', $language->id)->where('blog_id', '=', $blogInfo->blog_id)->get();

          if (count($otherBlogInfos) == 0) {
            $blog = Blog::query()->find($blogInfo->blog_id);
            @unlink(public_path('assets/admin/img/blogs/') . $blog->image);
            $blog->delete();
          }
        }
      }

      /**
       * delete 'blog categories' info
       */
      $blogCategories = $language->blogCategory()->get();

      if (count($blogCategories) > 0) {
        foreach ($blogCategories as $blogCategory) {
          $blogCategory->delete();
        }
      }

      /**
       * delete 'cookie alert' info
       */
      $cookieAlert = $language->cookieAlertInfo()->first();

      if (!empty($cookieAlert)) {
        $cookieAlert->delete();
      }


      /**
       * delete 'faqs' info
       */
      $faqs = $language->faq()->get();

      if (count($faqs) > 0) {
        foreach ($faqs as $faq) {
          $faq->delete();
        }
      }

      /**
       * delete 'features' info
       */
      $features = $language->feature()->get();

      if (count($features) > 0) {
        foreach ($features as $feature) {
          $feature->delete();
        }
      }

      /**
       * delete 'footer content' info
       */
      $footerContent = $language->footerContent()->first();

      if (!empty($footerContent)) {
        $footerContent->delete();
      }

      /**
       * delete 'hero section' info
       */
      $heroSec = $language->heroSec()->first();

      if (!empty($heroSec)) {
        @unlink(public_path('assets/admin/img/hero-section/') . $heroSec->background_image);
        @unlink(public_path('assets/admin/img/hero-section/') . $heroSec->image);
        $heroSec->delete();
      }

      /**
       * delete 'menu builders' info
       */
      $menuInfo = $language->menuInfo()->first();

      if (!empty($menuInfo)) {
        $menuInfo->delete();
      }

      /**
       * delete 'page contents'
       */
      $customPageInfos = $language->customPageInfo()->get();

      if (count($customPageInfos) > 0) {
        foreach ($customPageInfos as $customPageData) {
          $customPageInfo = $customPageData;
          $customPageData->delete();

          // delete the 'custom page' if, this page does not contain any other page content in any other language
          $otherPageContents = PageContent::query()->where('language_id', '<>', $language->id)->where('page_id', '=', $customPageInfo->page_id)->get();

          if (count($otherPageContents) == 0) {
            $page = Page::query()->find($customPageInfo->page_id);
            $page->delete();
          }
        }
      }

      /**
       * delete 'page heading' info
       */
      $pageHeadingInfo = $language->pageName()->first();

      if (!empty($pageHeadingInfo)) {
        $pageHeadingInfo->delete();
      }

      /**
       * delete 'popup' infos
       */
      $popups = $language->announcementPopup()->get();

      if (count($popups) > 0) {
        foreach ($popups as $popup) {
          @unlink(public_path('assets/admin/img/popups/') . $popup->image);
          $popup->delete();
        }
      }

      /**
       * delete 'quick links'
       */
      $quickLinks = $language->footerQuickLink()->get();

      if (count($quickLinks) > 0) {
        foreach ($quickLinks as $quickLink) {
          $quickLink->delete();
        }
      }

      /**
       * delete 'section title' info
       */
      $sectionTitleInfo = $language->sectionTitle()->first();

      if (!empty($sectionTitleInfo)) {
        $sectionTitleInfo->delete();
      }

      /**
       * delete 'seo' info
       */
      $seoInfo = $language->seoInfo()->first();

      if (!empty($seoInfo)) {
        $seoInfo->delete();
      }

      /**
       * delete 'testimonials'
       */
      $testimonials = $language->testimonial()->get();

      if (count($testimonials) > 0) {
        foreach ($testimonials as $testimonial) {
          @unlink(public_path('assets/admin/img/clients/') . $testimonial->image);
          $testimonial->delete();
        }
      }


      // delete the language json file
      @unlink(resource_path('lang/') . $language->code . '.json');

      // finally, delete the language info from db
      $language->delete();

      return redirect()->back()->with('success', 'Deleted Successfully');
    }
  }

  /**
   * Check the specified language is RTL or not.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function checkRTL($id)
  {
    if (!is_null($id)) {
      $direction = Language::where('id', $id)->pluck('direction')->first();

      return response()->json(['successData' => $direction], 200);
    } else {
      return response()->json(['errorData' => 'Sorry, an error has occured!'], 400);
    }
  }

  public function adminKeywordsEdit()
  {

    // get all the keywords of the selected language
    $jsonData = file_get_contents(resource_path('lang/admin.json'));

    // convert json encoded string into a php associative array
    $keywords = json_decode($jsonData, true);

    return view('backend.language.admin_keywords_edit', compact('keywords'));
  }
  public function adminKeywordsUpdate(Request $request)
  {
    $arrData = $request['keyValues'];

    // first, check each key has value or not
    foreach ($arrData as $key => $value) {
      if ($value == null) {
        Session::flash('warning', 'Value is required for "' . $key . '" key.');

        return redirect()->back();
      }
    }

    $jsonData = json_encode($arrData);

    $fileLocated = resource_path('lang/admin.json');

    // put all the keywords in the selected language file
    file_put_contents($fileLocated, $jsonData);

    Session::flash('success',   'Updated Successfully');

    return redirect()->back();
  }
}
