
<ul class="list-group">
  @foreach ($awardSettings as $awardSetting)
    @php $captionName = $awardSetting->caption ? $awardSetting->caption->name : 'Overall'; @endphp
    <li class="list-group-item">{{ $captionName }} Award Count: {{ $awardSetting->award_count }}</li>
  @endforeach
</ul>
