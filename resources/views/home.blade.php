@extends('layouts.app')

@section('main')
	@if(Auth::user()->hasRole('client') || Auth::user()->id == 42 || Auth::user()->id == 172 || Auth::user()->id == 198)
		@section('sh-title')
		Dashboard
		@endsection
		@section('sh-detail')
		Overall numbers including partner and process
		@endsection

		@section('sh-toolbar')
			<div class="kt-subheader__toolbar">
				<div class="kt-subheader__wrapper">

				<a href="/test_html_new_get" class="btn btn-label-brand btn-bold">
					Detail Dashboard
				</a>
				
				</div>
			</div> 
		@endsection
		@include('dashboards.client_dashboard')

	@elseif(Auth::user()->hasRole('qa'))
		@section('sh-title')
		Dashboard
		@endsection
		@section('sh-detail')
		Informations
		@endsection
		@include('dashboards.qa_dashboard')
	
	@elseif(Auth::user()->hasRole('qtl'))
		@section('sh-title')
		Dashboard
		@endsection
		@section('sh-detail')
		Informations
		@endsection
		@include('dashboards.qtl_dashboard')
		
	@elseif(Auth::user()->hasRole('partner-training-head')||
			Auth::user()->hasRole('partner-operation-head')||
			Auth::user()->hasRole('partner-quality-head')||
			Auth::user()->hasRole('partner-admin')||
			Auth::user()->hasRole('partner-qa'))
			@section('sh-title')
			Dashboard
			@endsection
			@section('sh-detail')
			Informations
			@endsection
			@include('dashboards.client_partner_wise_dashboard')
	@endif

@endsection

@section('js')
@endsection

@section('css')

<style type="text/css">
	.chart-container {
	width: 270px;
	float: left;
	height: 200px;
}
</style>
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
<link href="/assets/app/custom/wizard/wizard-v2.default.css" rel="stylesheet" type="text/css" />
@endsection