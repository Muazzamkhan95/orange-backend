@extends('admin.layouts.master')
@section('title', '| Today Booking Schedule List')
@section('content')
<div class="container-fluid pt-8">
    <div class="page-header mt-0 shadow p-3">
        <ol class="breadcrumb mb-sm-0">
            <li class="breadcrumb-item"><a href="#">Today Booking</a></li>
            <li class="breadcrumb-item active" aria-current="page">List</li>
        </ol>
        <div class="btn-group mb-0">
            {{-- <a class="btn btn-primary btn-sm " href="" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus mr-2"></i>Add Brand</a> --}}
            {{-- <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
            <div class="dropdown-menu"> --}}
            </div>
        </div>
    </div>
    <div class="card shadow overflow-hidden">
        <div class="card">
            <div class="p-3">
                <table id="myTable2" class="table card-table table-vcenter text-nowrap  align-items-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Id</th>
                            <th>Driver</th>
                            <th>Customer</th>
                            <th>Pickup</th>
                            <th>Destination</th>
                            {{-- <th>Pickup latitude</th> --}}
                            {{-- <th>Pickup longitude</th> --}}
                            {{-- <th>Destination latitude</th> --}}
                            {{-- <th>Destination Longitude</th> --}}
                            <th>Status</th>
                            <th>Car</th>
                            <th>Payment Method</th>
                            <th>Service Type</th>
                            {{-- <th>Updated_At</th> --}}
                            {{-- <th>Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $d->id }}</td>
                                <td>
                                    @if ($d->driver_id == 1)
                                    <strong>Name: </strong> No Driver Assign
                                    @else
                                    <strong>Name: </strong>{{ $d->driver->name }}
                                    @endif
                                    <div>
                                        <select name="deriver_id" onchange="assignDriver(this.value, {{ $d->id }})" class="form-control  js-select2-custom">
                                            @if ($d->driver_id != 1)
                                                <option value="{{ $d->driver->id }}">{{ $d->driver->name }}</option>
                                            @else
                                                <option value="">Select Driver</option>
                                            @endif
                                            @foreach ($driverData as $dD)
                                                @if ($dD->id == 1)

                                                @else
                                                    <option value="{{ $dD->id }}">{{ $dD->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td class="text-sm font-weight-600">{{ $d->customer->name }}</td>
                                <td>{{ $d->pickup }}</td>
                                <td>{{ $d->destination }}</td>
                                {{-- <td>{{ $d->pickup_lat }}</td> --}}
                                {{-- <td>{{ $d->pickup_lag}}</td> --}}
                                {{-- <td>{{ $d->destination_lat }}</td> --}}
                                {{-- <td>{{ $d->destination_lag }}</td> --}}
                                <td>
                                    @if ($d->status  == 1)
                                    Pending
                                    @elseif ($d->status  == 2)
                                    Accept
                                    @elseif ($d->status  == 3)
                                    Arrived
                                    @elseif ($d->status  == 4)
                                    In Progress
                                    @elseif ($d->status  == 5)
                                    Complete
                                    @else

                                    @endif
                                </td>
                                <td>{{ $d->car_id }}</td>
                                <td>{{ $d->paymentMethod->name }}</td>
                                <td>{{ $d->serviceType->name }}</td>
                                {{-- <td class="text-nowrap">
                                    <a class=""
                                    href="#" data-toggle="modal" onclick="getEditId({{ $d->id }})" data-target="#eidtModal">
                                        <i class="fe fe-edit"></i>
                                    </a>
                                    <a class="text-danger"
                                    href="#" data-toggle="modal" onclick="getEditId({{ $d->id }})" data-target="#deleteModal">
                                        <i class="fe fe-trash"></i>
                                    </a>

                                </td> --}}
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>



</div>

<script>
function assignDriver(driver_id, trip_id) {
    $.ajax({
        type: 'GET',
        url: '{{ url('admin/booking/assign/driver') }}' ,
        contentType: "application/json",
        data: {
            "driver_id": driver_id,
            "trip_id": trip_id,
        },
        dataType: "json",
        success: function(data) {
            location.reload();
        }
    });
}
</script>

@endsection
