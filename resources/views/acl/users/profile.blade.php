@extends('layouts.app')

@section('sh-title')
User
@endsection

@section('sh-detail')
Profile
@endsection

@section('main')
<div class="row">
  <div class="col-md-6">

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
      	
      <form method="POST"
      action="{{ route('profile.update', ['id' => Crypt::encrypt($rdata->id)]) }}"
      accept-charset="UTF-8"
      class="kt-form"
      role="form"
      data-toggle="validator"
      enctype="multipart/form-data"
      autocomplete="off">
        {{-- @method('PATCH') --}}
        @csrf

        <div class="kt-portlet__body">

          <div class="form-group">
            <label>Full Name*</label>
            <input type="text" name="name" readonly class="form-control" required value="{{$rdata->name}}" />
          </div>

          <div class="form-group">
            <label>Email*</label>
            <input type="text" name="email" readonly class="form-control" required value="{{$rdata->email}}" readonly="readonly">
          </div>
          @if(Auth::user()->is_first_time_user == 1)
          	<span style="color:red;"> Please reset your password to continue using QM-TOOL. </span>
          @endif
          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
               <small>Your password must be more than 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.</small><br>
          <small>Your password will expire automatically in 45 days.</small>
            {{-- <small>Your password must be more than 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.</small> --}}
          </div>
          
          <div class="form-group">
            <label>Mobile*</label>
            <input type="text" name="mobile" class="form-control" required value="{{$rdata->mobile}}" readonly="readonly">
          </div>

          <div class="form-group">
              <label class="kt-checkbox kt-checkbox--success">
                @if($rdata->two_auth)
                  <input type="checkbox" name="two_auth" value="1" checked />Two Factor Authentication
                @else
                  <input type="checkbox" name="two_auth" value="1"/>Two Factor Authentication
                @endif
                  <span></span>
              </label>
          </div>

           <div class="form-group">
            <div class="row">
              <div class="col col-md-6">
                <label>Avatar*</label>
                <input type="file" name="avatar" class="form-control"  />
              </div>
              <div class="col col-md-6">
                <!-- <img src="{{$rdata->avatar}}"  style="width: 80px; float: right;" /> -->
                @if(Auth::user()->avatar)
                <img src='{{Storage::url("company/_".Auth::user()->company_id."/user/_".Auth::Id()."/avatar/").Auth::user()->avatar}}' alt="Avatar" style="width: 80px; float: right;">
                @endif
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

      <!--end::Form-->
    </div>

    <!--end::Portlet-->
  </div>
</div>

@endsection
@section('js')
@include('shared.form_js')
@endsection