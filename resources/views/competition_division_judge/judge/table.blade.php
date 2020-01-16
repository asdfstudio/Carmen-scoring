@if($division->judges->isEmpty())
	<p>There are no judges.</p>
@endif

@if(!$division->judges->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
  	<th>Judge</th>
    
    @foreach($captions as $caption)
    <th>Scoring {{ $caption->name }}?</th>
    @endforeach
    
  </tr>
  
  @foreach($judges as $judge)
  <tr>
  	
    <td>{{ $judge->full_name }}</td>
    
    @foreach($captions as $caption)
    <td>
    	@if(in_array($caption->id, $judge->captions->pluck('id')->toArray() ))
    		<span class="glyphicon glyphicon-ok"></span>
      @endif
    </td>
    @endforeach

  </tr>
  @endforeach
</table>
</div>
@endif