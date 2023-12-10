<div class="footer-item mt-30">
  <div class="footer-title item-3">
    <i class="fal fa-blog"></i>
    <h4 class="title">{{ __('Latest Blog') }}</h4>
  </div>

  <div class="footer-instagram">
    @if (count($latestBlogInfos) == 0)
      <h6 class="text-light">{{ __('No Blog Found') . '!' }}</h6>
    @else
      <div class="instagram-item">
        @foreach ($latestBlogInfos as $latestBlogInfo)
          <div class="item mt-20 d-flex align-items-center">
            <div class="blog-img {{ $currentLanguageInfo->direction == 0 ? 'mr-4' : 'ml-4' }}">
              <img data-src="{{ asset('assets/admin/img/blogs/' . $latestBlogInfo->image) }}" class="lazy" alt="image">
            </div>

            <div class="blog-info">
              <h6 class="blog-title">
                <a href="{{ route('blog_details', ['slug' => $latestBlogInfo->slug]) }}">{{ strlen($latestBlogInfo->title) > 30 ? mb_substr($latestBlogInfo->title, 0, 30, 'UTF-8') . '...' : $latestBlogInfo->title }}</a>
              </h6>
              <span class="mt-1">{{ date_format($latestBlogInfo->created_at, 'M d, Y') }}</span>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</div>
