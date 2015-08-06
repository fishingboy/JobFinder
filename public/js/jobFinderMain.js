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

			JOBFINDER.currentPage = res.curr_page;
			JOBFINDER.total_page = res.total_page;
			var pagerArgs = {
				minPage: 1,
				currentPage: res.curr_page,
				rangeScope: 4,
				totalPage: JOBFINDER.total_page
			};
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
};

/*建立分頁元素*/
JOBFINDER.pagination.createPager = function(options) {
	// var settings = options || {};
	var settings = $.extend({
		pagination: [],

	}, options || {});
	var pageRange = pagination.pageRangeCalculate(settings);

	for (var page = pageRange.start; page <= pageRange.end; page++) {
		settings.pagination.push(page);
	}
	console.log(settings);
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

	JOBFINDER.listJobs(settings, renderView);
};

JOBFINDER.pagination.getNextPage = function() {
	// e.preventDefault();
	var urlParams = getUrlParams();
	var page = urlParams.page || 1;
	if (page >= JOBFINDER.total_page) {
		page = JOBFINDER.total_page;
	} else {
		page++;
	}
	console.log(JOBFINDER.total_page);
	console.log(page);
	return page;
};

JOBFINDER.pagination.getPrevPage = function() {
	var urlParams = getUrlParams();
	var page = urlParams.page || 1;
	if (page - 1 <= 0) {
		page = 1;
	} else {
		page--;
	}
	return page;
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

JOBFINDER.pushState = function(options) {
	var urlParams = JOBFINDER.getUrlParams();
	var qs = "";
	qs = "?page=" + options.page;
	var salarySort = options.salarySort || urlParams.salarySort || "DESC";
	qs += "&salarySort=" + salarySort;
	// if (typeof options.salarySort !== "undefined") {}
	History.pushState({
		state: options.page
	}, "JobFinder", qs);
};

JOBFINDER.companyList.pushState = function(options) {
	var urlParams = JOBFINDER.companyList.getUrlParams();
	var page = options.page || urlParams.page || 1;
	var qs = "";
	qs = "?page=" + page;

	var capitalSort = options.capitalSort || urlParams.capitalSort || "DESC";
	qs += "&capitalSort=" + capitalSort;

	History.pushState({
		state: options.page
	}, "JobFinder", qs);
};

/* 取得網址列參數並做處理(job) */
JOBFINDER.getUrlParams = function() {
	var urlParams = window.getUrlParams();
	var result = urlParams;
	result.page = urlParams.page || 1;
	result.salarySort = urlParams.salarySort || "DESC";
	return result;
};

/* 取得參數並整理為orderby參數處理(job) */
JOBFINDER.getOrderBy = function() {
	var urlParams = JOBFINDER.getUrlParams();
	var orderby = {};
	orderby.sal_month_high = urlParams.salarySort;
	orderby.sal_month_low = urlParams.salarySort;
	return orderby;
};

/* 取得網址列參數並做處理(company) */
JOBFINDER.companyList.getUrlParams = function() {
	var urlParams = window.getUrlParams();
	var result = urlParams;
	result.page = urlParams.page || 1;
	result.capitalSort = urlParams.capitalSort || "DESC";
	return result;
};

/* 取得參數並整理為orderby參數處理(company) */
JOBFINDER.companyList.getOrderBy = function() {
	var urlParams = JOBFINDER.getUrlParams();
	var orderby = {};
	orderby.capital = urlParams.capitalSort;
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

var getUrlParams = function() {
	var match,
		pl = /\+/g, // Regex for replacing addition symbol with a space
		search = /([^&=]+)=?([^&]*)/g,
		decode = function(s) {
			return decodeURIComponent(s.replace(pl, " "));
		},
		query = window.location.search.substring(1),
		urlParams = {};

	while ((match = search.exec(query)) !== null)
		urlParams[decode(match[1])] = decode(match[2]);
	// console.log(urlParams);
	return urlParams;
};

//# sourceMappingURL=jobFinderMain.js.map