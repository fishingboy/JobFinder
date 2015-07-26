<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>更新 Company 資訊(人數、資本額)</title>
<style type="text/css">@import url(/css/test.css);</style>
</head>
<body>
{{-- 顯示更新標題 --}}
<div style='font-weight:bold;'>更新 Company 資訊(人數、資本額)</div>
<br>

{{-- 顯示其他資訊 --}}
<div>所有公司: {{ $count }}</div>
<div>完成度:  {{ $count - $null_count }} / {{ $count }} ({{ $finish_percent }}%)</div>
<br>

{{-- 公司資訊 --}}
<div>公司: {{ $row->name }}</div>
<div>人數: {{ $employees }}</div>
<div>資本額: {{ $capital }}</div>

</body>
</html>

{{-- 自動跳到下頁更新 --}}
{!! $go_next_page_js !!}
