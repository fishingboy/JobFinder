$(function() {
	(function() {
		var urlParams = JOBFINDER.getUrlParams();
		var orderby = JOBFINDER.getOrderBy();
		JOBFINDER.bindOrder();
		JOBFINDER.jobList.onstatechange();
		JOBFINDER.listJobs({
			apiUrl: '/job/',
			page: urlParams.page,
			page_size: 50,
			orderby: orderby
		}, JOBFINDER.jobList.renderView);
	}());
});
