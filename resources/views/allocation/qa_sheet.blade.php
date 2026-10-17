@extends('layouts.app')

@section('sh-title')
QM Sheet Assignment
@endsection

@section('sh-detail')
To QA
@endsection

@section('main')

<qa-sheet></qa-sheet>

@endsection
@section('js')
@include('shared.form_js')
@endsection