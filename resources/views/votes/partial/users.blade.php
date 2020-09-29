<section class="userSection ptb_80">
  <div class="container">
    <div class="row">
      <input type="hidden" name="audientId" value="{{$audience?$audience->id:''}}">
      @foreach ($division->choirs as $key => $choir)
        <div class="col-lg-4" data-choir="{{$choir->id}}">
          <div class="white-bg wbg2 {{$colors[$key%6]}}">

            <div class="userInfo">
              <h3>
                @if($choir->school)
                  <span class="school">{{ $choir->school->name }}</span>
                @endif

                {{$choir->name}}

                @if($choir->school AND $choir->school->place AND $choir->school->place->city_state())
                  <span class="location">{{ $choir->school->place->city_state() }} </span>
                @endif
              </h3>
              <button type="button" class="btn like" data-vote="{{$choir->id}}">
                <i class="fas fa-thumbs-up"></i>
                @if($division->is_completed)
                  @php $votesObject = isset($audience)?json_decode($choir->votes($audience->id)):NULL; @endphp
                  <span class="vote-count">
                    @if(NULL === $votesObject)
                      0
                    @else
                      {{ number_format($votesObject->vote_count) }}
                    @endif
                  </span>
                @endif
              </button>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
