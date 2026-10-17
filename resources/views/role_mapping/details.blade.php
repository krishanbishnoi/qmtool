@extends('layouts.app')
<link href="/month_picker/monthpicker.css" rel="stylesheet" type="text/css">
@section('sh-title')
Mapping 
@endsection

@section('sh-detail')
Cluster Mapping
@endsection

@section('main')
<div class="row">
  <div class="col-md-12">

    <!--begin::Portlet-->
    <div class="kt-portlet">
      <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
          <h3 class="kt-portlet__head-title">
            Details
          </h3>
        </div>
      </div>

      <!--begin::Form-->
      
    
        
          <div class="kt-portlet__body">
        
            <div class = "row form-group">
               
                  <label> <b> Client List</b></label>
                  <br>
                  
                  @foreach($clients as $row)
                  <div style = "display: block" class = "card p-1 ml-1">
                    <label>{{$row->name}}</label>
                    </div>
                  @endforeach    
                
            </div>

            <div class = "row form-group">
               
               <label> <b> Partners</b></label>
               <br>
               
               @foreach($partner as $row)
               <div style = "display: block" class = "card p-1 ml-1">
                 <label>{{$row->name}}</label>
                 </div>
               @endforeach    
             
            </div>

            <div class = "row form-group">
               
               <label> <b> Process List</b></label>
               <br>
               
               @foreach($process as $row)
               <div style = "display: block" class = "card p-1 ml-1">
                 <label>{{$row->name}}</label>
                 </div>
               @endforeach    
             
            </div>

            <div class = "row form-group">
               
               <label> <b> locations</b></label>
               <br>
               
               @foreach($location as $row)
               <div style = "display: block" class = "card p-1 ml-1">
                 <label>{{$row->name}}</label>
                 </div>
               @endforeach    
             
            </div>

          </div>
          
      

      <!--end::Form-->
    </div>

    <!--end::Portlet-->
  </div>
</div>

@endsection
@section('js')
@include('shared.form_js')

<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
@endsection