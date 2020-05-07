<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<!--CODIFICACION EN ESPAÑOL==================================================-->
	<meta charset="utf-8" />
	
	<!--TITULO ==================================================================-->
	<title>@yield('title')</title>

	<!--CSRF ====================================================================-->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<!--META:DESCRIPTION PARA SEO ===============================================-->
	<meta name="description" content="Latest updates and statistic charts">
	
	<!--META:VIEWPORT PARA RESPONSIVE DESIGN ====================================-->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

	

	<!--FUENTES =================================================================-->
	@include('layouts.elements.fonts')
	

	<!--HOJAS DE ESTILO PLANTILLA METRONIC ======================================-->
	@include('layouts.elements.styles_metronic')
	<!-- FIN HOJAS DE ESTILO PLANTILLA METRONIC-->
	


	<!--ICONO DE PAGINA ============================================================-->
	<link rel="shortcut icon" href="assets/demo/media/img/logo/favicon.ico" />
	
	<!--ESTILOS PERSONALIZADOS PARA CADA VISTA =====================================-->
	@stack('styles')
</head>




<body class="m--skin- m-page--loading-enabled m-page--loading m-content--skin-light m-header--fixed m-header--fixed-mobile m-aside-left--offcanvas-default m-aside-left--enabled m-aside-left--fixed m-aside-left--skin-dark m-aside--offcanvas-default">

	<!-- CARGADOR DE PAGINA -->
	@include('layouts.elements.loading')

	

	<!-- begin:: Page -->
	<div class="m-grid m-grid--hor m-grid--root m-page">

		<!-- HEADER DE PAGINA -->
		@include('layouts.elements.header')

		 
		<!-- CUERPO DE LA PAGINA -->
		<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor-desktop m-grid--desktop m-body">
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-container--responsive m-container--xxl m-container--full-height">
				<div class="m-grid__item m-grid__item--fluid m-wrapper container-fluid" id="app">
					@yield('content')<!--AQUI VA EL CONTENIDO-->
				</div>
			</div>
		</div>
	</div>


	
	<!--SCRIPTS METRONIC ================================================================== -->
	@include('layouts.elements.scripts_metronic')

	
	<!--SCRIPTS PERSONALIZADOS PARA CADA VISTA-->
	@yield('scripts')

</body>
</html>