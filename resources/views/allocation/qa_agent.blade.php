@extends('layouts.app')

@section('sh-title')
QA - Agent Allocation
@endsection

@section('sh-detail')
With uploaded data
@endsection
@section('main')
<agent-allocation></agent-allocation>
@endsection
@section('js')
@include('shared.form_js')
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
@endsection