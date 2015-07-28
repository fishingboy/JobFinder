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

// console.log(jobList === JOBFINDER.jobList);
jobList.listJobs = function(options, renderView) {
	var settings = $.extend({
		page: 1,
		page_size: 30
	}, options || {});

	$.ajax({
		url: "/job/",
		method: "GET",
		dataType: "JSON",
		data: settings
	}).done(function(res) {
		console.log(res);
		if (res.status === true) {
			renderView(res);
		} else {
			alert("取得資料失敗");
		}
	});
};

jobList.renderView = function(data) {
	var source = $("#jobListTmpl").html();
	// Mustache.parse(template);
	var template = Handlebars.compile(source);
	var rendered = template(data);
	$("#jobListBody").append(rendered);
};

companyList.listJobs = function(options, renderView) {
	var settings = $.extend({
		page: 1,
		page_size: 5
	}, options || {});

	$.ajax({
		url: "/company/",
		method: "GET",
		dataType: "JSON",
		data: settings
	}).done(function(res) {
		console.log(res);
		if (res.status === true) {
			renderView(res);
		} else {
			alert("取得資料失敗");
		}
	});
};

companyList.renderView = function(data) {
	var template = $("#jobListTmpl").html();
	Mustache.parse(template);
	var rendered = Mustache.render(template, data);
	$("#jobListBody").append(rendered);

	$('.job-detail').hide();

	// show/hide job detail
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

JOBFINDER.createPager = function(options) {
	var settings = $.extend({
		currentPage: 10,
		minPage: 1,
		totalPage: 20,
		rangeScope: 2,
		pagination: []
	}, options || {});
	//
	// var startPage = settings.currentPage;
	// var endPage = settings.totalPage;
	//
	// if (settings.currentPage - settings.rangeScope <= 0) {
	// 	endPage = settings.minPage + (settings.rangeScope * 2);
	// } else if ((settings.currentPage + settings.rangeScope) >= settings.totalPage) {
	// 	startPage = settings.totalPage - (settings.rangeScope * 2);
	// } else {
	// 	startPage = settings.currentPage - settings.rangeScope;
	// 	endPage = settings.currentPage + settings.rangeScope;
	// }


	var pageRange = JOBFINDER.pageRangeCalculate(settings);
	for (var page = pageRange.start; page <= pageRange.end; page++) {
		settings.pagination.push(page);
	}

	settings.isActive = function() {
		console.dir(this);
	};

	// var template = $("#pagerTmpl").html();
	// Mustache.parse(template);
	// var rendered = Mustache.render(template, settings);
	// $('#pagerBody').append(rendered);
	var source = $("#pagerTmpl").html();
	var template = Handlebars.compile(source);
	var rendered = template(settings);
	$('#pagerBody').append(rendered);
};

JOBFINDER.pageRangeCalculate = function(options) {
	var settings = $.extend({
			currentPage: 10,
			minPage: 1,
			totalPage: 20,
			rangeScope: 2
		}, options || {}),
		startPage = settings.currentPage,
		endPage = settings.totalPage;

	if (settings.currentPage - settings.rangeScope <= 0) {
		endPage = settings.minPage + (settings.rangeScope * 2);
	} else if ((settings.currentPage + settings.rangeScope) >= settings.totalPage) {
		startPage = settings.totalPage - (settings.rangeScope * 2);
	} else {
		startPage = settings.currentPage - settings.rangeScope;
		endPage = settings.currentPage + settings.rangeScope;
	}

	return {
		start: startPage,
		end: endPage
	};
};

Handlebars.registerHelper('isActive', function(context, options) {
	if (context === options.data.root.currentPage) {
		return options.fn(this);
	} else {
		return options.inverse(this);
	}
});

JOBFINDER.createPager();
