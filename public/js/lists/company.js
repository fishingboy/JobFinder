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

		/* bind 分頁標籤 event */
		$("body").on("click", "#pagerUl a[name='goToPage']", function(e) {
			e.preventDefault();
			var page = $(this).attr("href");

			JOBFINDER.companyList.pushState({
				page: page
			});
		});

		$("body").on("click", "#goToPrev", function(e) {
			e.preventDefault();
			var urlParams = getUrlParams();
			var page = urlParams.page;
			if (page - 1 <= 0) {
				page = 1;
			} else {
				page--;
			}

			JOBFINDER.companyList.pushState({
				page: page
			});
		});

		$("body").on("click", "#goToNext", function(e) {
			e.preventDefault();
			var urlParams = getUrlParams();
			var page = urlParams.page;
			if (page >= settings.totalPage) {
				page = settings.totalPage;
			} else {
				page++;
			}

			JOBFINDER.companyList.pushState({
				page: page
			});
		});

	}());
});
