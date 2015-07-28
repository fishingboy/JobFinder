$(function() {
	JOBFINDER.jobList.listJobs({}, JOBFINDER.jobList.renderView);
	$("#pagerUl a[name='goToPage']").click(function(e) {
		e.preventDefault();
		var page = $(this).attr("href");

		History.pushState({
			state: page
		}, "Page" + page, "?page=" + page); // logs {state:1}, "State 1", "?state=1"

		// JOBFINDER.jobList.listJobs(options, JOBFINDER.jobList.renderView);
	});
});
