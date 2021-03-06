var urlParams = JOBFINDER.companyList.getUrlParams(),
	orderby = JOBFINDER.companyList.getOrderBy(),
	options = {
		apiUrl: '/company/',
		page: urlParams.page,
		page_size: 10,
		orderby: orderby
	};

$(function() {
	(function() {
		JOBFINDER.jobList.onstatechange(options, JOBFINDER.companyList.renderView);
		JOBFINDER.listJobs(options, JOBFINDER.companyList.renderView);
		JOBFINDER.changeBtnSortClass($("#btnSortCapital").find("i").eq(0),
			urlParams
			.capitalSort);
	}());

	/* bind 分頁標籤 event */
	$("body").on("click", "#pagerUl a[name='goToPage']", function(e) {
		e.preventDefault();
		var urlParams = getUrlParams();
		var page = $(this).attr("href");
		JOBFINDER.companyList.pushState({
			page: page
		});
	});

	$("body").on("click", "#goToPrev", function(e) {
		e.preventDefault();
		var page = JOBFINDER.pagination.getPrevPage();
		JOBFINDER.companyList.pushState({
			page: page
		});
	});

	$("body").on("click", "#goToNext", function(e) {
		e.preventDefault();
		var page = JOBFINDER.pagination.getNextPage();
		JOBFINDER.companyList.pushState({
			page: page
		});
	});

	$('#btnSortCapital').click(function() {
		var urlParams = JOBFINDER.companyList.getUrlParams();
		var iElement = $(this).find('i').eq(0);
		var btnId = $(this).attr("id");

		var pushStateData = {};

		/*change sort button class*/
		switch (btnId) {
			case 'btnSortCapital':
				pushStateData.capitalSort = JOBFINDER.toggleSort(urlParams.capitalSort);
				JOBFINDER.changeBtnSortClass(iElement, pushStateData.capitalSort);
				break;
		}

		JOBFINDER.companyList.pushState(pushStateData);
	});

});
