<form method='GET'>
    <input type='hidden' name='companyID' value='{{ $companyID or '' }}'>
    <input type='hidden' name='page' value='{{ $curr_page }}'>
    <input type='hidden' name='page_size' value='{{ $page_size }}'>
    @foreach($orderby as $key => $value)
        <input type='hidden' name='orderby[{{ $key }}]' value='{{ $value }}'>
    @endforeach
    <div style="padding: 2px;"><b>包含(, 分隔，有其中一個關鍵字就有):</b> <input type='text' name='keyword' value='{{ $keyword }}' style='width:300px;'></div>
    <div style="padding: 2px;"><b>不包含(, 分隔，不能出現任一個關鍵字):</b> <input type='text' name='not_keyword' value='{{ $not_keyword }}' style='width:300px;'> <input type='submit' value='搜尋'></div>
    
</form>
