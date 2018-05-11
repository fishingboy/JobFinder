<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Job API 測試</title>
<script src='http://code.jquery.com/jquery-1.11.3.min.js'></script>
<script src='http://johnny.github.io/jquery-sortable/js/jquery-sortable.js'></script>
<style type="text/css">@import url(/css/test.css);</style>
<script src='/js/test/like.js'></script>
@if ($controller == 'App\Http\Controllers\FavoriteController')
    <script src='/js/test/sort.js'></script>
    <style type="text/css">@import url(/css/sorted_table.css);</style>
@endif
</head>
<body>
<div class='base'>
    <div>狀態
        count:      {{ $count }},
        page_size:  {{ $page_size }},
        curr_page:  {{ $curr_page }},
        total_page: {{ $total_page }}
    </div>
    <br>

    {{-- 表單 --}}
    @if ($controller != 'App\Http\Controllers\FavoriteController')
        @include('test/search_form')
    @endif

    <div>

        @include('test/navi')

        {{-- 列表 --}}
        <div class='tableBox'>
            <table class='sorted_table'>
                <thead>
                    <tr>
                        <th>職缺名稱</th>
                        <th>公司</th>
                        <th class='width100'>薪資</th>
                        <th>地址</th>
                        <th class='width100'>員工人數</th>
                        <th>資本額</th>
                        <th>時間</th>
                    </tr>
                </thead>

                <tbody>
                    @if (count($rows) == 0)
                    <tr><td colspan="999" style='text-align:center'>尚無資料</td></tr>
                    @endif

                    @foreach ($rows as $row)
                    <tr id='row_{{ $row->favoriteID or '' }}'>
                        <td {!! ($row->job_readed == 1) ? "class='readed'" : '' !!}>
                            {{-- 加入最愛按鈕 --}}
                            @if ($controller != 'App\Http\Controllers\FavoriteController')
                            <img id='like_1_{{ $row->jobID }}' class='like' title='加入最愛' src='/img/like_white.png'>
                            @endif

                            <a href='/go/job/{{ $row->j_code }}' target='_blank'>
                                {{ $row->title }}
                                @if ($row->is_new)
                                    <span style="color:red; font-weight: bold;">(new)</span>
                                @endif
                            </a>
                        </td>
                        <td {!! ($row->company_readed == 1) ? "class='readed'" : '' !!}>
                            <a href='/go/company/{{ $row->c_code }}' target='_blank'>
                                {{ $row->name }}
                            </a>
                        </td>
                        <td class='center'>
                            {{ $row->pay }}
                        </td>
                        <td>{{ $row->job_addr_no_descript }}{{ $row->job_address }}</td>
                        <td class='center'>{{ $row->employees }}</td>
                        <td class='center'>{{ $row->capital }}</td>
                        <td class='center'>
                            {{ (new DateTime($row->created_at))->format("m-d H:i") }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @include('test/navi')
    </div>
</body>
</html>
