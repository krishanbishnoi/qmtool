@extends('layouts.app')

@section('sh-title')
Update Password
@endsection

@section('sh-detail')
Update Password
@endsection

@section('main')

<div class="row">
    <div class="col-md-12">

        <div class="kt-portlet">

            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Update User Password
                    </h3>
                </div>
            </div>

            {!! Form::open([
                'route' => 'password_update',
                'method' => 'POST',
                'class' => 'kt-form'
            ]) !!}

            <div class="kt-portlet__body">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="row">

                    <div class="form-group col-md-6">
                        <label>Email <span class="text-danger">*</span></label>

                        <input
                            type="email"
                            class="form-control"
                            name="email"
                            value="{{ old('email') }}"
                            required>

                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>

                    <div class="form-group col-md-6">

                        <label>New Password <span class="text-danger">*</span></label>

                        <input
                            type="password"
                            class="form-control"
                            name="password"
                            required>

                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>

                </div>

            </div>

            <div class="kt-portlet__foot">
                <button type="submit" class="btn btn-primary">
                    Update Password
                </button>

                <button type="reset" class="btn btn-secondary">
                    Reset
                </button>
            </div>

            {!! Form::close() !!}

        </div>

    </div>
</div>

@endsection