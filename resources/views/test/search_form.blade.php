<form method='GET'>
    <input type='hidden' name='page' value='{{ $curr_page }}'>
    <input type='hidden' name='page_size' value='{{ $page_size }}'>
    @foreach($orderby as $key => $value)
        <input type='hidden' name='orderby[{{ $key }}]' value='{{ $value }}'>
    @endforeach
    <input type='text' name='keyword' value='{{ $keyword }}' style='width:300px;'>
    <input type='submit' value='搜尋'>
</form>
