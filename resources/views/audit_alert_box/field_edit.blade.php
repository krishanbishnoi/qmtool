@extends('layouts.app')

@section('sh-title')
Audit Form Fields
@endsection

@section('sh-detail')
Edit
@endsection

@section('main')

<div class="kt-portlet">

```
<div class="kt-portlet__head">

    <div class="kt-portlet__head-label">

        <h3 class="kt-portlet__head-title">
            Edit Field
        </h3>

    </div>

</div>

{!! Form::open([
    'url' => 'update_field/' . Crypt::encrypt($field->id),
    'method' => 'POST',
    'class' => 'kt-form',
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
                    $process_list,
                    $field->process_id,
                    [
                        'class'=>'form-control',
                        'placeholder'=>'Select Process'
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
                    value="{{$field->field_name}}"
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
                >

                    <option
                        value="text"
                        {{$field->field_type=='text' ? 'selected' : ''}}
                    >
                        Text
                    </option>

                    <option
                        value="textarea"
                        {{$field->field_type=='textarea' ? 'selected' : ''}}
                    >
                        Textarea
                    </option>

                    <option
                        value="number"
                        {{$field->field_type=='number' ? 'selected' : ''}}
                    >
                        Number
                    </option>

                    <option
                        value="date"
                        {{$field->field_type=='date' ? 'selected' : ''}}
                    >
                        Date
                    </option>

                    <option
                        value="dropdown"
                        {{$field->field_type=='dropdown' ? 'selected' : ''}}
                    >
                        Dropdown
                    </option>

                </select>

            </div>

        </div>

        <div
            id="dropdown_section"
            style="{{$field->field_type=='dropdown' ? '' : 'display:none;'}}"
        >

            <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>

            <div id="kt_repeater_qm_sheet">

                <div class="form-group row">

                    <label class="col-lg-2 col-form-label">
                        Options:
                    </label>

                    <div
                        data-repeater-list="options"
                        class="col-lg-10"
                    >

                        @foreach($field->options as $option)

                        <div
                            data-repeater-item
                            class="form-group row align-items-center"
                        >

                            <input
                                type="hidden"
                                name="row_id"
                                value="{{$option->id}}"
                            >

                            <div class="col-md-7">

                                <div class="kt-form__group--inline">

                                    <div class="kt-form__label">
                                        <label>Option:</label>
                                    </div>

                                    <div class="kt-form__control">

                                        <input
                                            type="text"
                                            class="form-control"
                                            name="value"
                                            value="{{$option->option_value}}"
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
                                        <span>Delete</span>
                                    </span>

                                </div>

                            </div>

                        </div>

                        @endforeach

                        <div
                            data-repeater-item
                            class="form-group row align-items-center"
                        >

                            <input
                                type="hidden"
                                name="row_id"
                            >

                            <div class="col-md-7">

                                <div class="kt-form__group--inline">

                                    <div class="kt-form__label">
                                        <label>Option:</label>
                                    </div>

                                    <div class="kt-form__control">

                                        <input
                                            type="text"
                                            class="form-control"
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
                                        <span>Delete</span>
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
                                <span>Add Option</span>
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
            class="btn btn-success"
        >
            Update
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

    $('#kt_repeater_qm_sheet').repeater({

        initEmpty:false,

        show:function(){
            $(this).slideDown();
        },

        hide:function(deleteElement){

            if(confirm('Delete this option ?'))
            {
                $(this).slideUp(deleteElement);
            }

        }

    });

});

</script>

@endsection
