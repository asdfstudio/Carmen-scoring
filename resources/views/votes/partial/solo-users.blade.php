<section class="userSection ptb_80">
  <div class="container">
    <div class="row">
      <input type="hidden" name="audientId" value="{{$audience?$audience->id:''}}">
      @foreach ($division->performers as $key => $performer)
        <div class="col-lg-4" data-choir="{{$performer->id}}">
          <div class="white-bg wbg2 {{$colors[$key%6]}}">

            <div class="userInfo" data-vote="{{$performer->id}}">
              <h3>
                @if($performer->choir)
                  <span class="school">{{ $performer->choir->name }}</span>
                @endif

                {{$performer->name}}

                @if($performer->category)
                  <span class="location">{{ $performer->getCategoryNameAttribute() }} </span>
                @endif
              </h3>
              <button type="button"
                      class="btn like"
                      data-vote="{{$performer->id}}"
              >
                <i class="fas fa-thumbs-up"></i>
                  @php $votesObject = isset($audience)?json_decode($performer->votes_byUser($audience->id)):NULL; @endphp
                  <span class="vote-count">
                    @if(NULL === $votesObject)
                      0
                    @else
                      {{ number_format($votesObject) }}
                    @endif
                  </span>
              </button>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
