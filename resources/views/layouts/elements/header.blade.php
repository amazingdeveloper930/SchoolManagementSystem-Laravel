<header id="m_header" class="m-grid__item    m-header " m-minimize="minimize" m-minimize-mobile="minimize" m-minimize-offset="200" m-minimize-mobile-offset="200">
	<div class="">
		<div class="m-stack m-stack--ver m-stack--desktop  m-header__wrapper">

			<!-- BEGIN: Brand -->
			<div class="m-stack__item m-brand m-brand--mobile">
				<div class="m-stack m-stack--ver m-stack--general">
					<div class="m-stack__item m-stack__item--middle m-brand__logo">
						<a href="{{ route('balance.index') }}" class="m-brand__logo-wrapper">
							<img style="max-width: 100px;" alt="" src="{{ asset('app/config/logo.jpg') }}" />
						</a>

					</div>
					<div class="m-stack__item m-stack__item--middle m-brand__tools">

						<!-- BEGIN: Responsive Aside Left Menu Toggler -->
						<!--<a href="javascript:;" id="m_aside_left_toggle_mobile" class="m-brand__icon m-brand__toggler m-brand__toggler--left">
							<span></span>
						</a>-->

						<!-- END -->

						<!-- BEGIN: Responsive Header Menu Toggler -->
						<a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler">
							<span></span>
						</a>

						<!-- END -->

						<!-- BEGIN: Topbar Toggler -->
						<a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon">
							<i class="flaticon-more"></i>
						</a>

						<!-- BEGIN: Topbar Toggler -->
					</div>
				</div>
			</div>


			<!-- END: Brand -->
			<div class="m-stack__item m-stack__item--middle m-stack__item--left m-header-head" id="m_header_nav">

				<div class="m-stack m-stack--ver m-stack--desktop">							

					<div class="m-stack__item m-stack__item--fluid">

						<!-- BEGIN: Horizontal Menu -->
						
						<div id="m_header_menu"  class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark">

							<ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
								
								<div style="width:150px; padding: 5px 0 0 0; display: inline-block;" m-menu-submenu-toggle="click" aria-haspopup="true">
									<a href="{{ route('balance.index') }}">
										<img src="{{ asset('app/images-email/logo.jpg') }}" style="width: 100%; height: auto;">
									</a>
								</div>

								@can('students.index')
									<li class="m-menu__item  m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" aria-haspopup="true"><a href="{{route('student.index')}}" class="m-menu__link"><span class="m-menu__item-here"></span><i class="fa fa-child m-menu__link-icon"></i><span style="padding: 0" class="m-menu__link-text">Estudiantes</span></a>
									</li>
								@endcan
								
								@can('payments.index')
								   <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('payment.index')}}" class="m-menu__link" title="Non functional dummy link"><span
										 class="m-menu__item-here"></span><i class="m-menu__link-icon fa fa-dollar-sign"></i><span class="m-menu__link-text">Pagos</span></a>
									</li> 
								@endcan

								@can('costs.index')
								    <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" m-menu-link-redirect="1" aria-haspopup="true"><a href="javascript:;" class="m-menu__link m-menu__toggle" title="Non functional dummy link"><span
											 class="m-menu__item-here"></span><i class="m-menu__link-icon fa fa-money-check"></i><span class="m-menu__link-text">Costos</span><i class="m-menu__hor-arrow la la-angle-down"></i><i class="m-menu__ver-arrow la la-angle-right"></i></a>
										<div class="m-menu__submenu  m-menu__submenu--fixed m-menu__submenu--left" style="width:600px"><span class="m-menu__arrow m-menu__arrow--adjust"></span>
											<div class="m-menu__subnav">
												<ul class="m-menu__content">
													<li class="m-menu__item">
														
														<ul class="m-menu__inner">
															<li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('annuity.index')}}" class="m-menu__link "><span class="m-menu__link-text">Anualidad</span></a></li>
															<li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('enrollment.index')}}" class="m-menu__link "><span class="m-menu__link-text">Matricula</span></a></li>
															<li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('service.index')}}" class="m-menu__link "><span class="m-menu__link-text">Servicios</span></a></li>
														</ul>
													</li>
													<li class="m-menu__item">
													</li>
												</ul>
											</div>
										</div>
									</li>
								@endcan

								@can('reports.index')
								    <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('reports.index')}}" class="m-menu__link" title="Non functional dummy link"><span
										 class="m-menu__item-here"></span><i class="m-menu__link-icon fa 	fa-chart-line"></i><span class="m-menu__link-text">Reportes</span></a>
									</li>
								@endcan
								
								@hasrole('super_admin')
								    <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" m-menu-link-redirect="1" aria-haspopup="true"><a href="{{route('users')}}" class="m-menu__link" title="Non functional dummy link"><span
										 class="m-menu__item-here"></span><i class="m-menu__link-icon fa fa-users"></i><span class="m-menu__link-text">Usuarios</span></a>
									</li>
								@endhasrole
								
							</ul>
						</div>

						<!-- END: Horizontal Menu -->
					</div>
				</div>
			</div>
			<div class="m-stack__item m-stack__item--middle m-stack__item--center">

				<!-- BEGIN: Brand -->
				<a href="index.html" class="m-brand m-brand--desktop">
					<img alt="" src="assets/demo/media/img/logo/logo.png" />
				</a>

				<!-- END: Brand -->
			</div>
			<div class="m-stack__item m-stack__item--right">

				<!-- BEGIN: Topbar -->
				<div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
					<div class="m-stack__item m-topbar__nav-wrapper">
						<ul class="m-topbar__nav m-nav m-nav--inline">								

							<li class="m-nav__item m-dropdown m-dropdown--medium m-dropdown--arrow  m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" m-dropdown-toggle="click">
								<a href="#" class="m-nav__link m-dropdown__toggle">
									<span class="m-topbar__username m--hidden-mobile">{{ Auth::user()->name }}</span>
								</a>

								<div class="m-dropdown__wrapper">
									<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
									<div class="m-dropdown__inner">
										<div class="m-dropdown__header m--align-center">
											<div class="m-card-user m-card-user--skin-light">
												<div class="m-card-user__pic">
													<img src="assets/app/media/img/users/user4.jpg" class="m--img-rounded m--marginless" alt="" />
												</div>
												<div class="m-card-user__details">
													<span class="m-card-user__name m--font-weight-500">{{ Auth::user()->name }}</span>
													<a href="" class="m-card-user__email m--font-weight-300 m-link">{{ Auth::user()->email }}</a>
												</div>
											</div>
										</div>

										<div class="m-dropdown__body">
											<div class="m-dropdown__content">
												<ul class="m-nav m-nav--skin-light">
													<li class="m-nav__section m--hide">
														<span class="m-nav__section-text">Section</span>
													</li>
													<li class="m-nav__item">
														{{-- <a href="profile.html" class="m-nav__link">
															<i class="m-nav__link-icon flaticon-profile-1"></i>
															<span class="m-nav__link-title">
																<span class="m-nav__link-wrap">
																	<span class="m-nav__link-text">Mi perfil</span>
																</span>
															</span>
														</a> --}}
													</li>
													<li class="m-nav__item">
														<a style="color: rgb(25, 59, 100) !important;" href="{{ route('logout') }}" class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder"
														onclick="event.preventDefault();
                                                     	document.getElementById('logout-form').submit();">
														Cerrar Sesión</a>
														<br>
														<a style="color: rgb(25, 59, 100) !important;" href="{{ route('config.index') }}" class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
															Configuración
														</a>

														<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
					                                        @csrf
					                                    </form>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</li>

							<li id="m_quick_sidebar_toggle" class="m-nav__item m-nav__item--info m-nav__item--qs">
								<a href="#" class="m-nav__link m-dropdown__toggle">
								</a>
							</li>

						</ul>
					</div>
				</div>

				<!-- END: Topbar -->
			</div>
		</div>
	</div>
</header>