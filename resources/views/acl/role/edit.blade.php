@extends('layouts.app')

@section('sh-title')
Role
@endsection

@section('sh-detail')
Create New
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

                  {!! Form::model($data,
                  array(
                  'method' => 'PATCH',
                    'url' =>'role/'.Crypt::encrypt($data->id),
                    'class' => 'kt-form',
                    'data-toggle'=>"validator")
                  ) !!}


        <div class="kt-portlet__body">

          <div class="form-group">
            <label>Name*</label>
            <input type="text" name="name" class="form-control" required value="{{$data->name}}">
          </div>
          <div class="form-group">
            <label>Display Name*</label>
            <input type="text" name="display_name" class="form-control" required value="{{$data->display_name}}">
          </div>
          <div class="form-group">
            <label>Description*</label>
            <textarea class="form-control" name="description">{{$data->description}}</textarea>
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

  <div class="col-md-6">

    <!--begin::Portlet-->
    <div class="kt-portlet">
      <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
          <h3 class="kt-portlet__head-title">
            Attach Permissions 
          </h3>
        </div>
      </div>

      <!--begin::Form-->

                  {!! Form::open(
                  array(
                    'route' => 'assign_permission', 
                    'class' => 'kt-form')
                  ) !!}



        <div class="kt-portlet__body">
          <input type="hidden" name="role_id" value="{{$data->id}}">
          <div class="form-group">
            <label>Select Permissions*</label>
            <select class="form-control" name="permission_id">
                        <option></option>
                        @foreach($permissions as $k=>$v)
                                      <option value="{{$k}}">{{$v}}</option>
                                      @endforeach
                      </select>
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
        Attached Permission List
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
          <th>#</th>
          <th>Permission</th>
          <th>Action</th>
          
        </tr>
      </thead>
      <tbody>
        @foreach($data->perms as $row)
                        <tr>
                          <th>
                            {{$loop->iteration}}
                          </th>
                          <td>
                            {{$row->display_name}}
                          </td>
                          <td>
                            <a href="{{url('role/detach/permission/'.Crypt::encrypt($data->id).'/'.Crypt::encrypt($row->id))}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                          <i class="la la-edit"></i>
                        </a>
                          </td>
                        </tr>
                        @endforeach

        </tbody>
    </table>

    <!--end: Datatable -->
</div>
</div>

@endsection