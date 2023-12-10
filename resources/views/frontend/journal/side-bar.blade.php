<div class="col-lg-4 col-md-6">
  <div class="sidebar rmt-75">
    <div class="widget widget-search">
      <h5 class="widget-title">{{ __('Search Blog') }}</h5>
      <div class="blog-Search-content text-center">
        <form action="{{ route('blogs') }}" method="GET">
          <input type="text" placeholder="{{ __('Search By Title') }}" name="title"
            value="{{ !empty(request()->input('title')) ? request()->input('title') : '' }}">
          <input type="hidden" name="category"
            value="{{ !empty(request()->input('category')) ? request()->input('category') : '' }}">
          <button type="submit"><i class="fa fa-search"></i></button>
        </form>
      </div>
    </div>

    <div class="widget widget-cagegory">
      <h5 class="widget-title">{{ __('Categories') }}</h5>
      <div class="blog-categories-content">
        @if (count($categories) == 0)
          <h5>{{ __('No Category Found') . '!' }}</h5>
        @else
          <ul class="list-style-two">
            <li @if (empty(request()->input('category'))) class="active" @endif>
              <a href="{{ route('blogs') }}">{{ __('All') }} <span>({{ $allBlogs }})</span></a>
            </li>

            @foreach ($categories as $category)
              <li @if ($category->slug == request()->input('category')) class="active" @endif>
                <a href="{{ route('blogs', ['category' => $category->slug]) }}"
                  data-category_id="{{ $category->slug }}">{{ $category->name }}
                  <span>({{ $category->blogCount }})</span>
                </a>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>

    <div class="banner-add mt-40 text-center">
      {!! showAd(1) !!}
    </div>

    <div class="banner-add mt-40 text-center">
      {!! showAd(2) !!}
    </div>
  </div>

  {{-- search form start --}}
  <form class="d-none" action="{{ route('blogs') }}" method="GET">
    <input type="hidden" name="title"
      value="{{ !empty(request()->input('title')) ? request()->input('title') : '' }}">

    <input type="hidden" id="categoryKey" name="category">

    <button type="submit" id="submitBtn"></button>
  </form>
  {{-- search form end --}}
</div>
