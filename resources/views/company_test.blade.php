<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Company API 測試</title>
<style>
    body {background: #000; color:#FFF;}
    a {text-decoration:none; color:#AAF;}
    a:hover{color:#AFA;}
    .base {margin: 0 auto; width:95%;}
    .tableBox {}
    .center   {text-align: center;}
    .readed a, a:visited {color:#999;}
    table {width: 100%; border-collapse: collapse;}
    table tr:hover{background: #666}
    table tr th {background: #696}
    table tr td, table tr th{border:1px solid #CCC; padding: 3px;}
</style>
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

    {{-- 排序 --}}
    排序:
    <a href='{{ $url['job_count_desc_url'] }}'>職缺數 ▼</a> |
    <a href='{{ $url['job_count_asc_url'] }}'>職缺數 ▲</a> |
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

    {{-- 列表 --}}
    <div class='tableBox'>
        <table>
            <tr>
                <th>職缺數</th>
                <th>公司名稱</th>
                <th>員工人數</th>
                <th>資本額</th>
                <th>地址</th>
            </tr>

            @foreach ($rows as $row)
            <tr>
                <td class='center'>
                    <a href='/job/test/?companyID={{ $row->companyID }}&page_size=100' target='_blank'>
                        <div>{{ $row->job_count }}</div>
                    </a>
                </td>
                <td  {!! ($row->company_readed == 1) ? "class='readed'" : '' !!}>
                    <a href='/go/company/{{ $row->c_code }}' target='_blank'>
                        {{ $row->name }}
                    </a>
                </td>
                <td class='center'>{{ number_format($row->employees) }}</td>
                <td class='center'>{{ App\Library\Lib::number2capital($row->capital) }}</td>
                <td class='center'>{{ $row->addr_no_descript }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    {{-- 排序 --}}
    排序:
    <a href='{{ $url['job_count_desc_url'] }}'>職缺數 ▼</a> |
    <a href='{{ $url['job_count_asc_url'] }}'>職缺數 ▲</a> |
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
