<p>Hello,</p>

<p>This is a notification that the {{ $soloDivision->name }} division of {{ $soloDivision->competition->name }} has been completed. You can view the results of the division at:</p>

<p>{{ link_to_route('results.solo-division.show', NULL, [$soloDivision, $soloDivision->access_code])}}</p>

<p>Thank you,</p>
<p>Carmen Scoring</p>
