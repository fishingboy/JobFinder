$(function() {
	var params = getUrlParams();
	var page = typeof params.page === 'undefined' ? 1 : params.page;
	JOBFINDER.listJobs({
		apiUrl: '/job/',
		page: page,
		page_size: 50
	}, JOBFINDER.jobList.renderView);
});
