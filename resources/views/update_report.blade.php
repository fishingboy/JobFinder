<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>更新</title>
</head>
<body>
<div style='font-weight:bold;'>Update {{ $source }} Success.</div>
@if ($source == 'App\Classes\Job104')
    <br>
    <div>API 網址: {{ $api_url }}</div>
    <div>查詢條件: {{ $condition }}</div>
    <div>符合筆數: {{ $job_count }}</div>
@endif
</body>
</html>
