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
	var template = $("#jobListTmpl").html();
	Mustache.parse(template);
	var rendered = Mustache.render(template, data);
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
