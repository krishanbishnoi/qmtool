@extends('layouts.app')

@section('sh-title')
Audit Form Fields
@endsection

@section('sh-detail')
Master
@endsection

@section('main')

<div class="kt-portlet kt-portlet--mobile">

```
<div class="kt-portlet__head kt-portlet__head--lg">

    <div class="kt-portlet__head-label">

        <span class="kt-portlet__head-icon">
            <i class="kt-font-brand flaticon2-list-2"></i>
        </span>

        <h3 class="kt-portlet__head-title">
            Field List
        </h3>

    </div>

</div>

<div class="kt-portlet__body">

    <table
        class="table table-striped table-bordered table-hover table-checkable"
        id="kt_table_1"
    >

        <thead>

            <tr>

                <th>#</th>

                <th>
                    Process
                </th>

                <th>
                    Field Name
                </th>

                <th>
                    Field Type
                </th>

                <th>
                    Total Options
                </th>

                <th>
                    Status
                </th>

                <th>
                    Action
                </th>

            </tr>

        </thead>

        <tbody>

            @foreach($data as $row)

            <tr>

                <td>
                    {{$loop->iteration}}
                </td>

                <td>
                    {{$row->process->name ?? 'NA'}}
                </td>

                <td>
                    {{$row->field_name}}
                </td>

                <td>
                    {{ucfirst($row->field_type)}}
                </td>

                <td>
                    {{$row->options->count()}}
                </td>

                <td>

                    @if($row->status == 1)

                        <span class="badge badge-success">
                            Active
                        </span>

                    @else

                        <span class="badge badge-danger">
                            Inactive
                        </span>

                    @endif

                </td>

                <td nowrap>

                    <a
                        href="{{ route('edit_field', Crypt::encrypt($row->id)) }}"
                        class="btn btn-sm btn-clean btn-icon btn-icon-md"
                        title="Edit"
                    >
                        <i class="la la-edit"></i>
                    </a>

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>
```

</div>

@endsection

@section('css')
@include('shared.table_css')
@endsection

@section('js')
@include('shared.table_js')
@endsection
