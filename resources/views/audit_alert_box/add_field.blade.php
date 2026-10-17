@extends('layouts.app')

@section('sh-title')
Audit Form Field Master
@endsection

@section('sh-detail')
Create New
@endsection

@section('main')

<div class="row">
    <div class="col-md-8">
        <div class="kt-portlet">

```
        {!! Form::open([
            'route' => 'save_field',
            'class' => 'kt-form',
            'role' => 'form',
            'data-toggle' => 'validator',
        ]) !!}

        <div class="kt-portlet__body">

            <div class="kt-form__section kt-form__section--first">

                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">
                        Process:
                    </label>

                    <div class="col-lg-4">
                        {{ Form::select(
                            'process_id',
                            $process,
                            null,
                            [
                                'class' => 'form-control',
                                'placeholder' => 'Select Process'
                            ]
                        ) }}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">
                        Field Name:
                    </label>

                    <div class="col-lg-4">
                        <input
                            type="text"
                            name="field_name"
                            class="form-control"
                            required
                        >
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">
                        Field Type:
                    </label>

                    <div class="col-lg-4">
                        <select
                            name="field_type"
                            id="field_type"
                            class="form-control"
                            required
                        >
                            <option value="">
                                Select Type
                            </option>

                            <option value="text">
                                Text
                            </option>

                            <option value="textarea">
                                Textarea
                            </option>

                            <option value="number">
                                Number
                            </option>

                            <option value="date">
                                Date
                            </option>

                            <option value="dropdown">
                                Dropdown
                            </option>
                        </select>
                    </div>
                </div>

                <div
                    id="dropdown_section"
                    style="display:none;"
                >

                    <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>

                    <div id="kt_repeater_1">

                        <div class="form-group row">

                            <label class="col-lg-2 col-form-label">
                                Dropdown Options:
                            </label>

                            <div
                                data-repeater-list="options"
                                class="col-lg-10"
                            >

                                <div
                                    data-repeater-item
                                    class="form-group row align-items-center"
                                >

                                    <div class="col-md-7">

                                        <div class="kt-form__group--inline">

                                            <div class="kt-form__label">
                                                <label>
                                                    Option:
                                                </label>
                                            </div>

                                            <div class="kt-form__control">

                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    placeholder="Enter option"
                                                    name="value"
                                                >

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div
                                            data-repeater-delete
                                            class="btn-sm btn btn-danger btn-pill"
                                        >
                                            <span>
                                                <i class="la la-trash-o"></i>
                                                <span>
                                                    Delete
                                                </span>
                                            </span>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-lg-2 col-form-label"></label>

                            <div class="col-lg-4">

                                <div
                                    data-repeater-create
                                    class="btn btn-sm btn-brand btn-pill"
                                >
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>
                                            Add Option
                                        </span>
                                    </span>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="kt-portlet__foot">

            <div class="kt-form__actions">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Submit
                </button>

                <button
                    type="reset"
                    class="btn btn-secondary"
                >
                    Cancel
                </button>

            </div>

        </div>

        {!! Form::close() !!}

    </div>
</div>
```

</div>

@endsection

@section('js')

@include('shared.form_js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>

<script>

$(document).ready(function(){

    $('#field_type').change(function(){

        if($(this).val() == 'dropdown')
        {
            $('#dropdown_section').show();
        }
        else
        {
            $('#dropdown_section').hide();
        }

    });

    $('#kt_repeater_1').repeater({

        initEmpty: false,

        show: function() {
            $(this).slideDown();
        },

        hide: function(deleteElement) {

            if(confirm('Delete this option ?'))
            {
                $(this).slideUp(deleteElement);
            }

        }

    });

});

</script>

@endsection
