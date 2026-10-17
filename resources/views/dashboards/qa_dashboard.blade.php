@extends('layouts.app')

@section('sh-title')
Dashboard
@endsection
@section('sh-detail')
Partner - Location - Process Wise
@endsection

@section('main')
	<qa-dashboard></qa-dashboard>
@endsection

@section('js')
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-daterangepicker.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/components/extended/blockui.js" type="text/javascript"></script>
@include('shared.table_js');
@endsection
@section('css')

<style type="text/css">
	.chart-container {
	width: 270px;
	float: left;
	height: 200px;
}
.highcharts-credits
{
	display: none !important;
}
</style>
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@include('shared.table_css')
@endsection