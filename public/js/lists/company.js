$(function() {
	(function() {
		var urlParams = JOBFINDER.getUrlParams();
		var orderby = JOBFINDER.getOrderBy();
		JOBFINDER.bindOrder();
		JOBFINDER.jobList.onstatechange();
		JOBFINDER.listJobs({
			apiUrl: '/company/',
			page: urlParams.page,
			page_size: 10,
			orderby: orderby
		}, JOBFINDER.companyList.renderView);
	}());
});
