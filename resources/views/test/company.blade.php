<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Company API 測試</title>
<script src='http://code.jquery.com/jquery-1.11.3.min.js'></script>
<script src='http://johnny.github.io/jquery-sortable/js/jquery-sortable.js'></script>
<style type="text/css">@import url(/css/test.css);</style>
@if ($controller == 'App\Http\Controllers\FavoriteController')
    <script src='/js/test/sort.js' type="text/javascript"></script>
    <script src='/js/test/like.js'></script>
    <style type="text/css">@import url(/css/sorted_table.css);</style>
@endif
</head>
<body>
<div class='base'>
    <div>狀態
        count:      {{ $count }},
        page_size:  {{ $page_size }},
        curr_page:  {{ $curr_page }},
        total_page: {{ $total_page }},
        orderby:    {{ json_encode($orderby) }}
    </div>
    <br>

    @include('test/navi')

    {{-- 列表 --}}
    <div class='tableBox'>
        <table class='sorted_table'>
            <thead>
              <tr>
                  <th>職缺數</th>
                  <th>公司名稱</th>
                  <th>員工人數</th>
                  <th>資本額</th>
                  <th>地址</th>
              </tr>
            </thead>

            <tbody>
                @if (count($rows) == 0)
                <tr><td colspan="999" style='text-align:center'>尚無資料</td></tr>
                @endif

                @foreach ($rows as $row)
                <tr id='row_{{ $row->favoriteID or '' }}'>
                    <td class='center'>
                        <a href='/job/test/?companyID={{ $row->companyID }}&page_size=100' target='_blank'>
                            <div>{{ $row->job_count }}</div>
                        </a>
                    </td>
                    <td  {!! ($row->company_readed == 1) ? "class='readed'" : '' !!}>
                        {{-- 加入最愛按鈕 --}}
                        @if ($controller != 'App\Http\Controllers\FavoriteController')
                        <img id='like_2_{{ $row->companyID }}' class='like' title='加入最愛' src='/img/like_white.png'>
                        @endif

                        <a href='/go/company/{{ $row->c_code }}' target='_blank'>
                            {{ $row->name }}
                        </a>
                    </td>
                    <td class='center'>{{ $row->employees }}</td>
                    <td class='center'>{{ $row->capital }}</td>
                    <td class='center'>{{ $row->addr_no_descript }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('test/navi')

</div>
</body>
</html>
