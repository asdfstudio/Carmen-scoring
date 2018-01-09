@if($captions->isEmpty())
	<p>There are no captions.</p>
@endif

@if(!$captions->isEmpty())
<ul class="list-group">
  @foreach($captions as $caption)
    <li class="school list-group-item">

      <span class="name">{{ $caption->name }}</span>
      <ul class="actions-group">
        <li>{{ link_to_route('admin.caption.edit', 'Edit caption', [$caption], ['class' => 'action']) }}</li>
      </ul>

    </li>
  @endforeach
</ul>
@endif
