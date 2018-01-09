<p>Hello,</p>

<p>This is a notification that there is judge feedback available for your choir for {{ $competition->name }}. You can view all your feedback for this competition at:</p>

<p>{{ link_to_route('feedback.show', NULL, [$commentUrl->access_code])}}</p>

<p>Thank you,</p>
<p>Carmen Scoring</p>
