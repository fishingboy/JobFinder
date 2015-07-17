<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>工作列表</title>
<style>
    .box {border:1px solid #ccc; padding:10px; border-radius: 10px; background: #EFE; margin: 10px 0;}
    .box .title {font-size: 14px; font-weight: bold; text-align: center;}
</style>
</head>
<body>
{{-- 搜尋表單 --}}
<div class='box'>
    <div class='title'>搜尋表單</div>
    <form action='{{ $url }}' method='GET'>
        @foreach ($search_field as $field)
            <div>{{ $field }}: <input type='text' name='{{ $field }}' value='{{ $search_param[$field] or '' }}'></div>
        @endforeach
        <input type='submit' value='搜尋'>
    </form>
</div>

{{-- 工作列表 --}}
<div class='box'>
    <div class='title'>工作列表</div>
    <?php
        echo "<pre>data = " . print_r($data, TRUE). "</pre>";
    ?>
</div>
</body>
</html>
