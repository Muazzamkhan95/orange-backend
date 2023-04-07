@extends('admin.layouts.master')
@section('title', '| Valuet  List')
@section('content')
@php
$walletDetails = [];
@endphp
<div class="container-fluid pt-8">
    <div class="page-header mt-0 shadow p-3">
        <ol class="breadcrumb mb-sm-0">
            <li class="breadcrumb-item"><a href="#">Driver Valuet </a></li>
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
                            <th>Collected Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($driver as $d)
                            <tr>
                                <td>{{ $d->id }}</td>
                                <td class="text-sm font-weight-600">{{ $d->name }}</td>
                                <td>{{ $d->wallet->sum('amount') }}</td>
                                <td class="text-nowrap">
                                    <a class="" href="" onclick="getDetails({{ $d->id }})" data-toggle="modal" data-target="#exampleModal">
                                        <i class="fe fe-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"><span id="exampleModalLabel"></span> Valuet</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table" id="my-table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Collected Amount</th>
                        </tr>
                    </thead>
                    <tbody id="my-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function getDetails(id) {
    $.ajax({
        type: 'GET',
        url: '{{ url('admin/driver/walletDetails') }}/' + id,
        contentType: "application/json",
        dataType: "json",
        success: function(data) {
            // console.log(data);
            populateTable(data);
            document.getElementById('exampleModalLabel').innerHTML = data[0].driver.name;
            // document.getElementById('name').value = data.name;
            // document.getElementById('rate').value = data.rate;
            // document.getElementById('rate1').value = data.rate1;
            // document.getElementById('deleteId').value = data.id;
        }
    });
}
function populateTable(data) {
    var tableBody = document.getElementById('my-table-body');
    tableBody.innerHTML = '';
    data.forEach(function(row) {
    var tableRow = document.createElement('tr');
    tableRow.innerHTML = '<td>' + row.id + '</td>' +
                        '<td>' + row.driver.name + '</td>'+
                        '<td>' + row.amount + '</td>';
    tableBody.appendChild(tableRow);
  });
}

</script>
@endsection
