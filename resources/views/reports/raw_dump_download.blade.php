@extends('layouts.app')

@section('title', 'Download Processed Data')

@section('sh-title')
    Download Processed Data
@endsection

@section('main')

<div class="row">
    <div class="col-md-12">
        <div class="kt-portlet">

            <div class="kt-portlet__body">
                <span class="form-text">
                    Your data has been successfully processed.
                    Click the buttons below to download the processed data.
                </span>
            </div>

            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <div class="row">

                        @foreach ($fileNames as $fileName)

                            {{-- User 3560 can only see RCC files --}}
                            @if (auth()->user()->id === 3560 && !str_starts_with($fileName, 'RCC'))
                                @continue
                            @endif

                            @php
                                $filePath = '/' . $public_path . '/' . $fileName;
                            @endphp

                            <div class="col-md-3 col-sm-6 col-12 mb-3">
                                <a href="{{ $filePath }}"
                                   download="{{ $fileName }}"
                                   class="btn btn-primary btn-block">
                                    {{ $fileName }}
                                </a>
                            </div>

                        @endforeach

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection