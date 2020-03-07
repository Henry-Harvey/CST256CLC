<!-- This layout is for users that are not logged in -->
<html>
<head>
	<title>@yield('title')</title>
	@include('layouts._bootstrap')
</head>

<body id="homeBody">
	@include('layouts._navbar')
	<div align="center" class="loginForm">
		@if(!Session::get('sp'))
		@yield('content')
		@else
		<h2>You must be logged out to view this page</h2>
		@endif
	</div>
	@include('layouts._footer')
</body>

</html>