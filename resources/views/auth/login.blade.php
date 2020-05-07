<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<!-- begin::Head -->
	<head>
	<!--CODIFICACION EN ESPAÑOL-->
	<meta charset="utf-8" />
	
	<!--TITULO-->
	<title>Iniciar Sesión</title>
	
	<!--META:DESCRIPTION PARA SEO-->
	<meta name="description" content="Latest updates and statistic charts">
	
	<!--CSRF-->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<!--META:VIEWPORT PARA RESPONSIVE DESIGN-->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

	<!--FUENTES -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>

		<script>
		WebFont.load({
        google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
        active: function() {
            sessionStorage.fonts = true;
        }
      });
    	</script>
	<!--FIN FUENTES -->

	<!--GLOBAL MANDATORY VENDORS-->
		<link href="{{asset('metronic/vendors/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css" />
	<!--FIN GLOBAL MANDATORY VENDORS-->
	
	<!--GLOBAL OPTIONAL VENDORS-->
		<link href="{{asset('metronic/vendors/vendors/line-awesome/css/line-awesome.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('metronic/vendors/vendors/flaticon/css/flaticon.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('metronic/vendors/vendors/metronic/css/styles.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('metronic/vendors/vendors/fontawesome5/css/all.min.css')}}" rel="stylesheet" type="text/css" />
	<!--FIN GLOBAL OPTIONAL VENDORS-->

	<!--GLOBAL THEME STYLES-->
		<link href="{{asset('metronic/assets/demo/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />
	<!--FIN GLOBAL THEME STYLES-->

	<!--PAGE VENDORS STYLES-->
		<link href="{{asset('metronic/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css" />
	<!--FIN PAGE VENDORS STYLES-->

	<!--ICONO-->
	<link rel="shortcut icon" href="assets/demo/media/img/logo/favicon.ico" />

	@stack('styles')<!--ESTILOS PERSONALIZADOS PARA CADA VISTA-->
</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-3" id="m_login" style="background-image: url(metronic/assets/app/media/img//bg/bg-3.jpg);">
				<div class="m-grid__item m-grid__item--fluid	m-login__wrapper">
					<div class="m-login__container">
						<div class="m-login__logo">
							<a href="#">
								<img src="{{ asset('app/images-email/logo.jpg') }}" style="width: 100%; height: auto;">
							</a>
						</div>
						<div class="m-login__signin">
							<div class="m-login__head">
								<h3 class="m-login__title" style="color: #193B64;">{{ __('Gestión ICA') }}</h3>
								@if(Session::has('error'))
								<div class="alert alert-danger text-center">
								    {{ Session::get('error') }}
								</div>
								@endif
							</div>
							<form class="m-login__form m-form" method="POST" action="{{ route('login') }}">
								@csrf
								<div class="form-group m-form__group">
									<input style="color: #91899f; background: #f7f6f9;" id="email" class="form-control m-input{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" placeholder="Email" name="email" autocomplete="off" value="{{ old('email') }}">

									@if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                	@endif

								</div>
								<div class="form-group m-form__group">
									<input style="color: #91899f; background: #f7f6f9;" id="password" class="form-control m-input m-login__form-input--last{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" placeholder="Contraseña" name="password" required>

									@if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                	@endif

								</div>
								<div class="row m-login__form-sub">
									<div class="col m--align-left m-login__form-left">
										<!--<input type="checkbox" name="remember" id="recordarme">
										<label for="recordarme">
											Recordarme
										</label>-->

										<label class="m-checkbox  m-checkbox--focus">
											<input type="checkbox" name="remember" id="recordarme"> Recordarme
											<span></span>
										</label>
									</div>
									{{-- <div class="col m--align-right m-login__form-right">
										<a href="javascript:;" id="m_login_forget_password" class="m-link">Forget Password ?</a>
									</div> --}}
								</div>
								<div class="m-login__form-action">
									<button id="" type="submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn" style="background-color: #193B64; border: 0px;">Iniciar Sesión</button>
								</div>
							</form>
						</div>
						<div class="m-login__signup">
							<div class="m-login__head">
								<h3 class="m-login__title">Sign Up</h3>
								<div class="m-login__desc">Enter your details to create your account:</div>
							</div>
							<form class="m-login__form m-form" action="">
								<div class="form-group m-form__group">
									<input class="form-control m-input" type="text" placeholder="Fullname" name="fullname">
								</div>
								<div class="form-group m-form__group">
									<input class="form-control m-input" type="text" placeholder="Email" name="email" autocomplete="off">
								</div>
								<div class="form-group m-form__group">
									<input class="form-control m-input" type="password" placeholder="Password" name="password">
								</div>
								<div class="form-group m-form__group">
									<input class="form-control m-input m-login__form-input--last" type="password" placeholder="Confirm Password" name="rpassword">
								</div>
								<div class="row form-group m-form__group m-login__form-sub">
									<div class="col m--align-left">
										<label class="m-checkbox m-checkbox--light">
											<input type="checkbox" name="agree">I Agree the <a href="#" class="m-link m-link--focus">terms and conditions</a>.
											<span></span>
										</label>
										<span class="m-form__help"></span>
									</div>
								</div>
								<div class="m-login__form-action">
									<button id="m_login_signup_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn">Sign Up</button>&nbsp;&nbsp;
									<button id="m_login_signup_cancel" class="btn btn-outline-focus m-btn m-btn--pill m-btn--custom m-login__btn">Cancel</button>
								</div>
							</form>
						</div>
						<div class="m-login__forget-password">
							<div class="m-login__head">
								<h3 class="m-login__title">Forgotten Password ?</h3>
								<div class="m-login__desc">Enter your email to reset your password:</div>
							</div>
							<form class="m-login__form m-form" action="">
								<div class="form-group m-form__group">
									<input class="form-control m-input" type="text" placeholder="Email" name="email" id="m_email" autocomplete="off">
								</div>
								<div class="m-login__form-action">
									<button id="m_login_forget_password_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">Request</button>&nbsp;&nbsp;
									<button id="m_login_forget_password_cancel" class="btn btn-outline-focus m-btn m-btn--pill m-btn--custom  m-login__btn">Cancel</button>
								</div>
							</form>
						</div>
						{{-- <div class="m-login__account">
							<span class="m-login__account-msg">
								Don't have an account yet ?
							</span>&nbsp;&nbsp;
							<a href="javascript:;" id="m_login_signup" class="m-link m-link--light m-login__account-link">Sign Up</a>
						</div> --}}
					</div>
				</div>
			</div>
		</div>

		<!-- end:: Page -->

		<!--GLOBAL MANDATORY VENDORS-->
		<script src="{{asset('metronic/vendors/jquery/dist/jquery.js')}}" type="text/javascript"></script>
		<script src="{{asset('metronic/vendors/popper.js/dist/umd/popper.js')}}" type="text/javascript"></script>
		<script src="{{asset('metronic/vendors/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
		<!--FIN GLOBAL MANDATORY VENDORS-->

		<!--GLOBAL THEME BUNDLE-->
			<script src="{{asset('metronic/assets/demo/base/scripts.bundle.js')}}" type="text/javascript"></script>
		<!--FIN GLOBAL THEME BUNDLE-->
		<!--Script Laravel-->
			<script src="{{ asset('js/app.js') }}"></script>
		<!--PAGE LOADER-->
			<script>
				$(window).on('load', function() {
					$('body').removeClass('m-page--loading');
				});
			</script>
		<!--FIN PAGE LOADER-->


		@yield('scripts')<!--SCRIPTS PERSONALIZADOS PARA CADA VISTA-->

		<!--end:: Global Optional Vendors -->

		<!--begin::Global Theme Bundle -->
		<script src="{{asset('metronic/assets/demo/base/scripts.bundle.js')}}" type="text/javascript"></script>

		<!--end::Global Theme Bundle -->

		<!--begin::Page Scripts -->
		<script src="{{asset('metronic/assets/snippets/custom/pages/user/login.js')}}" type="text/javascript"></script>

		<!--end::Page Scripts -->
	</body>

	<!-- end::Body -->
</html>