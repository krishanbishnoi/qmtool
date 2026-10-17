@extends('layouts.app')

 @section('sh-title')
 Update Training Details
 @endsection
 
 @section('sh-detail')
 Update Training Details
 @endsection
 
 @section('main')
     <div class="kt-portlet kt-portlet--mobile">
         <div class="kt-portlet__head">
             <div class="kt-portlet__head-label">
                 <h3 class="kt-portlet__head-title">
                    Update Training Details
                 </h3>
             </div>
         </div>


         <div class="kt-portlet__body">         
            <form action="/update_training_submit" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-lg-4">
                        <label>Select Partner QA</label>
                        <select class="form-control m-select2" id="selected_partner_qa_list" name="selected_partner_qa[]" multiple required>
                            <option value="">Select Partner QA</option>
                            @foreach ($attende_qa as $k => $v)
                                <option value="{{ $v }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-4">
                        <label>Enter Time</label>
                        <input type="datetime-local" name="training_date" class="form-control" required>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-8">
                        <label>Feedback</label>
                        <textarea name="training_feedback" class="form-control" rows="6" placeholder="Add any comments or instructions here..."></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-8">
                        <label>Upload Image:</label>
                        <input type="file" name="image" class="form-input"><br><br>
                        {{-- <textarea name="training_feedback" class="form-control" rows="6" placeholder="Add any comments or instructions here..."></textarea> --}}
                    </div>
                </div>

 
                <div class="form-group row">
                    <div class="col-lg-4">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>

         {{-- <div class="kt-portlet__body">
             <form action="/qtl_dashboard/update_training_submit" method="post" enctype="multipart/form-data">
                 @csrf
                 <div class="form-group row">
                   
                     
                     <div class="col-lg-6"><br>
                        <label>Upload Image:</label>
                        <input type="file" name="image" class="form-input"><br><br>
                         <label for="training_date">Date</label>
                         <input type="date" id="training_date" name="training_date"><br><br>
                         <label for="training_feedback">Feedback</label>
                         <input type="text" id="training_feedback" name="training_feedback"><br><br>
                   
                         <button  type="Submit" class="btn btn-success output">Submit</button>
                     </div>
                     
                 </div>
             </form>
 
             {{-- {{ Form::close() }} --}}
         {{-- </div>  --}}
     </div>

 @endsection
 