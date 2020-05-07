<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>@yield('title')</title>
	
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	<style type="text/css">
		
	</style>
</head>
<body>
	
	<div class="container">
		<div class="row">
			<div class="col-xs-3 col-md-3">
				{{-- <img src="{{ public_path() . '/app/images-email/logo.jpg' }}" style="width: 100%"> --}}
				<img src="{{ asset('app/images-email/logo.jpg') }}" style="width: 100%">
			</div>

			<div class="col-xs-4 col-md-4">
				<h4 style="text-align: center;">@yield('title') <br> @yield('subtitle')</h4>
			</div>

			<div class="col-xs-5 col-md-5">
				<h5 style="">Usuario: {{Auth::user()->name}} <br> Fecha: {{ Carbon\Carbon::now()->format('d/m/Y H:i')}}</h5>
			</div>
		</div>

		<br>

		@yield('content')
	</div>

</body>
</html>