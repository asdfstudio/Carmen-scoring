<p>Hello,</p>

<p>This is a notification that the {{ $division->name }} division of {{ $division->competition->name }} has been completed. You can view the results of the division at:</p>

<p>{{ link_to_route('results.division.show', NULL, [$division, $division->access_code])}}</p>

<p>Thank you,</p>
<p>Carmen Scoring</p>
