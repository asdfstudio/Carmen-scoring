<div class="competition-bar-wrap">
    <div class="competition-bar body-width">
        <div class="carmen-logo-wrap">
            <img src="/images/logo-with-dark-text.png" width="185" />
        </div>
        <div class="heading">
            {{ link_to_route(Request::segment(1) . '.competition.show', $competition->name, [$competition]) }}
            @if($competition->place)
                <span class="subheading">{{ $competition->place->city }}, {{ $competition->place->state }}</span>
            @endif
        </div>

        <div class="status-container">
            {!! $competition->status_label('pull-right') !!}
        </div>

    </div>
</div>
