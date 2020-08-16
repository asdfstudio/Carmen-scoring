<div class="board-list judges" id="judge-list">
  <div class="list-header">
    <h3>Judges</h3>
    <span class="card-count" data-resource-type="judge">{{ count($division->judges) }}</span>
  </div>

  <a class="add-resource" data-resource-type="judge" href="#">Add a judge</a>
  <a class="import-resource" data-resource-type="judge" href="#">Import a judge</a>
  
  {!! form($newJudgeForm) !!}

  @can('importJudges', $division)
    @if (count($divisions_import_judge) > 0)
    
      {{ Form::open(['method' => 'POST', 'url' => route('organizer.competition.division.judge.import.process', [$division->competition->id, $division->id]), 'class' => 'import-resource-form-prototype', 'data-resource-type' => 'judge', 'data-resource-action' => 'import']) }}
        <label for="id" class="control-label ss-fs-16">Choose a division to import judges from</label>

        @foreach($divisions_import_judge as $division_import_judge)

          @php
            $judges_import = $division_import_judge -> judges -> unique('id') -> pluck('full_name');
            $judges_list_import = implode(', ', $judges_import->toArray());
          @endphp

          <div class="choice-container d-flex">
            {{ Form::radio('id', $division_import_judge->id, NULL, ['id' => 'id_'.$division_import_judge->id, 'required' => 'required', 'disabled' => 'disabled'])}}

            <label for="id_{{ $division_import_judge->id }}">
              <strong>{{ $division_import_judge -> name }}</strong><br />
              {{ $judges_list_import }}
            </label>
          </div>

        @endforeach

        {{ Form::submit('Import Judges', ['class' => 'btn btn-primary']) }}
      {{ Form::close() }}
    @endif
  @endcan
  @include('judge.board.list')

</div>
