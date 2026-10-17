@extends('layouts.app')

@section('sh-title')
Compliace Report
@endsection

@section('sh-detail')
Agent Wise
@endsection

@section('main')
<client-agent-wise-report></client-agent-wise-report>
@endsection

@section('js')
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-daterangepicker.js" type="text/javascript"></script>
<script src="/assets/app/custom/general/components/extended/blockui.js" type="text/javascript"></script>
@include('shared.table_js')
@endsection
@section('css')
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@include('shared.table_css')
@endsection
