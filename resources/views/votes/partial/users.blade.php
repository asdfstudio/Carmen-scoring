<section class="userSection ptb_80">
  <div class="container">
    <div class="row">
      <input type="hidden" name="audienceId" value="{{$audience?$audience->id:''}}">
      <input type="hidden" id="isSoloDivision" value="0" />
      @foreach ($audience->audienceable->choirs as $key => $choir)
        <div class="col-lg-4" data-choir="{{$choir->id}}">
          <div class="white-bg wbg2 {{$colors[$key%6]}}">

            <div class="userInfo" data-vote="{{$choir->id}}">
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
                  @php $votesObject = isset($audience)?json_decode($choir->votes_byUser($audience->id)):NULL; @endphp
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
