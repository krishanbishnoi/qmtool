<!DOCTYPE html>

<html lang="en">

<!-- begin::Head -->
<head>
	<meta charset="utf-8" />
	<title>QM Tool</title>
	<meta name="description" content="QuoteNow">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!--begin::Fonts -->
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
	<script>
		WebFont.load({
			google: {
				"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
			},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script> -->


	<!--end::Fonts -->
	@yield('css')
	<!--begin::Page Vendors Styles(used by this page) -->
	{!! Html::style('assets/vendors/custom/fullcalendar/fullcalendar.bundle.css') !!}

	<!--RTL version:{!! Html::style('assets/vendors/custom/fullcalendar/fullcalendar.bundle.rtl.css') !!}-->

	<!--end::Page Vendors Styles -->

	<!--begin::Global Theme Styles(used by all pages) -->
	{!! Html::style('assets/vendors/base/vendors.bundle.css') !!}

	<!--RTL version:{!! Html::style('assets/vendors/base/vendors.bundle.rtl.css') !!}-->
	{!! Html::style('assets/demo/default/base/style.bundle.css') !!}

	<!--RTL version:{!! Html::style('assets/demo/default/base/style.bundle.rtl.css') !!}-->

	<!--end::Global Theme Styles -->

	<!--begin::Layout Skins(used by all pages) -->
	{!! Html::style('assets/demo/default/skins/header/base/light.css') !!}

	<!--RTL version:{!! Html::style('assets/demo/default/skins/header/base/light.rtl.css') !!}-->
	{!! Html::style('assets/demo/default/skins/header/menu/light.css') !!}

	<!--RTL version:{!! Html::style('assets/demo/default/skins/header/menu/light.rtl.css') !!}-->
	{!! Html::style('assets/demo/default/skins/brand/dark.css') !!}

	<!--RTL version:{!! Html::style('assets/demo/default/skins/brand/dark.rtl.css') !!}-->
	{!! Html::style('assets/demo/default/skins/aside/dark.css') !!}

	<!--RTL version:{!! Html::style('assets/demo/default/skins/aside/dark.rtl.css') !!}-->

	<!--end::Layout Skins -->
	<link rel="shortcut icon" href="{{url('assets/media/logos/logo_default_dark.png')}}" />
</head>

<!-- end::Head -->

<!-- begin::Body -->
<body class="kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">
	<div id="app">
	<!-- begin:: Page -->

	<!-- begin:: Header Mobile -->
	<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
		<div class="kt-header-mobile__logo">
			<a href="index.html">
				<img alt="Logo" src="{{url('assets/media/logos/logo-light.png')}}" />
			</a>
		</div>
		<div class="kt-header-mobile__toolbar">
			<button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
			<!-- <button class="kt-header-mobile__toggler" id="kt_header_mobile_toggler"><span></span></button> -->
			<button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
		</div>
	</div>

	<!-- end:: Header Mobile -->
	<div class="kt-grid kt-grid--hor kt-grid--root">
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

			
			
			{{-- <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper"> --}}

			

				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

					{{-- @include('shared.sub_header') --}}
					
					<!-- begin:: Content -->
					<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

						@if (count($errors)>0)
						<div class="alert alert-light alert-elevate" role="alert">
							<div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
							<div class="alert-text">
								<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">Error!</span><br/>
								<ul>
									@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						</div>
						@endif
						@if(Session::get('success'))
						<div class="alert alert-light alert-elevate" role="alert">
							<div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
							<div class="alert-text">
								<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">Success!</span>
								{{session('success')}}
							</div>
						</div>
						@endif
						
						@if(Session::get('warning'))
						<div class="alert alert-light alert-elevate" role="alert">
							<div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
							<div class="alert-text">
								<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Warning!</span>
								{{session('warning')}}
							</div>
						</div>
						@endif

						@if(Session::get('warning'))
						<div class="alert alert-light alert-elevate" role="alert">
							<div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
							<div class="alert-text">
								<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Warning!</span>
								<pre>{{session('warning')}}</pre>
								
							</div>
						</div>
						@endif

						<div class="alert alert-light alert-elevate" role="alert" id = "process_warning" style = "display:none">
							<div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
							<div class="alert-text">
								<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Warning!</span>
								Please Select Process from the dropdown list
							</div>
						</div>
						@yield('main')
						
					</div>

					<!-- end:: Content -->
				</div>

			
			{{-- </div> --}}
	<!-- end:: vue app div -->
	</div>
    	@include('shared.footer')
	<!-- end:: vue app div -->
	<!-- end::Demo Panel -->
	</div>
</div>



	<!-- begin::Global Config(global config for global JS sciprts) -->
	<script type="text/javascript" src="{{url('js/app.js')}}"></script>

	<script>
		
		
		var KTAppOptions = {
			"colors": {
				"state": {
					"brand": "#5d78ff",
					"dark": "#282a3c",
					"light": "#ffffff",
					"primary": "#5867dd",
					"success": "#34bfa3",
					"info": "#36a3f7",
					"warning": "#ffb822",
					"danger": "#fd3995"
				},
				"base": {
					"label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
					"shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
				}
			}
		};
	</script>

	<!-- end::Global Config -->

	<!--begin::Global Theme Bundle(used by all pages) -->
	{!! Html::script('assets/vendors/base/vendors.bundle.js')!!}
	{!! Html::script('assets/demo/default/base/scripts.bundle.js')!!}

	<!--end::Global Theme Bundle -->

	<!--begin::Page Vendors(used by this page) -->
	{!! Html::script('assets/vendors/custom/fullcalendar/fullcalendar.bundle.js')!!}
	<!--end::Page Vendors -->

	<!--begin::Page Scripts(used by this page) -->
	{!! Html::script('assets/app/custom/general/dashboard.js')!!}
	<!--end::Page Scripts -->
	{!! Html::script('assets/app/bundle/app.bundle.js')!!}
	{!! Html::script('assets/app/custom/general/my-script.js')!!}
	{!! Html::script('assets/app/custom/general/components/extended/sweetalert2.js')!!}
	@yield('js')

	<!--begin::Global App Bundle(used by all pages) -->
	
	

	<!--end::Global App Bundle -->
	<script type="text/javascript">
		function delete_confirm() {
			var x = confirm("Are you sure you want to delete?");
			if (x) {
				return true;
			}
			else {
				event.preventDefault();
				return false;
			}
		}
	</script>
	
</body>

<!-- end::Body -->
</html>