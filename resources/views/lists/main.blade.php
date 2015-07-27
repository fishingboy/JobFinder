<!DOCTYPE html>
<html lang="zh-TW">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="Content-Language" content="zh-TW" />
	<meta name="description" content="JobFinder">
	<meta name="keywords" content="JobFinder">
	<meta property="og:site_name" content="JobFinder" />
	<meta property="og:title" content="JobFinder" />
	<meta property="og:type" content="" />
	<meta property="og:description" content="JobFinder" />
	<meta property="og:url" content="" />
	<meta property="og:image" content="" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="renderer" content="webkit|ie-comp|ie-stand" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />

	<title>JobFinder</title>

	<link rel="shortcut icon" href="../img/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="" />
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('/compile/css/3rd-party/font-awesome.min.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('/compile/css/3rd-party/jquery-ui-1.10.4.custom.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('/compile/css/common.css') }}" />

	<!--[if lt IE 9]>
	<link rel="stylesheet" type="text/css" href="../compile/css/ie8.css">
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<script src="../js/3rd-party/respond.js"></script>
	<![endif]-->
</head>

<body>
	<header class="header">
		<h1 class="title">JobFinder</h1>
	</header>
	@yield('content')
	<script src="{{ URL::asset('js/jquery-2.1.4.min.js') }}" charset="utf-8"></script>
	@include('lists/common_js')
	@section('customJs')
	@show
</body>

</html>
