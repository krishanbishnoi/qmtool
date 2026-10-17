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
            <span class="form-text">Your data has been successfully processed. Click the buttons below to download the processed data.</span>
        </div>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <a href="/AgentfeedbackReport1.xls" download="AgentfeedbackReport1.xls" class="btn btn-primary">Download Agent Feedback Report Current Month</a>
                <a href="/AgentfeedbackReport2.xls" download="AgentfeedbackReport2.xls" class="btn btn-primary">Download Summary of Current Month</a>
                <a href="/AgentfRLastMonth1.xls" download="AgentfRLastMonth1.xls" class="btn btn-primary">Download Agent Feedback Report Last Month</a>
                <a href="/AgentfRLastMonth2.xls" download="AgentfRLastMonth2.xls" class="btn btn-primary">Download Summary of Last Month</a>
            </div>
        </div>
      </div>
    </div>
  </div>
@endsection