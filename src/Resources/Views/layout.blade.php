<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ $pageTitle ?? 'Entity Generator' }}</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<style>
		#content {
			margin-top: 20px;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<div class="container">
			<a class="navbar-brand" href="{{ url('/') }}">{{ env('APP_NAME', 'Entity Generator') }}</a>
		</div>
	</nav>
	<div class="container" id="content">
		@yield('header')
		@if (isset($errors) && count($errors) == 1)
			<div class="alert alert-danger">
				@foreach ($errors->all() as $error)
					{!! $error !!}
				@endforeach
			</div>
		@elseif(isset($errors) && count($errors) > 1)
			<div class="alert alert-danger">
				<p style="margin: 0;">
					@foreach ($errors->all() as $error)
						{!! $error !!}<br>
					@endforeach
				</p>
			</div>
		@endif
		@if(session('alert'))
			<div class="alert alert-{{ session('alert') }}">
				{!! session('message') !!}
			</div>
		@endif
		@yield('content')
	</div>
</body>
</html>