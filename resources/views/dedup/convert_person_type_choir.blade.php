@extends('layouts.simple')

@section('content-header')
  <h1>Convert People, Types, &amp; Choir Relationships</h1>
  <a href="{{ route('dedup') }}" class="action">Back</a>
@endsection


@section('content')
  
  <p>
    This script converts the type (Judge, Director, Choreographer) of every person in
    the database to the new table structure. This will allow one person to have multiple
    types.  This script also looks up existing relationships between judges and divisions
    and between directors/choreographers and choirs, and converts those associations into
    the new table structure.
  </p>

  <p>It is safe to run this script more than once. It will NOT double-convert the data.</p>

  <p>Use the "Dry Run" button to preview the changes without actually modifying the database. Use the "Convert" button to do the conversion.</p>

  <p style="margin: 20px 0;">
    @if(!$run)
      <script>
        function disableButtons(){
          var b = document.getElementsByClassName('run-button');
          for(var i=0; i<b.length; i++){
            b[i].setAttribute('disabled', 'disabled');
          }
        }
      </script>
      <a href="{{ url()->current() }}?run" class="run-button btn btn-primary" onClick="disableButtons(); this.innerHTML = 'Please wait...';">Convert</a>
      <a href="{{ url()->current() }}?dryrun" class="run-button btn btn-default" onClick="disableButtons(); this.innerHTML = 'Please wait...';">Dry Run</a>
    @endif
    @if($run || $dryrun)
      <a href="{{ url()->current() }}" class="run-button btn btn-default" onClick="disableButtons(); this.innerHTML = 'Please wait...';">Clear</a>
    @endif
  </p>

  @if($run_dryrun_error)
    <div class="alert alert-danger">This script cannot be set to "run" and "dryrun" at the same time.</div>
  @endif

  @if(($run || $dryrun) && !$run_dryrun_error)

      <hr>

      <h3>Judges</h3>

      <div style="max-height: 600px; overflow-y: scroll; padding: 20px; border: 1px #c0c0c0 solid; margin-bottom: 50px;">

          @if(empty($judges))
            <p>No judges were found.</p>
          @endif

          @foreach($judges as $judge)
            <div style="padding: 20px;">
              <p>{{ $judge->intro }}</p>
              <ul>
                <li>Assigned to {{ count($judge->divisions) }} divisions</li>
              </ul>
              @if(isset($judge->run_messages) && !empty($judge->run_messages))
                <div class="alert alert-info">
                  @foreach($judge->run_messages as $message)
                    {!! $message !!}<br>
                  @endforeach
                </div>
              @endif
            </div>

            <hr>

          @endforeach

      </div>

      <hr>

      <h3>Directors</h3>

      <div style="max-height: 600px; overflow-y: scroll; padding: 20px; border: 1px #c0c0c0 solid; margin-bottom: 50px;">

          @if(empty($directors))
            <p>No directors were found.</p>
          @endif

          @foreach($directors as $director)
            <div style="padding: 20px;">
              <p>{{ $director->intro }}</p>
              @if(isset($director->choir) || isset($director->school))
                <ul>
              @endif
                @if(isset($director->choir))
                  <li>{{ $director->choir }}</li>
                @endif
                @if(isset($director->school))
                  <li>{{ $director->school }}</li>
                @endif
              @if(isset($director->choir) || isset($director->school))
                </ul>
              @endif
              @if(isset($director->run_messages) && !empty($director->run_messages))
                <div class="alert alert-info">
                  @foreach($director->run_messages as $message)
                    {!! $message !!}<br>
                  @endforeach
                </div>
              @endif
            </div>

            <hr>

          @endforeach

      </div>

      <hr>

      <h3>Choreographers</h3>

      <div style="max-height: 600px; overflow-y: scroll; padding: 20px; border: 1px #c0c0c0 solid; margin-bottom: 50px;">

          @if(empty($choreographers))
            <p>No directors were found.</p>
          @endif

          @foreach($choreographers as $choreographer)
            <div style="padding: 20px;">
              <p>{{ $choreographer->intro }}</p>
              @if(isset($choreographer->choir) || isset($choreographer->school))
                <ul>
              @endif
                @if(isset($choreographer->choir))
                  <li>{{ $choreographer->choir }}</li>
                @endif
                @if(isset($choreographer->school))
                  <li>{{ $choreographer->school }}</li>
                @endif
              @if(isset($choreographer->choir) || isset($choreographer->school))
                </ul>
              @endif
              @if(isset($choreographer->run_messages) && !empty($choreographer->run_messages))
                <div class="alert alert-info">
                  @foreach($choreographer->run_messages as $message)
                    {!! $message !!}<br>
                  @endforeach
                </div>
              @endif
            </div>

            <hr>

          @endforeach

      </div>

  @endif

@endsection
