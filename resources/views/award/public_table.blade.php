@if($awards->isEmpty())
	<p>There are no awards.</p>
@endif

@if(!$awards->isEmpty())
<table>
  <tr>
    <th>Award</th>
    <th>Sponsor</th>
    <th>Recipient</th>
  </tr>
  @foreach($awards as $award)
	  <tr>
			<td>{{ $award->name }}</td>
      <td>
        @if($award->pivot->sponsor)
          {{ $award->pivot->sponsor }}
        @endif
      </td>
			<td>
        @if($award->pivot)
      		@if($award->pivot->recipient OR $award->choir)
						@if($award->pivot->recipient)
							{{ $award->pivot->recipient }}
						@endif
						@if($award->choir)
							{{ $award->choir->full_name }}
						@endif
          @endif
        @endif
			</td>


		</tr>
  @endforeach
</table>
@endif
