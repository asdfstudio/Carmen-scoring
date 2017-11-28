<li class="card-prototype card judge list-group-item" data-resource-type="judge" data-resource-id="@{{id}}">

  <span class="name">@{{first_name}} @{{last_name}}</span>

  <ul class="captions-group">
    @{{ #captions }}
      <li class="@{{ name }} caption label">@{{ name }} </span>
    @{{ /captions }}
  </ul>

  <!--<div class="actions">
    <a class="remove-judge" href="#">Remove judge</a>
  </div>-->
</li>
