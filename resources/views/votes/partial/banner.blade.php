  <section class="heroVideo ptb_80">
    <div class="container">
      <div class="row">
      @if(isset($audience) AND ($audience->banner_type=='embed_video' || $audience->banner_type=='image_video'))

        @if($audience->banner_type === 'embed_video')
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="{{$audience->banner_embed}}" frameborder="0"
                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
          </div>
          <div class="videoTitle">
            <h2 class="videoTitle">Audience Vote – {{$division->name}}, {{ isset($division->competition)?$division->competition->name:'' }}
              <span>Choose the soloist that you would like to see in the next round.</span>
            </h2>
          </div>
        @endif

        @if($audience->banner_type === 'image_video')

          @php
            $banner_url = 'uploads/'.$audience->batnner_upload;
            if(env('AWS_ACCESS_KEY_ID')) {
              $banner_url = env('AWS_URL').$audience->banner_upload;
            }
          @endphp

          @if('mp4' === substr($audience->banner_upload, -3))
            <video class="mx-auto" width="1200" height="657" controls>
              <source src="{{$banner_url}}" type="video/mp4">
              Your browser does not support the video tag.
            </video>
          @else
              <img class="mx-auto" src="{{$banner_url}}">
          @endif

        @endif

      @endif
      </div>
    </div>
  </section>
