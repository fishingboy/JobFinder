var urlParams = JOBFINDER.getUrlParams(),
	orderby = JOBFINDER.getOrderBy(),
	options = {
		apiUrl: '/job/',
		page: urlParams.page,
		page_size: 50,
		orderby: orderby
	};

$(function() {

	//init
	(function() {
		JOBFINDER.bindOrder();
		JOBFINDER.jobList.onstatechange(options, JOBFINDER.jobList.renderView);
		JOBFINDER.listJobs(options, JOBFINDER.jobList.renderView);
		JOBFINDER.changeBtnSortClass($("#btnSortSalary").find("i").eq(0),
			urlParams
			.salarySort);
	}());

	/* bind 分頁標籤 event */
	$("body").on("click", "#pagerUl a[name='goToPage']", function(e) {
		e.preventDefault();
		var page = $(this).attr("href");
		JOBFINDER.pushState({
			page: page
		});
	});

	$("body").on("click", "#goToPrev", function(e) {
		e.preventDefault();
		var page = JOBFINDER.pagination.getPrevPage();
		JOBFINDER.pushState({
			page: page
		});
	});

	$("body").on("click", "#goToNext", function(e) {
		e.preventDefault();
		var page = JOBFINDER.pagination.getNextPage();
		JOBFINDER.pushState({
			page: page
		});
	});

});
