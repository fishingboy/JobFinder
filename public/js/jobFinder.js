var JOBFINDER = JOBFINDER || {};

JOBFINDER.namespace = function(ns_string) {
	var parts = ns_string.split('.'),
		parent = JOBFINDER,
		i;
	// strip redundant leading global
	if (parts[0] === "JOBFINDER") {
		parts = parts.slice(1);
	}
	for (i = 0; i < parts.length; i += 1) {
		// create a property if it doesn't exist
		if (typeof parent[parts[i]] === "undefined") {
			parent[parts[i]] = {};
		}
		parent = parent[parts[i]];
	}
	return parent;
};

var jobList = JOBFINDER.namespace("JOBFINDER.jobList");
var companyList = JOBFINDER.namespace("JOBFINDER.companyList");
var pagination = JOBFINDER.namespace("JOBFINDER.pagination");

/*
 * 取得工作資訊
 * @todo 預計重構為 取得工作資訊,公司資訊共用。
 */
JOBFINDER.listJobs = function(options, renderView) {
	var settings = $.extend({
		apiUrl: '/job/',
		page: 1,
		page_size: 50,
		orderby: {
			sal_month_high: 'DESC',
			sal_month_low: 'DESC'
		}
	}, options || {});

	var sendData = {
		page: settings.page,
		page_size: settings.page_size,
		orderby: settings.orderby
	};

	// console.log(sendData);

	$.ajax({
		url: settings.apiUrl,
		method: "GET",
		dataType: "JSON",
		data: sendData
	}).done(function(res) {
		// console.log(res);
		if (res.status === true) {
			$("#jobListBody").empty();

			var stateData = History.getState();
			/*如果是進入首頁 網址列無參數 預設塞入第一頁的pushState值*/
			if (!stateData.data) {
				History.pushState({
					state: page
				}, "Page" + page, "?page=" + page); // logs {state:1}, "State 1", "?state=1"
			}

			renderView(res);

			var pagerArgs = {
				currentPage: res.curr_page,
				minPage: 1,
				totalPage: res.total_page,
				rangeScope: 2
			};
			// console.log(pagerArgs);
			pagination.createPager(pagerArgs);
			window.scrollTo(0, 0);
		} else {
			alert("取得資料失敗");
		}
	});
};

/*render 工作列表*/
JOBFINDER.jobList.renderView = function(data) {
	var source = $("#jobListTmpl").html();
	var template = Handlebars.compile(source);
	var rendered = template(data);
	$("#jobListBody").append(rendered);
};

JOBFINDER.companyList.renderView = function(data) {
	var source = $("#jobListTmpl").html();
	// console.log(source);
	var template = Handlebars.compile(source);
	var rendered = template(data);
	$("#jobListBody").append(rendered);
	$('.job-detail').hide();
	//
	// // show/hide job detail
	$(".toggle-detail").each(function() {
		$(this).click(function() {
			$("tr[name='" + $(this).data('name') + "']").toggle();
		});
	});

	// $('.toggle-detail').each(function() {
	// 	var trBgColor = $(this).parent('td').css('background-color');
	// 	$(this).click(function() {
	// 		$('#' + $(this).data('name')).toggle();
	// 	});
	// });

	// job detail switch
	// $('.toggle-detail').each(function() {
	// 	$(this).click(function() {
	// 		$(this).parents('.job-detail').find('.detail-content').hide();
	// 		$(this).parents('.job-detail').find('.' + $(this).data('name')).show();
	// 	});
	// });
};

/*建立分頁元素*/
JOBFINDER.pagination.createPager = function(options) {
	// var settings = options || {};
	var settings = $.extend({
		pagination: []
	}, options || {});
	var pageRange = pagination.pageRangeCalculate(settings);

	for (var page = pageRange.start; page <= pageRange.end; page++) {
		settings.pagination.push(page);
	}

	$('#pagerBody').empty();
	var source = $("#pagerTmpl").html();
	var template = Handlebars.compile(source);
	var rendered = template(settings);
	$('#pagerBody').append(rendered);

};

/* 計算分頁範圍 */
JOBFINDER.pagination.pageRangeCalculate = function(options) {
	var settings = $.extend({
			currentPage: 1,
			minPage: 1,
			totalPage: 20,
			rangeScope: 2
		}, options || {}),
		startPage = settings.currentPage,
		endPage = settings.totalPage;

	if (settings.currentPage - settings.rangeScope <= 0) {
		endPage = settings.minPage + (settings.rangeScope * 2);
		startPage = settings.minPage;
	} else if ((settings.currentPage + settings.rangeScope) >= settings.totalPage) {
		startPage = settings.totalPage - (settings.rangeScope * 2);
	} else {
		startPage = settings.currentPage - settings.rangeScope;
		endPage = settings.currentPage + settings.rangeScope;
	}
	// console.log(startPage);
	return {
		start: startPage,
		end: endPage
	};
};

/*重新刷新工作列表內容*/
JOBFINDER.pagination.refreshPage = function(options, renderView) {
	var urlParams = JOBFINDER.getUrlParams();
	var orderby = {};
	if (options.apiUrl === "/job/") {
		orderby = JOBFINDER.getOrderBy();
	} else {
		orderby = JOBFINDER.companyList.getOrderBy();
	}

	var settings = {
		apiUrl: options.apiUrl,
		page_size: options.page_size,
		page: urlParams.page,
		orderby: orderby
	};
	// JOBFINDER.listJobs(options, JOBFINDER.jobList.renderView);
	JOBFINDER.listJobs(settings, renderView);
};

/* Handlebars helper 判斷分頁元素是否為當前頁面  顯示為highlight*/
Handlebars.registerHelper('isActive', function(context, options) {
	if (context === options.data.root.currentPage) {
		return options.fn(this);
	} else {
		return options.inverse(this);
	}
});

//監聽並觸發 popstate 動作
JOBFINDER.jobList.onstatechange = function(options, renderView) {
	window.onstatechange = function() {
		JOBFINDER.pagination.refreshPage(options, renderView);
	};
};

/* 綁定排序EVENT */
JOBFINDER.bindOrder = function() {
	$('#btnSortCompany, #btnSortSalary').click(function() {
		var urlParams = JOBFINDER.getUrlParams();
		var iElement = $(this).find('i').eq(0);
		var btnId = $(this).attr("id");

		var pushStateData = {
			page: urlParams.page
		};

		/*change sort button class*/
		switch (btnId) {
			case 'btnSortSalary':
				pushStateData.salarySort = JOBFINDER.toggleSort(urlParams.salarySort);
				JOBFINDER.changeBtnSortClass(iElement, pushStateData.salarySort);
				break;
		}

		JOBFINDER.pushState(pushStateData);
	});
};


JOBFINDER.bindOrder = function() {
	$('#btnSortCompany, #btnSortSalary').click(function() {
		var urlParams = JOBFINDER.getUrlParams();
		var iElement = $(this).find('i').eq(0);
		var btnId = $(this).attr("id");

		var pushStateData = {
			page: urlParams.page
		};

		/*change sort button class*/
		switch (btnId) {
			case 'btnSortSalary':
				pushStateData.salarySort = JOBFINDER.toggleSort(urlParams.salarySort);
				JOBFINDER.changeBtnSortClass(iElement, pushStateData.salarySort);
				break;
		}

		JOBFINDER.pushState(pushStateData);
	});
};


JOBFINDER.bindCompanyOrder = function() {
	// $('#btnSortCompany').click(function() {
	// 	var urlParams = JOBFINDER.getUrlParams();
	// 	var iElement = $(this).find('i').eq(0);
	// 	var btnId = $(this).attr("id");
	//
	// 	var pushStateData = {
	// 		page: urlParams.page
	// 	};
	//
	// 	/*change sort button class*/
	// 	switch (btnId) {
	// 		case 'btnSortSalary':
	// 			pushStateData.salarySort = JOBFINDER.toggleSort(urlParams.salarySort);
	// 			JOBFINDER.changeBtnSortClass(iElement, pushStateData.salarySort);
	// 			break;
	// 	}
	//
	// 	JOBFINDER.pushState(pushStateData);
	// });
};

JOBFINDER.pushState = function(options) {
	var qs = "";
	qs = "?page=" + options.page;
	if (typeof options.salarySort !== "undefined") {
		qs += "&salarySort=" + options.salarySort;
	}
	History.pushState({
		state: options.page
	}, "JobFinder", qs); // logs {state:1}, "State 1", "?state=1"
};

JOBFINDER.companyList.pushState = function(options) {
	var qs = "";
	qs = "?page=" + options.page;
	// if (typeof options.salarySort !== "undefined") {
	// 	qs += "&salarySort=" + options.salarySort;
	// }
	History.pushState({
		state: options.page
	}, "JobFinder", qs); // logs {state:1}, "State 1", "?state=1"
};

/* 取得網址列參數並做處理 */
JOBFINDER.getUrlParams = function() {
	var urlParams = window.getUrlParams();
	var result = {};
	result.page = urlParams.page || 1;
	result.salarySort = urlParams.salarySort || "DESC";
	return result;
};

JOBFINDER.getOrderBy = function() {
	var urlParams = JOBFINDER.getUrlParams();
	var orderby = {};
	orderby.sal_month_high = urlParams.salarySort;
	orderby.sal_month_low = urlParams.salarySort;
	return orderby;
};

JOBFINDER.companyList.getOrderBy = function() {
	var urlParams = JOBFINDER.getUrlParams();
	var orderby = {};
	return orderby;
};

JOBFINDER.changeBtnSortClass = function(obj, sort) {
	if (sort === "DESC") {
		obj.addClass("fa-chevron-down");
		obj.removeClass("fa-chevron-up");
	} else {
		obj.addClass("fa-chevron-up");
		obj.removeClass("fa-chevron-down");
	}
};

JOBFINDER.toggleSort = function(sort) {
	return sort === "DESC" ? "ASC" : "DESC";
};
