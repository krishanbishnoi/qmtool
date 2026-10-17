@extends('layouts.app')

@section('sh-title')
    Schedule Training
@endsection

@section('sh-detail')
    Schedule the training
@endsection

@section('main')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Schedule Training
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">         
            <form action="/trainning_pkt/schedule_training_submit" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-lg-4">
                        <label>Select Partner QA</label>
                        <select class="form-control m-select2" id="selected_partner_qa_list" name="selected_partner_qa[]" multiple required>
                            <option value="">Select Partner QA</option>
                            @foreach ($assigned_partner_qa as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-4">
                        <label>Enter Time</label>
                        <input type="datetime-local" name="training_time" class="form-control" required>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-8">
                        <label>Additional Comments or Instructions</label>
                        <textarea name="comments" class="form-control" rows="6" placeholder="Add any comments or instructions here..."></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-4">
                        <label>Paste the Link Here</label>
                        <input type="text" name="submited_link" class="form-control" required>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-4">
                        <label>Attach Files (PPT, Excel)</label>
                        <input type="file" name="attachments[]" class="form-control" multiple >
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-4">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
