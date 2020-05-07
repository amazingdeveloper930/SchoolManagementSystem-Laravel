<!--GLOBAL MANDATORY VENDORS-->
	<script src="{{asset('metronic/vendors/jquery/dist/jquery.js')}}" type="text/javascript"></script>
	<script src="{{asset('metronic/vendors/popper.js/dist/umd/popper.js')}}" type="text/javascript"></script>
	<script src="{{asset('metronic/vendors/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
<!--FIN GLOBAL MANDATORY VENDORS-->

	<script src="{{('metronic/assets3/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>

<!--GLOBAL THEME BUNDLE-->
	<script src="{{asset('metronic/assets/demo/base/scripts.bundle.js')}}" type="text/javascript"></script>
<!--FIN GLOBAL THEME BUNDLE-->

<!--GRAFICAS-->

	<script src="{{asset('metronic/assets/vendors/custom/flot/flot.bundle.js')}}" type="text/javascript"></script>

	<!--<script src="../../assets/demo/default/custom/components/charts/flotcharts.js" type="text/javascript"></script>-->


<!--Script Laravel-->
	<script src="{{ asset('js/app.js') }}"></script>

<!--PAGE LOADER-->
	<script>
		$(window).on('load', function() {
			$('body').removeClass('m-page--loading');
		});
	</script>
<!--FIN PAGE LOADER-->