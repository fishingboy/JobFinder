@extends('lists/main')

@section('content')
<section class="content">
	<table class="jobtable">
		<thead>
			<tr>
				<!-- <th><input type="checkbox" class="checkbox-orders" name="checkbox-orders"></th> -->
				<!-- <th>職缺列表</th> -->
				<th>刊登日期</th>
				<th>職稱</th>
				<th>公司名稱<button class="table-sort"><i class="fa fa-chevron-down"></i></button></th>
				<th>新資範圍<button class="table-sort"><i class="fa fa-chevron-up"></i></button></th>
				<th>所在地</th>
				<th>總人數</th>
				<th>經歷需求</th>
				<th>職缺數</th>
				<th>資本額</th>
			</tr>
		</thead>
	<tbody id="jobListBody">
	</tbody>
	</table>
</section><script id="jobListTmpl" type="x-tmpl-mustache">		@{{#rows}}		<tr>			<td>@{{created_at}}</td>			<td><div class="fixword">@{{title}}</td>			<td><div class="fixword">@{{name}}</div></td>			<td>@{{sal_month_low}} ~ @{{sal_month_high}}</td>			<td>@{{job_addr_no_descript}}</td>			<td>@{{employees}}</td>			<td>@{{period}}</td>			<td>@{{job_count}}</td>			<td>@{{capital}}</td>		</tr>		@{{/rows}}</script>@stop@section('customJs')	<script src="{{ URL::asset('/js/lists/job.js') }}" charset="utf-8"></script>@stop