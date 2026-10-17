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
	<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
	<script>
		WebFont.load({
			google: {
				"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
			},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!--end::Fonts -->
	<style type="text/css">
		table tr td
		{
			text-align: justify;
		}
	</style>
	<!--begin::Page Vendors Styles(used by this page) -->
	
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

	<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
		@if($calibrator_data->status==1)
		<h6>You have already submited this calibration.</h6>
		@elseif($calibration_data->due_date < date('Y-m-d'))
		<h6>Oop's calibration expired.</h6>
		@else

		<div id="app">
		<div class="row">
			<div class="col-md-2">
				<img src="{{Storage::url('company/_'.$data->company_id.'/client/'.$data->client->logo)}}" width="80">
			</div>
			<div class="col-md-8">
				<h5 class="kt-font-brand kt-font-bolder" style="text-align: center; line-height: 60px;">{{$data->name}}</h5>
			</div>
			<div class="col-md-2" style="padding-top: 17px;">
				<img src="/img/logo.png" width="100" align="right">
			</div>
		</div>
		<br/>
		
		<calibration-sheet-component qm-sheet-id="{{$qm_sheet_id}}"
		       today-date="{{date('d-m-Y')}}"
		       auditor-name="{{Auth::user()->name}}"
		       auditor-id="{{Crypt::encrypt(Auth::user()->id)}}"
		       calibration-id="{{$calibration_data->id}}"
		       calibrator-id="{{$calibrator_data->id}}"></calibration-sheet-component>

		</div>

		@endif
	</div>
	
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

	<!--begin::Global Theme Bundle(used by all pages) -->
	{!! Html::script('assets/vendors/base/vendors.bundle.js')!!}
	{!! Html::script('assets/demo/default/base/scripts.bundle.js')!!}
	<!--end::Page Scripts -->
	{!! Html::script('assets/app/bundle/app.bundle.js')!!}
	<script type="text/javascript" src="/js/app.js"></script>
	
	
</body>

<!-- end::Body -->
</html>