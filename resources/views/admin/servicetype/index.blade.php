@extends('admin.layouts.master')
@section('title', '| Service Type')
@section('content')
<div class="container-fluid pt-8">
    <div class="page-header mt-0 shadow p-3">
        <ol class="breadcrumb mb-sm-0">
            <li class="breadcrumb-item"><a href="#">Service</a></li>
            <li class="breadcrumb-item active" aria-current="page">Type</li>
        </ol>
        <div class="btn-group mb-0">
            <a class="btn btn-primary btn-sm " href="{{ url('admin/service/create')}}" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus mr-2"></i>Add Service Type</a>
            {{-- <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
            <div class="dropdown-menu"> --}}
            </div>
        </div>
    </div>
    <div class="card shadow overflow-hidden">
        <div class="card">
            <div class="table-responsive p-3">
                <table class="table card-table table-vcenter text-nowrap  align-items-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Created At</th>
                            <th>Update At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $d->id }}</td>
                                <td class="text-sm font-weight-600">{{ $d->name }}</td>
                                <td>
                                    <img src="{{ asset('')}}{{ $d->image }}" width="30px" alt="">
                                </td>
                                <td class="text-nowrap">{{ $d->created_at }}</td>
                                <td class="text-nowrap">{{ $d->updated_at }}</td>
                                <td class="text-nowrap">
                                    <a class=""
                                    href="#" data-toggle="modal" onclick="getEditId({{ $d->id }})" data-target="#eidtModal">
                                        <i class="fe fe-edit"></i>
                                    </a>
                                    <a class="text-danger"
                                    href="#" data-toggle="modal" onclick="getEditId({{ $d->id }})" data-target="#deleteModal">
                                        <i class="fe fe-trash"></i>
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
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Add New Servies Type</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form1" action="{{ route('admin.service.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="text" class="form-control" name="name" placeholder="Service Name" value="">
                    <br>
                    <input type="file" class="form-control" name="image" placeholder="Image Upload" value="">
                    <br>
                    <button class="btn btn-primary btn-block waves-effect waves-light" id="save" type="submit"> {{ __('Save') }}</button>
                    </div>
                </form>

        </div>
    </div>
</div>
<div class="modal fade" id="eidtModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Edit Servies Type</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.service.update')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id" name="id" value="">
                    <input type="text" class="form-control"id="name" name="name" placeholder="Service Name" value="">
                    <br>
                    <input type="file"  class="form-control" name="image" placeholder="Image Upload" value="">
                    <br>
                    <img src=""  id="image" height="80" alt="">
                    <button class="btn btn-primary btn-block waves-effect waves-light" id="update" type="submit"> {{ __('Update') }}</button>
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
                        <button class="btn btn-danger waves-effect waves-light" ata-dismiss="modal" aria-label="Close"> {{ __('NO') }}</button>
                    </div>
                </form>

        </div>
    </div>
</div>
<script>
function getEditId(id) {
    $.ajax({
        type: 'GET',
        url: '{{ url('admin/service/edit') }}/' + id,
        contentType: "application/json",
        dataType: "json",
        success: function(data) {
            document.getElementById('id').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('image').src = '{{ asset('/')}}'+data.image;
            document.getElementById('deleteId').value = data.id;
        }
    });
}
</script>

@endsection
