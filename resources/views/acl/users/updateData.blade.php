@extends('layouts.app')
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script type='text/javascript' src='https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js'></script>
<script type='text/javascript' src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js'></script>
<script type='text/javascript' src='https://unpkg.com/popper.js'></script>
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js'></script>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css" />
<link rel="stylesheet" href="{{asset('c_dash_css/select2.min.css')}}">
<script type="text/javascript" src="{{ asset('cdn/select2.min.js') }}" ></script>
@section('sh-title')
Users Uploader
@endsection

@section('sh-detail')
Process action dump
@endsection

@section('main')
<style>

.rdoLbl{
  
  margin-top:20px;
}
.right{
  float: right;
}
.modal {
  visibility: hidden;
  opacity: 0;
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(77, 77, 77, .7);
  transition: all .4s;
}

.modal:target {
  visibility: visible;
  opacity: 1;
}

.modal__content {
  border-radius: 4px;
  position: relative;
  width: 500px;
  max-width: 90%;
  background: #fff;
  padding: 1em 2em;
}
.modal-dialog, .modal-content {
  height: 70%;
}

.modal-body {
  max-height: calc(100% - 120px);;
  overflow-y: scroll;
}
.modal__footer {
  text-align: right;
  a {
    color: #585858;
  }
  i {
    color: #d02d2c;
  }
}
.modal__close {
  position: absolute;
  top: 10px;
  right: 10px;
  color: #585858;
  text-decoration: none;
}
</style>
<div class="row">
  <div class="col-md-6">
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <button type="button" class="btn btn-primary mt-3 mb-3"  data-toggle="modal" data-target="#exampleModal">
                Upload Excel
            </button>
        </div>
    </div>
  </div>
</div>



<!-- 
  users import model
 -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Please Upload Excel</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <div class="modal-body">
              {!! Form::open(
                  array(
                    'route' => 'updateExcelData',
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator",
                    'enctype'=>'multipart/form-data')
                  ) !!}
                <div class="kt-portlet__body">

                  <div class="form-group">
                      <b></b>
                      <input type="file" name="users_file" class="form-control" required/>
                      <span class="form-text text-muted">Max file size:- 50MB. File format:- .xlsx only</span>
                  </div>
                  <div class="kt-form__actions">
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    <button type="reset" class="btn btn-secondary mt-3">Cancel</button>
                  </div>
                </div>
              </form>
            </div>
        </div>
      </div>
    </div>
    <!-- end users import -->

   
@endsection
@section('css')
@include('shared.table_css')

@endsection

@section('js')

@endsection