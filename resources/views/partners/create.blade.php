@extends('layouts.app')

@section('sh-title')
Partner
@endsection

@section('sh-detail')
Create New
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
      
        {!! Form::open(
                  array(
                    'route' => 'client_partner_store', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator",
                    'enctype'=>"multipart/form-data")
                  ) !!}
        <input type="hidden" name="company_id" value="{{Auth::User()->company_id}}">
        <input type="hidden" name="client_id" value="{{Crypt::encrypt($client_data->id)}}">

        <div class="kt-portlet__body">

          <div class="form-group row">
            <div class="col-lg-6">
            <label>Client</label>
            <input type="text" class="form-control" readonly value="{{$client_data->name}}">
          </div>
          <div class="col-lg-6">
            <label>Partner Name*</label>
            <input type="text" name="partner_name" class="form-control">
          </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-6">
            <label>Conact Email*</label>
            <input type="email" name="contact_email" class="form-control">
          </div>
            <div class="col-lg-6">
            <label>Logo*</label>
            <input type="file" name="logo" class="form-control">
          </div>
        </div>
          <div class="form-group row">
          <div class="col-lg-6">
              <label>Partner's Process*</label>
            {{ Form::select('partner_process_id[]',$all_process,null,['class'=>'form-control m-select2','id'=>'kt_select2_1','multiple'=>'multiple']) }}
            </div>
            <div class="col-lg-6">
            <label>Details</label>
            <textarea class="form-control" name="details"></textarea>
          </div>
        </div>

        <h6>Partner Admin User Details</h6>

        <div class="form-group row">
          <div class="col-lg-6">
              <label>Admin</label>
            {{ Form::select('admin_user_id',
                            $all_partner_admin,
                            null,
                            ['class'=>'form-control',
                            'required'=>'required',
                            'placeholder'=>"Select and partner admin"]) }}
            </div>
        </div>

          <!-- <div class="form-group row">
            <div class="col-lg-6">
            <label>Name*</label>
            <input type="text" name="user_name" class="form-control" required>
            </div>
            <div class="col-lg-6">
            <label>Primary Email (as username)*</label>
            <input type="text" name="email" class="form-control" required>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-lg-6">
            <label>Mobile No.*</label>
            <input type="text" name="mobile" class="form-control" required>
          </div>
          <div class="col-lg-6">
            <label>Password*</label>
            <input type="password" name="password" class="form-control" required>
          </div>
        </div>
          <div class="form-group row">
            <div class="col-lg-6">
            <label>Confirm Password*</label>
            <input type="password" name="password_confirmation" class="form-control" required>
            </div>
          </div>-->

          <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
          <h6>Add Partner Locations</h6>
          <br/>
          <div id="kt_repeater_1">
                        <div class="form-group  row" id="kt_repeater_1">
                          <label class="col-lg-2 col-form-label">Locations:</label>
                          <div data-repeater-list="locations" class="col-lg-10">
                            <div data-repeater-item class="form-group row align-items-center">
                              <div class="col-md-7">
                                <div class="kt-form__group--inline">
                                  <div class="kt-form__label">
                                    <label>Select Location:</label>
                                  </div>
                                  <div class="kt-form__control">
                                  {{ Form::select('location_id',$all_regions,null,['class'=>'form-control','placeholder'=>"Select one"]) }}
          
                                  </div>
                                </div>
                                <div class="d-md-none kt-margin-b-10"></div>
                              </div>
                            
                              
                              <div class="col-md-4">
                                <div data-repeater-delete="" class="btn-sm btn btn-danger btn-pill">
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
                            <div data-repeater-create="" class="btn btn btn-sm btn-brand btn-pill">
                              <span>
                                <i class="la la-plus"></i>
                                <span>Add</span>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>

        </div> 
        <div class="kt-portlet__foot">
          <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
          </div>
        </div>
      </form>

      
    </div>

    <!--end::Portlet-->
  </div>
</div>

@endsection
@section('js')
@include('shared.form_js')
@endsection