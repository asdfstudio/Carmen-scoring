@section('body-header')
  <style>@import "/css/director-form.css";</style>
@endsection

<div class="board-list choirs" id="choir-list">
  <div class="list-header">
    <h3>Choirs</h3>
    <span class="card-count" data-resource-type="choir">{{ count($division->choirs) }}</span>
  </div>

  <a class="add-resource" data-resource-type="choir" href="#">Add a choir</a>
  
  {!! form($newChoirForm) !!}

  @include('choir.board.list')

</div>

@section('body-footer')
  <script src="/js/director-form.js"></script>
@endsection