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

// console.log(jobList === JOBFINDER.jobList);
jobList.listJobs = function(renderView) {
	$.ajax({
		url: "job/",
		method: "GET",
		dataType: "JSON"
	}).done(function(res) {
		console.log(res);
		renderView(res);
	});
};

jobList.renderView = function(data) {
	var template = $('#jobListTmpl').html();
	Mustache.parse(template);
	var rendered = Mustache.render(template, data);
	$('#jobListBody').append(rendered);
};

JOBFINDER.jobList.listJobs(JOBFINDER.jobList.renderView);
