<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content p-2">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>

      <iframe
        src="//maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q={{ $map_address }}&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"
        height="380" style="border:0; width: 100%;max-height:600px" allowfullscreen="" loading="lazy"></iframe>


    </div>
  </div>
</div>

<div class="modal fade share-event" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4>{{ __('Share Now') }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center p-4">
        <div class="button-group">
          <a href="//www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank">
            <i class="fab fa-facebook-f"></i>
            <p>{{ __('Facebook') }}</p>
          </a>
          <a href="//twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}"
            target="_blank"><i class="fab fa-twitter"></i>
            <p>{{ __('Twitter') }}</p>
          </a>
          <a href="//www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title="
            target="_blank"><i class="fab fa-linkedin"></i>
            <p>{{ __('linkedin') }}</p>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
