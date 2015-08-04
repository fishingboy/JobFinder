$(function() {
	(function() {
		var urlParams = JOBFINDER.getUrlParams(),
			orderby = JOBFINDER.getOrderBy(),
			options = {
				apiUrl: '/company/',
				page: urlParams.page,
				page_size: 10,
				orderby: orderby
			};
		JOBFINDER.bindOrder();
		JOBFINDER.jobList.onstatechange(options, JOBFINDER.companyList.renderView);
		JOBFINDER.listJobs(options, JOBFINDER.companyList.renderView);
	}());
});
