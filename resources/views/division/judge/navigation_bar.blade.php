<div class="division-navigation-bar body-width">
    <ul class="division-navigation tab-links">
        <li>
            <a href="#scoring" class="active tab-link" data-tab-id="scoring">Settings</a>
        </li>
        <li>
            <a href="#choirs" class="tab-link" data-tab-id="choirs">Ensembles</a>
        </li>

        <li>
            <a href="#judges" class="tab-link" data-tab-id="judges">Judges</a>
        </li>
        <li>
            <a href="#penalties" class="tab-link" data-tab-id="penalties">Penalties</a>
        </li>
        <li>
            <a href="#" class="tab-link" data-tab-id="awards">Awards</a>
        </li>
        <li>
            <a href="#" class="tab-link" data-tab-id="standings">Final Standings</a>
        </li>
        <li class="scoring">
            <a href="{{ route('judge.competition.division.scoring', [$competition, $division]) }}">Enter Scoring Mode</a>
        </li>
    </ul>
</div>
