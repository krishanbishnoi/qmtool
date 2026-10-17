@extends('layouts.app')

@section('sh-title')

@endsection

@section('sh-detail')

@endsection

@section('main')

<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head kt-portlet__head--lg">
		<div class="kt-portlet__head-label">
			<span class="kt-portlet__head-icon">
				<i class="kt-font-brand flaticon2-line-chart"></i>
			</span>
			<h1 class="kt-portlet__head-title">
				Error - Some thing went wrong!
			</h1>
		</div>
		
	</div>

    

	<div class="kt-portlet__body">

		

    <!--end: Datatable -->
</div>
</div>
@endsection
@section('css')
@include('shared.table_css')
@endsection
@section('js')

@include('shared.table_js')
@endsection