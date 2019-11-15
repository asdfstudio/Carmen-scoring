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
</script>

<link href=/css/dynamic-colors.css rel=stylesheet>
<link href=/static/css/app.b74f201eeee6eb6ba324d3b921fd7ed0.css rel=stylesheet></head>
<body>
  <div id=app></div>
<script type=text/javascript src=/static/js/manifest.2ae2e69a05c33dfc65f8.js></script><script type=text/javascript src=/static/js/vendor.2bdb1616a0fef26ce4e0.js></script><script type=text/javascript src=/static/js/app.f7358848eca1333f8c1c.js></script></body>
</html>
