<div class="board-list judges" id="judge-list">
  <div class="list-header">
    <h3>Judges</h3>
    <span class="card-count" data-resource-type="judge">{{ count($division->round->judges) }}</span>
    <div class="d-none base-url-div">{{ url('/') }}</div>
  </div>

  <a class="add-resource" data-resource-type="judge" href="#">Add a judge</a>
  <a class="import-resource" data-resource-type="judge" href="#">Import a judge</a>
  <a class="change-password" href="#">Change Password</a>

  {!! form($newJudgeForm) !!}

  @php
    $str_all_captions = "";
    $arr_captions = $captions->pluck('name', 'id')->toArray();
    foreach ($arr_captions as $id => $name) {
      $str_all_captions .= $id . "-" . $name . "-";
    }
  @endphp

  @can('importJudges', $division)
    @if (count($divisions_import_judge) > 0)
    
      {{ Form::open(['method' => 'POST', 'url' => route('organizer.competition.division.judge.import.process', [$division->competition->id, $division->id]), 'class' => 'import-resource-form-prototype', 'data-resource-type' => 'judge', 'data-resource-action' => 'import']) }}
        <!-- <label for="id" class="control-label dg-fs-16">Choose a division to import judges from</label> -->

        @foreach($divisions_import_judge as $division_import_judge)

          @php
            $judges_import = $division_import_judge -> judges -> unique('id') -> pluck('full_name');
            $judges_list_import = implode(', ', $judges_import->toArray());
          @endphp

          <div class="choice-container d-flex">
            {{ Form::radio('id', $division_import_judge->id, NULL, ['id' => 'proto_id_'.$division_import_judge->id, 'required' => 'required', 'disabled' => 'disabled'])}}

            <label for="proto_id_{{ $division_import_judge->id }}">
              <div class="text-left dg-fs-18 dg-fw-bold">{{ $division_import_judge -> name }}</div>
              <div class="text-left dg-mt-4 dg-ml-4 dg-fs-16 dg-color-9">{{ $judges_list_import }}</div>
            </label>
          </div>

        @endforeach

        <!-- {{ Form::submit('Import Judges', ['class' => 'btn btn-primary']) }} -->
      {{ Form::close() }}
    @endif
  @endcan
  @include('judge.board.list')

</div>
