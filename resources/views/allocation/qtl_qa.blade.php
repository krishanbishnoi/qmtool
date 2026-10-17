@extends('layouts.app')

@section('sh-title')
QTL
@endsection

@section('sh-detail')
Manage Your Team
@endsection

@section('main')

<qtl-qa></qtl-qa>

@endsection
@section('js')
@include('shared.form_js')
@endsection