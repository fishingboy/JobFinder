var JGMAP = (function(google, $) {
	var myLocation = {};

	var myStorage = localStorage;

	var init = function() {

		if (typeof myStorage.latitude === "undefined" || typeof myStorage
			.longitude === "undefined") {

			if ("geolocation" in navigator) {
				navigator.geolocation.getCurrentPosition(function(
					position) {
					myLocation = new google.maps.LatLng(
						position.coords
						.latitude,
						position.coords.longitude);
					myStorage.setItem("latitude", position.coords
						.latitude);
					myStorage.setItem("longitude", position
						.coords
						.longitude);

					initGMap(myLocation);
				});
			}
		} else {
			myLocation = new google.maps.LatLng(
				myStorage
				.latitude,
				myStorage.longitude);
			initGMap(myLocation);
		}

	};

	function initGMap(myLocation) {

		var mapOptions = {
			center: myLocation,
			zoom: 16
		};

		var map = new google.maps.Map(document.getElementById(
				'map-canvas'),
			mapOptions);
		markJob(map);
	}

	var markJob = function(map) {
		$.ajax({
			method: "GET",
			url: "/job/position",
			dataType: "json"
		}).done(function(res) {

			res.rows.forEach(function(jobValue, jobKey) {
				var myLatlng = new google.maps.LatLng(jobValue.lat, jobValue.lon);
				var marker = new google.maps.Marker({
					position: myLatlng,
					map: map,
					title: jobValue.name
				});

				var infowindow = new google.maps.InfoWindow({
					content: "<div>" +
						"<H1>" + jobValue.name + "</H1>",
					maxWidth: 200
						// content: "<div>" +
						// 	"<H1>" + jobValue.name + "</H1>" +
						// 	"<p></p>" +
						// 	"<div>" + jobValue.profile + "</div>" +
						// 	"</div>",
						// maxWidth: 200
				});

				google.maps.event.addListener(marker, 'click', function() {
					// map.setZoom(20);
					map.setCenter(marker.getPosition());
					infowindow.open(map, marker);
				});
			});
		});
	};

	var buildTaipeiMrtSelector = function() {
		var source = $("#mrt-template").html();
		var template = Handlebars.compile(source);
		var html = template(mrtLocation);
		$("#mrtSelector").append(html);
	};

	return {
		init: init,
		// markJob: markJob
		buildTaipeiMrtSelector: buildTaipeiMrtSelector
	};
}(google, $));


var mrtLocation = mrtLocation || [];

mrtLocation = [{
	"id": 1,
	"name": "松山機場",
	"number": "7",
	"address": "10576臺北市松山區敦化北路338號",
	"latitude": 25.063718,
	"longitude": 121.549643
}, {
	"id": 2,
	"name": "中山國中",
	"number": "8",
	"address": "10476臺北市中山區復興北路376號",
	"latitude": 25.0607843,
	"longitude": 121.5439248
}, {
	"id": 3,
	"name": "南京東路",
	"number": "9",
	"address": "10550臺北市松山區南京東路3段253號",
	"latitude": 25.0519749,
	"longitude": 121.544514
}, {
	"id": 4,
	"name": "忠孝復興",
	"number": "10",
	"address": "10654臺北市大安區忠孝東路4段47號",
	"latitude": 25.0417843,
	"longitude": 121.5448626
}, {
	"id": 5,
	"name": "大安",
	"number": "11",
	"address": "10683臺北市大安區信義路4段2號",
	"latitude": 25.0331472,
	"longitude": 121.5438667
}, {
	"id": 6,
	"name": "科技大樓",
	"number": "12",
	"address": "10667臺北市大安區復興南路2段235號",
	"latitude": 25.0261364,
	"longitude": 121.5436451
}, {
	"id": 7,
	"name": "六張犁",
	"number": "13",
	"address": "10674臺北市大安區和平東路3段168號",
	"latitude": 25.02379,
	"longitude": 121.553126
}, {
	"id": 8,
	"name": "麟光",
	"number": "14",
	"address": "10676臺北市大安區和平東路3段410號",
	"latitude": 25.0184814,
	"longitude": 121.5587292
}, {
	"id": 9,
	"name": "辛亥",
	"number": "15",
	"address": "11694臺北市文山區辛亥路4段128號",
	"latitude": 25.005483,
	"longitude": 121.557102
}, {
	"id": 10,
	"name": "萬芳醫院",
	"number": "16",
	"address": "11696臺北市文山區興隆路3段113號",
	"latitude": 24.999335,
	"longitude": 121.558095
}, {
	"id": 11,
	"name": "萬芳社區",
	"number": "17",
	"address": "11653臺北市文山區萬芳路60號",
	"latitude": 24.99784,
	"longitude": 121.570705
}, {
	"id": 12,
	"name": "木柵",
	"number": "18",
	"address": "11656臺北市文山區木柵路4段135號",
	"latitude": 24.998231,
	"longitude": 121.573147
}, {
	"id": 13,
	"name": "動物園",
	"number": "19",
	"address": "11656臺北市文山區新光路2段32號",
	"latitude": 24.9981707,
	"longitude": 121.5794981
}, {
	"id": 14,
	"name": "大直",
	"number": "21",
	"address": "10465臺北市中山區北安路534之1號",
	"latitude": 25.0796581,
	"longitude": 121.5468824
}, {
	"id": 15,
	"name": "劍南路",
	"number": "22",
	"address": "10464臺北市中山區北安路798號",
	"latitude": 25.0847974,
	"longitude": 121.5550972
}, {
	"id": 16,
	"name": "西湖",
	"number": "23",
	"address": "11493臺北市內湖區內湖路1段256號",
	"latitude": 25.081906,
	"longitude": 121.567597
}, {
	"id": 17,
	"name": "港墘",
	"number": "24",
	"address": "11446臺北市內湖區內湖路1段663號",
	"latitude": 25.0801772,
	"longitude": 121.5756865
}, {
	"id": 18,
	"name": "文德",
	"number": "25",
	"address": "11475臺北市內湖區文德路214號",
	"latitude": 25.078436,
	"longitude": 121.585527
}, {
	"id": 19,
	"name": "內湖",
	"number": "26",
	"address": "11489臺北市內湖區成功路4段186號",
	"latitude": 25.0838996,
	"longitude": 121.5942576
}, {
	"id": 20,
	"name": "大湖公園",
	"number": "27",
	"address": "11477臺北市內湖區成功路5段11號",
	"latitude": 25.083351,
	"longitude": 121.602658
}, {
	"id": 21,
	"name": "葫洲",
	"number": "28",
	"address": "11486臺北市內湖區康寧路3段16號",
	"latitude": 25.0724829,
	"longitude": 121.607389
}, {
	"id": 22,
	"name": "東湖",
	"number": "29",
	"address": "11486臺北市內湖區康寧路3段235號",
	"latitude": 25.0671258,
	"longitude": 121.6117183
}, {
	"id": 23,
	"name": "南港軟體園區",
	"number": "30",
	"address": "11568臺北市南港區經貿二路183號",
	"latitude": 25.0599419,
	"longitude": 121.6159291
}, {
	"id": 24,
	"name": "南港展覽館",
	"number": "31",
	"address": "11568臺北市南港區南港路1段32號板南線",
	"latitude": 25.055288,
	"longitude": 121.6175001
}, {
	"id": 25,
	"name": "小碧潭",
	"number": "32",
	"address": "23150新北市新店區中央路151號4樓",
	"latitude": 24.9720931,
	"longitude": 121.5295777
}, {
	"id": 26,
	"name": "新店",
	"number": "33",
	"address": "23152新北市新店區北宜路1段2號",
	"latitude": 24.9578719,
	"longitude": 121.537557
}, {
	"id": 27,
	"name": "新店區公所",
	"number": "34",
	"address": "23147新北市新店區北新路1段295號",
	"latitude": 24.967498,
	"longitude": 121.541192
}, {
	"id": 28,
	"name": "七張",
	"number": "35",
	"address": "23143新北市新店區北新路2段150號",
	"latitude": 24.9750109,
	"longitude": 121.54322
}, {
	"id": 29,
	"name": "大坪林",
	"number": "36",
	"address": "23143新北市新店區北新路3段190號",
	"latitude": 24.9831,
	"longitude": 121.5418
}, {
	"id": 30,
	"name": "景美",
	"number": "37",
	"address": "11674臺北市文山區羅斯福路6段393號",
	"latitude": 24.9921332,
	"longitude": 121.5405874
}, {
	"id": 31,
	"name": "萬隆",
	"number": "38",
	"address": "11678臺北市文山區羅斯福路5段214號",
	"latitude": 25.0025439,
	"longitude": 121.538552
}, {
	"id": 32,
	"name": "公館",
	"number": "39",
	"address": "10091臺北市中正區羅斯福路4段74號B1",
	"latitude": 25.014347,
	"longitude": 121.534412
}, {
	"id": 33,
	"name": "台電大樓",
	"number": "40",
	"address": "10089臺北市中正區羅斯福路3段126之5號B1",
	"latitude": 25.02072,
	"longitude": 121.527181
}, {
	"id": 34,
	"name": "古亭",
	"number": "41",
	"address": "10084臺北市中正區羅斯福路2段164之1號B1",
	"latitude": 25.025473,
	"longitude": 121.523279
}, {
	"id": 35,
	"name": "中正紀念堂",
	"number": "42",
	"address": "10074臺北市中正區羅斯福路1段8之1號B1",
	"latitude": 25.032406,
	"longitude": 121.518244
}, {
	"id": 36,
	"name": "小南門",
	"number": "43",
	"address": "10066臺北市中正區愛國西路22號B1",
	"latitude": 25.035277,
	"longitude": 121.510975
}, {
	"id": 37,
	"name": "頂溪",
	"number": "45",
	"address": "23445新北市永和區永和路2段168號B1",
	"latitude": 25.0128479,
	"longitude": 121.515416
}, {
	"id": 38,
	"name": "永安市場",
	"number": "46",
	"address": "23574新北市中和區中和路388號",
	"latitude": 25.0023175,
	"longitude": 121.5110392
}, {
	"id": 39,
	"name": "景安",
	"number": "47",
	"address": "23582新北市中和區景平路486號",
	"latitude": 24.9937133,
	"longitude": 121.5048071
}, {
	"id": 40,
	"name": "南勢角",
	"number": "48",
	"address": "23566新北市中和區捷運路6號",
	"latitude": 24.989714,
	"longitude": 121.509767
}, {
	"id": 41,
	"name": "台大醫院",
	"number": "50",
	"address": "10048臺北市中正區公園路52號B1",
	"latitude": 25.041003,
	"longitude": 121.515715
}, {
	"id": 42,
	"name": "台北車站",
	"number": "51",
	"address": "10041臺北市中正區忠孝西路1段49號",
	"latitude": 25.046374,
	"longitude": 121.517896
}, {
	"id": 43,
	"name": "中山",
	"number": "53",
	"address": "10444臺北市中山區南京西路16號",
	"latitude": 25.052433,
	"longitude": 121.520518
}, {
	"id": 44,
	"name": "雙連",
	"number": "54",
	"address": "10354臺北市大同區民生西路47號",
	"latitude": 25.057805,
	"longitude": 121.520627
}, {
	"id": 45,
	"name": "民權西路",
	"number": "55",
	"address": "10365臺北市大同區民權西路72號",
	"latitude": 25.062879,
	"longitude": 121.519346
}, {
	"id": 46,
	"name": "圓山",
	"number": "56",
	"address": "10367臺北市大同區酒泉街9之1號",
	"latitude": 25.0708435,
	"longitude": 121.5200551
}, {
	"id": 47,
	"name": "劍潭",
	"number": "57",
	"address": "11163臺北市士林區中山北路5段65號",
	"latitude": 25.084873,
	"longitude": 121.525078
}, {
	"id": 48,
	"name": "士林",
	"number": "58",
	"address": "11163臺北市士林區福德路1號",
	"latitude": 25.093535,
	"longitude": 121.52623
}, {
	"id": 49,
	"name": "芝山",
	"number": "59",
	"address": "11158臺北市士林區福國路70號",
	"latitude": 25.1030599,
	"longitude": 121.522514
}, {
	"id": 50,
	"name": "明德",
	"number": "60",
	"address": "11280臺北市北投區明德路95號",
	"latitude": 25.109721,
	"longitude": 121.518848
}, {
	"id": 51,
	"name": "石牌",
	"number": "61",
	"address": "11271臺北市北投區石牌路1段200號",
	"latitude": 25.115078,
	"longitude": 121.515136
}, {
	"id": 52,
	"name": "唭哩岸",
	"number": "62",
	"address": "11265臺北市北投區東華街2段301號",
	"latitude": 25.120872,
	"longitude": 121.506252
}, {
	"id": 53,
	"name": "奇岩",
	"number": "63",
	"address": "11270臺北市北投區三合街2段489號",
	"latitude": 25.126605,
	"longitude": 121.500889
}, {
	"id": 54,
	"name": "北投",
	"number": "64",
	"address": "11246臺北市北投區光明路1號",
	"latitude": 25.131977,
	"longitude": 121.498695
}, {
	"id": 55,
	"name": "新北投",
	"number": "65",
	"address": "11268臺北市北投區大業路700號",
	"latitude": 25.136947,
	"longitude": 121.502534
}, {
	"id": 56,
	"name": "復興崗",
	"number": "66",
	"address": "11257臺北市北投區中央北路3段53巷10號",
	"latitude": 25.137532,
	"longitude": 121.485424
}, {
	"id": 57,
	"name": "忠義",
	"number": "67",
	"address": "11257臺北市北投區中央北路4段301號",
	"latitude": 25.131058,
	"longitude": 121.473226
}, {
	"id": 58,
	"name": "關渡",
	"number": "68",
	"address": "11259臺北市北投區大度路3段296巷51號",
	"latitude": 25.12571,
	"longitude": 121.467188
}, {
	"id": 59,
	"name": "竹圍",
	"number": "69",
	"address": "25173新北市淡水區民權路50號",
	"latitude": 25.133598,
	"longitude": 121.4592857
}, {
	"id": 60,
	"name": "紅樹林",
	"number": "70",
	"address": "25173新北市淡水區中正東路2段68號",
	"latitude": 25.154693,
	"longitude": 121.458791
}, {
	"id": 61,
	"name": "淡水",
	"number": "71",
	"address": "25158新北市淡水區中正路1號",
	"latitude": 25.167935,
	"longitude": 121.445459
}, {
	"id": 62,
	"name": "永寧",
	"number": "77",
	"address": "23671新北市土城區中央路3段105號B1",
	"latitude": 24.9666709,
	"longitude": 121.4366324
}, {
	"id": 63,
	"name": "土城",
	"number": "78",
	"address": "23671新北市土城區金城路1段105號B1",
	"latitude": 24.973183,
	"longitude": 121.444156
}, {
	"id": 64,
	"name": "海山",
	"number": "79",
	"address": "23660新北市土城區樂利街16號B2",
	"latitude": 24.985339,
	"longitude": 121.448786
}, {
	"id": 65,
	"name": "亞東醫院",
	"number": "80",
	"address": "22060新北市板橋區南雅南路2段17號B1",
	"latitude": 24.9975632,
	"longitude": 121.4524089
}, {
	"id": 66,
	"name": "府中",
	"number": "81",
	"address": "22055新北市板橋區縣民大道1段193號B1",
	"latitude": 25.0085549,
	"longitude": 121.459183
}, {
	"id": 67,
	"name": "板橋",
	"number": "82",
	"address": "22041新北市板橋區站前路5號B1",
	"latitude": 25.014579,
	"longitude": 121.4625101
}, {
	"id": 68,
	"name": "新埔",
	"number": "83",
	"address": "22047新北市板橋區民生路3段2號B1",
	"latitude": 25.0242359,
	"longitude": 121.468174
}, {
	"id": 69,
	"name": "江子翠",
	"number": "84",
	"address": "22044新北市板橋區文化路2段296號B1",
	"latitude": 25.0296499,
	"longitude": 121.472311
}, {
	"id": 70,
	"name": "龍山寺",
	"number": "85",
	"address": "10855臺北市萬華區西園路1段153號",
	"latitude": 25.0354487,
	"longitude": 121.4995792
}, {
	"id": 71,
	"name": "西門",
	"number": "86",
	"address": "10042臺北市中正區寶慶路32之1號B1",
	"latitude": 25.0417459,
	"longitude": 121.508652
}, {
	"id": 72,
	"name": "善導寺",
	"number": "88",
	"address": "10049臺北市中正區忠孝東路1段58號B1",
	"latitude": 25.044746,
	"longitude": 121.523133
}, {
	"id": 73,
	"name": "忠孝新生",
	"number": "89",
	"address": "10652臺北市大安區新生南路1段67號",
	"latitude": 25.042023,
	"longitude": 121.533107
}, {
	"id": 74,
	"name": "忠孝敦化",
	"number": "91",
	"address": "10686臺北市大安區忠孝東路4段182號",
	"latitude": 25.0413218,
	"longitude": 121.5516538
}, {
	"id": 75,
	"name": "國父紀念館",
	"number": "92",
	"address": "11072臺北市信義區忠孝東路4段400號",
	"latitude": 25.0410987,
	"longitude": 121.5579885
}, {
	"id": 76,
	"name": "市政府",
	"number": "93",
	"address": "11071臺北市信義區忠孝東路5段2號",
	"latitude": 25.0409916,
	"longitude": 121.5651802
}, {
	"id": 77,
	"name": "永春",
	"number": "94",
	"address": "11061臺北市信義區忠孝東路5段455號",
	"latitude": 25.041007,
	"longitude": 121.576419
}, {
	"id": 78,
	"name": "後山埤",
	"number": "95",
	"address": "11575臺北市南港區忠孝東路6段2號",
	"latitude": 25.045248,
	"longitude": 121.582961
}, {
	"id": 79,
	"name": "昆陽",
	"number": "96",
	"address": "11558臺北市南港區忠孝東路6段451號",
	"latitude": 25.050461,
	"longitude": 121.593268
}, {
	"id": 80,
	"name": "南港",
	"number": "97",
	"address": "11561臺北市南港區忠孝東路7段380號",
	"latitude": 25.0518136,
	"longitude": 121.6060423
}, {
	"id": 81,
	"name": "輔大",
	"number": "121",
	"address": "24205新北市新莊區中正路510之1號B1",
	"latitude": 25.032913,
	"longitude": 121.4353916
}, {
	"id": 82,
	"name": "新莊",
	"number": "122",
	"address": "24243新北市新莊區中正路138號B1",
	"latitude": 25.0362597,
	"longitude": 121.4524832
}, {
	"id": 83,
	"name": "頭前庄",
	"number": "123",
	"address": "24251新北市新莊區思源路18號B1",
	"latitude": 25.0397749,
	"longitude": 121.4607719
}, {
	"id": 84,
	"name": "先嗇宮",
	"number": "124",
	"address": "24158新北市三重區重新路5段515號B1",
	"latitude": 25.0465799,
	"longitude": 121.47129
}, {
	"id": 85,
	"name": "三重",
	"number": "125",
	"address": "24161新北市三重區捷運路36號B1",
	"latitude": 25.0552855,
	"longitude": 121.4841009
}, {
	"id": 86,
	"name": "菜寮",
	"number": "126",
	"address": "24143新北市三重區重新路3段150號B1",
	"latitude": 25.0594539,
	"longitude": 121.491432
}, {
	"id": 87,
	"name": "台北橋",
	"number": "127",
	"address": "24142新北市三重區重新路1段108號B1",
	"latitude": 25.063048,
	"longitude": 121.500932
}, {
	"id": 88,
	"name": "大橋頭",
	"number": "128",
	"address": "10357臺北市大同區民權西路223號B1",
	"latitude": 25.0631761,
	"longitude": 121.5131091
}, {
	"id": 89,
	"name": "中山國小",
	"number": "130",
	"address": "10452臺北市中山區民權東路1段71號B1",
	"latitude": 25.0627591,
	"longitude": 121.5271317
}, {
	"id": 90,
	"name": "行天宮",
	"number": "131",
	"address": "10468臺北市中山區松江路316號B1",
	"latitude": 25.0604614,
	"longitude": 121.53282
}, {
	"id": 91,
	"name": "松江南京",
	"number": "132",
	"address": "10457臺北市中山區松江路126號B1",
	"latitude": 25.0526894,
	"longitude": 121.5327444
}, {
	"id": 92,
	"name": "蘆洲",
	"number": "174",
	"address": "24760新北市蘆洲區三民路386號B1",
	"latitude": 25.0915536,
	"longitude": 121.4644714
}, {
	"id": 93,
	"name": "三民高中",
	"number": "175",
	"address": "24760新北市蘆洲區三民路105號B1",
	"latitude": 25.0856209,
	"longitude": 121.4729395
}, {
	"id": 94,
	"name": "徐匯中學",
	"number": "176",
	"address": "24753新北市蘆洲區中山一路3號B1",
	"latitude": 25.08035,
	"longitude": 121.479925
}, {
	"id": 95,
	"name": "三和國中",
	"number": "177",
	"address": "24152新北市三重區三和路4段107號B1",
	"latitude": 25.077349,
	"longitude": 121.48749
}, {
	"id": 96,
	"name": "三重國小",
	"number": "178",
	"address": "24149新北市三重區三和路3段5號B1",
	"latitude": 25.0707449,
	"longitude": 121.4965
}];
