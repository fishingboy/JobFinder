<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Company API 測試</title>
<style>
    .base {margin: 0 auto; width:1200px;}
    .tableBox {border: 1px solid #ccc;}
    .center   {text-align: center;}
    table {width: 100%; border-collapse: collapse;}
    table tr td, table tr th{border:1px solid #CCC; padding: 3px;}
</style>
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

    <div class='tableBox'>
        <table>
            <tr>
                <th>公司名稱</th>
                <th>員工人數</th>
                <th>資本額</th>
                <th>地址</th>
                <th>職缺數</th>
            </tr>

            @foreach ($rows as $row)
            <tr>
                <td>
                    <a href='{{ App\Library\Lib::get_104_company_url($row->c_code) }}' target='_blank'>
                        {{ $row->name }}
                    </a>
                </td>
                <td class='center'>{{ number_format($row->employees) }}</td>
                <td class='center'>{{ App\Classes\Crawler104::number2capital($row->capital) }}</td>
                <td class='center'>{{ $row->addr_no_descript }}</td>
                <td class='center'>{{ $row->job_count }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>
