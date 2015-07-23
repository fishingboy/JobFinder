<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Job API 測試</title>
<style>
    a {text-decoration:none;}
    .base {margin: 0 auto; width:95%;}
    .tableBox {}
    .center   {text-align: center;}
    .width100 {width:100px;}
    .readed a {color:#999;}
    table {width: 100%; border-collapse: collapse;}
    table tr:hover{background: #DDF}
    table tr th {background: #DFD}
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
                    {{ App\Library\Lib::convert_pay($row->sal_month_low, $row->sal_month_high) }}
                </td>
                <td>{{ $row->job_addr_no_descript }}{{ $row->job_address }}</td>
                <td {!! ($row->company_readed == 1) ? "class='readed'" : '' !!}>
                    <a href='/go/company/{{ $row->c_code }}' target='_blank'>
                        {{ $row->name }}
                    </a>
                </td>
                <td class='center'>{{ number_format($row->employees) }}</td>
                <td class='center'>{{ App\Library\Lib::number2capital($row->capital) }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>
