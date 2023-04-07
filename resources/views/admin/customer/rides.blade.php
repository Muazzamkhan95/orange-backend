@extends('admin.layouts.master')
@section('title', '| Customer List')
@section('content')
<div class="container-fluid pt-8">
    <div class="page-header mt-0 shadow p-3">
        <ol class="breadcrumb mb-sm-0">
            <li class="breadcrumb-item"><a href="#">Customer Ride</a></li>
            <li class="breadcrumb-item active" aria-current="page">List</li>
        </ol>
    </div>
    <div class="card shadow overflow-hidden">
        <div class="card">
            <div class="table-responsive p-3">
                <table class="table card-table table-vcenter text-nowrap  align-items-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Total Rides</th>
                            <th>Average Rating</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customer as $c)
                            <tr>
                                <td>{{ $c->id }}</td>
                                <td class="text-sm font-weight-600">{{ $c->name }}</td>
                                <td>{{ $c->trips->count() }}</td>
                                <td>{{ $c->rating->avg('rating') }}</td>
                                <td class="text-nowrap">
                                    {{-- <a class=""
                                    href="{{ route('admin.driver.detail', $c->id) }}">
                                        <i class="fe fe-eye"></i>
                                    </a> --}}


                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>



</div>


@endsection
