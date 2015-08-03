@extends('lists/main')

@section('content')
<section class="content">
	<table class="jobtable">
		<thead>
			<tr>
				<th>職缺列表</th>
				<th>刊登日期</th>
				<th>公司名稱<button class="table-sort"><i class="fa fa-chevron-down"></i></button></th>
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
	<div id="pagerBody" class="pagenavi">	</div></section><script id="jobListTmpl" type="text/x-handlebars-template">		@{{#each rows}}		<tr>			<td><button data-name="cId_@{{companyID}}" title="詳細內容" class="btn-detail toggle-detail"><i class="fa fa-search"></i></button></td>			<td>@{{appear_date}}</td>			<td><div class="fixword">@{{name}}</div></td>			<td>@{{addr_no_descript}} @{{address}}</td>			<td>@{{employees}}</td>			<td>@{{period}}</td>			<td>@{{job_count}}</td>			<td>@{{capital}}</td>		</tr>			@{{#each jobs}}			<tr name="cId_@{{companyID}}" class="job-detail" id="detail-201405AP26001420" style="display: table-row;">				<td colspan="9">					<div class="detail-content">						<table class="jobtable">							<thead>								<tr>									<th>職稱</th>									<th>新資範圍</th>									<th>經歷需求</th>									<th>亂寫</th>									<th>亂寫</th>									<th>亂寫</th>								</tr>							</thead>							<tbody>								<tr>									<td><div class="fixword">@{{this.title}}</div></td>									<td>@{{this.pay}}</td>									<td>@{{this.period}}</td>									<td></td>									<td></td>									<td></td>								</tr>							</tbody>						</table>					</div>				</td>			</tr>			@{{/each}}		@{{/each}}</script><script id="pagerTmpl" type="text/x-handlebars-template">	<ul id="pagerUl">		<li class="prev"><a id="goToPrev" href=""><i class="fa fa-chevron-left"></i></a></li>		@{{#each pagination}}			@{{#isActive this}}				<li  class="active"><a name="goToPage" href="@{{this}}" >@{{this}}</a></li><!-- 作用中 -->			@{{else}}				<li><a name="goToPage" href="@{{this}}" >@{{this}}</a></li>			@{{/isActive}}		@{{/each}}		<li class="next"><a id="goToNext" href=""><i class="fa fa-chevron-right"></a></i></li>	</ul></script>@stop@section('customJs')	<script src="{{ URL::asset('/js/3rd-party/jquery.history.js') }}" charset="utf-8"></script>	<script src="{{ URL::asset('/js/lists/company.js') }}" charset="utf-8"></script>@stop