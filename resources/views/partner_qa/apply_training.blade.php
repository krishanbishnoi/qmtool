{{-- @extends('layouts.app')

@section('main')
@section('sh-title')
Apply Training
@endsection
	<h1>Apply Training</h1>

    <form>
        <input type="text" name="apply_traning" >
        <button type="Submit" class="btn btn-success">Submit</button>
        </form>
@endsection
 --}}




@extends('layouts.app')

@section('sh-title')
    Apply Training
@endsection

@section('sh-detail')
    Apply for the training
@endsection

@section('main')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Apply Training
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            {{-- @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif --}}
            {{-- {{ Form::open() }} --}}
            <form action="/apply_training_submit" method="post">
                @csrf
                <div class="form-group row">
                    <div class="col-lg-6">
                        <input type="text" name="apply_traning" placeholder="Describe Issue"> <br> <br>
                        <button type="Submit" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form>

            {{-- {{ Form::close() }} --}}
        </div>
    </div>
@endsection
