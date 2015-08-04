$(function() {
	(function() {
		var urlParams = JOBFINDER.getUrlParams(),
			orderby = JOBFINDER.getOrderBy(),
			options = {
				apiUrl: '/job/',
				page: urlParams.page,
				page_size: 50,
				orderby: orderby
			};
		JOBFINDER.bindOrder();
		JOBFINDER.jobList.onstatechange(options, JOBFINDER.jobList.renderView);
		JOBFINDER.listJobs(options, JOBFINDER.jobList.renderView);
	}());
});
