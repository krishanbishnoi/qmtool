@extends('layouts.app')

@section('sh-title')
Dashboard
@endsection
@section('sh-detail')
Partner - Location - Process Wise
@endsection

@section('main')
	@if(Auth::user()->hasRole('client'))
		<client-partner-dashboard :show-qc-block="false"></client-partner-dashboard>
	@else
		<client-partner-dashboard :show-qc-block="false"></client-partner-dashboard>
	@endif
@endsection

@section('js')
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-daterangepicker.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/components/extended/blockui.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/canvas/html2canvas-master/dist/html2canvas.js"></script>

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
<script>
	function screenshot() {
	 	html2canvas($("#qrc_call_chart"), {
            onrendered: function(canvas) {
                theCanvas = canvas;
                document.body.appendChild(canvas);

                // Convert and download as image 
                Canvas2Image.saveAsPNG(canvas); 
                document.body.appendChild(canvas);
                // Clean up 
                //document.body.removeChild(canvas);
            }
        });
	    
	}
</script>
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@include('shared.table_css')
@endsection