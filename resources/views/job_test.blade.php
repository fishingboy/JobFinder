<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Job API 測試</title>
<script src='http://code.jquery.com/jquery-1.11.3.min.js'></script>
<script src='http://johnny.github.io/jquery-sortable/js/jquery-sortable.js'></script>
<style type="text/css">@import url(/css/test.css);</style>
<style type="text/css">@import url(/css/sorted_table.css);</style>
<script type="text/javascript">
$(function()
{
    $('.sorted_table').sortable(
    {
        containerSelector: 'table',
        itemPath: '> tbody',
        itemSelector: 'tr',
        placeholder: '<tr class="placeholder"/>'
    });
});
</script>
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

    <div >
    {{-- 排序 --}}
    排序:
    <a href='{{ $url['pay_low_desc_url'] }}'>最低薪資 ▼</a> |
    <a href='{{ $url['pay_low_asc_url'] }}'>最低薪資 ▲</a> |
    <a href='{{ $url['pay_high_desc_url'] }}'>最高薪資 ▼</a> |
    <a href='{{ $url['pay_high_asc_url'] }}'>最高薪資 ▲</a> |
    <a href='{{ $url['employees_desc_url'] }}'>員工人數 ▼</a> |
    <a href='{{ $url['employees_asc_url'] }}'>員工人數 ▲</a> |
    <a href='{{ $url['capital_desc_url'] }}'>資本額 ▼</a> |
    <a href='{{ $url['capital_asc_url'] }}'>資本額 ▲</a> |
    <a href='{{ $url['time_desc_url'] }}'>同步時間 ▼</a> |
    <a href='{{ $url['time_asc_url'] }}'>同步時間 ▲</a>

    {{-- 分頁 --}}
    分頁:
    <a href='{{ $url['prev_url'] }}'>上一頁 ▲</a> |
    <a href='{{ $url['next_url'] }}'>下一頁 ▼</a>

    <div class='tableBox'>
        <table class='sorted_table'>
            <tr>
                <th>職缺名稱</th>
                <th class='width100'>薪資</th>
                <th>地址</th>
                <th>公司</th>
                <th class='width100'>員工人數</th>
                <th>資本額</th>
            </tr>

            @foreach ($rows as $row)
            <tr>
                <td {!! ($row->job_readed == 1) ? "class='readed'" : '' !!}>
                    <a href='/go/job/{{ $row->j_code }}' target='_blank'>
                        {{ $row->title }}
                    </a>
                </td>
                <td class='center'>
                    {{ $row->pay }}
                </td>
                <td>{{ $row->job_addr_no_descript }}{{ $row->job_address }}</td>
                <td {!! ($row->company_readed == 1) ? "class='readed'" : '' !!}>
                    <a href='/go/company/{{ $row->c_code }}' target='_blank'>
                        {{ $row->name }}
                    </a>
                </td>
                <td class='center'>{{ $row->employees }}</td>
                <td class='center'>{{ $row->capital }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    {{-- 排序 --}}
    排序:
    <a href='{{ $url['pay_low_desc_url'] }}'>最低薪資 ▼</a> |
    <a href='{{ $url['pay_low_asc_url'] }}'>最低薪資 ▲</a> |
    <a href='{{ $url['pay_high_desc_url'] }}'>最高薪資 ▼</a> |
    <a href='{{ $url['pay_high_asc_url'] }}'>最高薪資 ▲</a> |
    <a href='{{ $url['employees_desc_url'] }}'>員工人數 ▼</a> |
    <a href='{{ $url['employees_asc_url'] }}'>員工人數 ▲</a> |
    <a href='{{ $url['capital_desc_url'] }}'>資本額 ▼</a> |
    <a href='{{ $url['capital_asc_url'] }}'>資本額 ▲</a> |
    <a href='{{ $url['time_desc_url'] }}'>同步時間 ▼</a> |
    <a href='{{ $url['time_asc_url'] }}'>同步時間 ▲</a>

    {{-- 分頁 --}}
    分頁:
    <a href='{{ $url['prev_url'] }}'>上一頁 ▲</a> |
    <a href='{{ $url['next_url'] }}'>下一頁 ▼</a>
</div>
</body>
</html>
