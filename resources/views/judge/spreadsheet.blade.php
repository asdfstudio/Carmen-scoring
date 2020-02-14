<!DOCTYPE html>
<html>
<head>
<meta charset=utf-8>
<meta name=viewport content="width=device-width,initial-scale=1">
<meta name=_token content={{csrf_token()}}>
<title>Carmen - Judge Spreadsheet</title>
<script>
window.__DIVISIONS__ = {!! $divisions !!};
window.__CHOIRS__ = {!! $choirs !!};
window.__CRITERIA__ = {!! $criteria !!};
window.__SCORES__ = {!! $scores !!};
window.__COMMENTS__ = {!! $comments !!};
window.__CAPTIONS__ = {!! $captions !!};
window.__CAPTION_WEIGHTING_ID__ = {!! $captionWeightingId !!};
window.__RATINGS__ = {!! $rating_system !!};
window.__SPREADSHEET_TITLE__ = "{!! $spreadsheetTitle !!}";
window.__BACK_URL__ = "{!! $backUrl !!}";
window.__IS_SPREADSHEET_SCORING_ACTIVE__ = "{!! $isSpreadsheetScoringActive !!}";
window. __RECORDED_COMMENTS__ = {!! $recordedComments !!}; 
window. __Competition__ = {!! $competition !!}; 
</script>
<script type=text/javascript src=/js/mic-recorder.js></script>
<link href=/css/dynamic-colors.css rel=stylesheet>
<link href=/static/css/app.54529df7035626f4f29955f423812753.css rel=stylesheet></head>
<body>
  <input type=hidden id=recordingsInProgress value=0>
  <div id=app></div>
  <script>
    const input = document.getElementById('recordingsInProgress');
    window.onbeforeunload = function() {
      if (input.value > 0) {
        return 'Upload in progress, navigating away from the page will lose recording. Are you sure you want to continue?'
      }
      return;
    };
  </script>
<script type=text/javascript src=/static/js/manifest.2ae2e69a05c33dfc65f8.js></script><script type=text/javascript src=/static/js/vendor.41ea52dbb86cd037fb1d.js></script><script type=text/javascript src=/static/js/app.a4754c8337013ec26bf9.js></script></body>
</html>
