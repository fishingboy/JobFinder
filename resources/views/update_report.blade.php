<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>更新 {{ $source }}</title>
<style type="text/css">@import url(/css/test.css);</style>
</head>
<body>
{{-- 顯示更新標題 --}}
<div style='font-weight:bold;'>Update {{ $source }}
@if ($page == $total_page)
    Success.
@else
    ....
@endif
</div>
<br>

{{-- 顯示求職條件(僅預灠模式) --}}
@if ($preview_mode)
<div>104 求職條件: {{ $condition }}</div>
@else
<div>求職條件檔案: {{ $condition_file }}</div>
<div>求職條件: {{ $condition }}</div>
@endif

{{-- 顯示其他資訊 --}}
@if ($source == 'App\Classes\Job104')
    <div>符合筆數: {{ $record_count }}</div>
    @if ( ! $preview_mode)
    <div>完成度:  {{ $finish_record_count }} / {{ $record_count }} ({{ $finish_percent }}%)</div>
    <div>頁數:   {{ $page }} / {{ $total_page }}</div>
    @endif
@endif
</body>
</html>
{{-- 自動跳到下頁更新 --}}
{!! $go_next_page_js !!}
