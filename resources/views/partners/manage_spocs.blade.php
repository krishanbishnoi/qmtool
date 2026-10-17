@extends('layouts.app')

@section('sh-title')
{{$partner_data->name}}
@endsection

@section('sh-detail')
Manage Spocs
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
      
        {!! Form::open(
                  array(
                    'route' => 'store_partners_spocs', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator")
                  ) !!}
        <input type="hidden" name="partner_id" value="{{Crypt::encrypt($partner_data->id)}}">

        <div class="kt-portlet__body">

          <div class="form-group">
            <label>Location*</label>
            {{ Form::select('partner_location_id',$all_partner_locations,null,['class'=>'form-control','required'=>'required']) }}
          </div>
          <div class="form-group">
            <label>Process*</label>
            {{ Form::select('pp_id',$selected_processes,null,['class'=>'form-control','required'=>'required']) }}
          </div>
          <div class="form-group">
            <label>Spoc User*</label>
            {{ Form::select('user_id',$all_partner_spocs,null,['class'=>'form-control','required'=>'required']) }}
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


<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head kt-portlet__head--lg">
		<div class="kt-portlet__head-label">
			<span class="kt-portlet__head-icon">
				<i class="kt-font-brand flaticon2-line-chart"></i>
			</span>
			<h3 class="kt-portlet__head-title">
				Attached Spocs User List
			</h3>
		</div>
		<!-- <div class="kt-portlet__head-toolbar">
			<div class="kt-portlet__head-wrapper">
				<div class="kt-portlet__head-actions">
					<a href="{{url('skill/create')}}" class="btn btn-brand btn-elevate btn-icon-sm">
						<i class="la la-plus"></i>
						New Record
					</a>
				</div>
			</div>
		</div> -->
	</div>
	<div class="kt-portlet__body">

		<!--begin: Datatable -->
		<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
			<thead>
				<tr>
						<th title="Field #1">#</th>
						<th title="Field #2">
							Process
						</th>
						<th title="Field #2">
							Spoc Name
						</th>
						<th title="Field #7">
							Actions
						</th>
				</tr>
			</thead>
			<tbody>
				@foreach($all_attached_spocs as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
											<td>
												{{$row->process->name}}
											</td>
											<td>
												{{$row->user->name}}
											</td>
					<td nowrap>
                        <div style="display: flex;">
                          {{Form::open([ 'method'  => 'delete', 'route' => [ 'partner_process_spoc_delete', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])}}
                          <button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                            <i class="la la-trash"></i>
                          </button>
                        </form>
                    </div>

                </td>
            	</tr>
            @endforeach

        </tbody>
    </table>

    <!--end: Datatable -->
</div>
</div>



@endsection
@section('js')
@include('shared.form_js')
@endsection