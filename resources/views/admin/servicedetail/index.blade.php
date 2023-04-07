@extends('admin.layouts.master')
@section('title', '| Service Detail')
@include('admin.layouts.editorcss')


@section('content')
<div class="container-fluid pt-8">
    <div class="page-header mt-0 shadow p-3">
        <ol class="breadcrumb mb-sm-0">
            <li class="breadcrumb-item"><a href="#">Service</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
        <div class="btn-group mb-0">
            <a class="btn btn-primary btn-sm" href="" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus mr-2"></i>Add Service Details</a>
            {{-- <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
            <div class="dropdown-menu"> --}}
            </div>
        </div>
    </div>
    <div class="card shadow overflow-hidden">
        <div class="card">
            <div class="p-3">
                <table id="myTable" class="">
                    <thead class="thead-light">
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Service Type</th>
                            <th>Booking Fee</th>
                            <th>Rate</th>
                            <th>Status</th>
                            <th>Action</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $d->id }}</td>
                                <td class="text-sm font-weight-600">{{ $d->name }}</td>
                                <td>
                                    {{ $d->servicetype->name }}
                                </td>
                                <td>
                                    {{ $d->booking_fee }}
                                </td>
                                <td>
                                    {{ $d->rate }}
                                </td>
                                <td>
                                    @if ($d->status == 1)
                                        Active
                                    @else
                                        deactive
                                    @endif
                                </td>
                                <td>
                                    <a class=""
                                    href="#" data-toggle="modal" onclick="getEditId({{ $d->id }})" data-target="#eidtModal">
                                        <i class="fe fe-edit"></i>
                                    </a>
                                    <a class="text-danger"
                                    href="#" data-toggle="modal" onclick="getEditId({{ $d->id }})" data-target="#deleteModal">
                                        <i class="fe fe-trash"></i>
                                    </a>

                                </td>
                                <td>
                                    <div>
                                        {{$d->description}}
                                    </div>
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
                <h2 class="modal-title" id="exampleModalLabel">Add New Servies Type</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form1" action="{{ route('admin.service.detail-store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <label for="" class="col-form-label">Service Type</label>
                            <select class="form-control" name="service_type_id" id="">
                                <option value="" selected>Select Service Type</option>
                                @foreach ($serviceType as $type)
                                    <option value="{{ $type->id}}">{{ $type->name }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label for="" class="col-form-label">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Service Name" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <label for="" class="col-form-label">Sub Title</label>
                            <input type="text" class="form-control" name="subtitle" placeholder="Sub Title" value="">
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label for="" class="col-form-label">Booking Fee</label>
                            <input type="text" class="form-control" name="booking_fee" placeholder="Booking Fee Eg. 20" value="">
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label for="" class="col-form-label">Service Rate</label>
                            <input type="text" class="form-control" name="rate" placeholder="Service Rate Eg. AED 2, 100 " value="">
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label for="" class="col-form-label">Rate Calculation</label>
                            <select class="form-control" name="rate_cal" id="">
                                <option value="" selected>Select Rate Calculation Type</option>
                                    <option value="0">Per Minutes</option>
                                    <option value="1">Is Hourly</option>
                                    <option value="2">Is Fixed</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label for="" class="col-form-label">Decription</label>
                        <div id="editor">
                            <textarea name="description" id="trumbowyg-demo" cols="30" rows="10"></textarea>
                            {{-- <div id='edit' style="margin-top: 30px;"></div> --}}
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block waves-effect waves-light" id="save" type="submit"> {{ __('Save') }}</button>
                    </div>
                </form>

        </div>
    </div>
</div>
<div class="modal fade" id="eidtModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Edit Servies Type</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.service.detail-update')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id" name="id" value="">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <label for="" class="col-form-label">Service Type</label>
                            <select class="form-control" name="service_type_id" id="service_type_id">
                                <option value="" selected>Select Service Type</option>
                                @foreach ($serviceType as $type)
                                    <option value="{{ $type->id}}">{{ $type->name }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label for="" class="col-form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Service Name" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <label for="" class="col-form-label">Sub Title</label>
                            <input type="text" class="form-control" id="subtitle" name="subtitle" placeholder="Sub Title" value="">
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label for="" class="col-form-label">Booking Fee</label>
                            <input type="text" class="form-control" id="booking_fee" name="booking_fee" placeholder="Booking Fee Eg. 20" value="">
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label for="" class="col-form-label">Service Rate</label>
                            <input type="text" class="form-control" id="rate" name="rate" placeholder="Service Rate Eg. AED 2, 100 " value="">
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label for="" class="col-form-label">Rate Calculation</label>
                            <select class="form-control" id="rate_cal" name="rate_cal">
                                <option value="" selected>Select Rate Calculation Type</option>
                                    <option value="0">Per Minutes</option>
                                    <option value="1">Is Hourly</option>
                                    <option value="2">Is Fixed</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label for="" class="col-form-label">Decription</label>
                        <div id="editor">
                            <textarea name="description" id="trumbowyg-demo1" cols="30" rows="10"></textarea>
                            {{-- <div id='edit' style="margin-top: 30px;"></div> --}}
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-primary waves-effect waves-light" id="update" type="submit"> {{ __('Update') }}</button>
                    </div>

                    </div>
                </form>

        </div>
    </div>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Edit Servies Type</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.service.delete')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="deleteId" name="id" value="">
                    <p>Are you Sure to Delete</p>
                    <div class="d-flex">
                        <button class="btn btn-primary waves-effect waves-light" id="update" type="submit"> {{ __('Yes') }}</button>
                    </div>
                </form>

        </div>
    </div>
</div>
<script>
function getEditId(id) {
    $.ajax({
        type: 'GET',
        url: '{{ url('admin/service/detail/edit') }}/' + id,
        contentType: "application/json",
        dataType: "json",
        success: function(data) {
            console.log(data.description);
            document.getElementById('id').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('service_type_id').value = data.service_type_id;
            document.getElementById('booking_fee').value = data.booking_fee;
            document.getElementById('rate').value = data.rate;
            document.getElementById('rate_cal').value = data.rate_cal;
            document.getElementById('subtitle').value = data.subtitle;
            document.getElementById('trumbowyg-demo1').value = data.description;
            $(".trumbowyg-editor").html(data.description);
            // $(".fr-wrapper Span").html("");
            document.getElementById('deleteId').value = data.id;
        }
    });
}
</script>
@include('admin.layouts.editorjs')

@endsection
