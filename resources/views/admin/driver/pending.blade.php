@extends('admin.layouts.master')
@section('title', '| Pending Driver List')
@section('content')
<div class="container-fluid pt-8">
    <div class="page-header mt-0 shadow p-3">
        <ol class="breadcrumb mb-sm-0">
            <li class="breadcrumb-item"><a href="#">Driver</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pending</li>
        </ol>
        <div class="btn-group mb-0">
            {{-- <a class="btn btn-primary btn-sm " href="" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus mr-2"></i>Add Service Type</a> --}}
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
                            <th>Email</th>
                            <th>Dob</th>
                            <th>Phone</th>
                            {{-- <th>Gender</th> --}}
                            <th>Image</th>
                            <th>status</th>
                            {{-- <th>Updated_At</th> --}}
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $d->id }}</td>
                                <td class="text-sm font-weight-600">{{ $d->name }}</td>
                                <td>{{ $d->email }}</td>
                                <td>{{ $d->dob }}</td>
                                <td>{{ $d->phone }}</td>
                                {{-- <td>{{ $d->gender }}</td> --}}
                                <td><img src="{{ asset('')}}{{ $d->profile_image }}" width="80px" alt=""></td>
                                <td class="text-nowrap">
                                    @if ($d->status == 1)
                                        Active
                                    @else
                                        Pending
                                    @endif
                                </td>
                                {{-- <td class="text-nowrap">{{ $d->updated_at }}</td> --}}
                                <td class="text-nowrap">
                                    <a class=""
                                    href="{{ route('admin.driver.detail', $d->id) }}">
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
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Add New Rate Type</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- <form id="form1" action="{{ route('admin.rate.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="text" class="form-control" name="name" placeholder="Service Name" value="">
                    <br>
                    <input type="text" class="form-control" name="rate" placeholder="Rate" value="">
                    <br>
                    <input type="text" class="form-control" name="rate1" placeholder="Rate 1" value="">
                    <br>
                    <button class="btn btn-primary btn-block waves-effect waves-light" id="save" type="submit"> {{ __('Save') }}</button>
                    </div>
                </form> --}}

        </div>
    </div>
</div>
{{-- <div class="modal fade" id="eidtModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
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
                    <input type="text" class="form-control" name="name" id="name" placeholder="Service Name" value="">
                    <br>
                    <input type="file" class="form-control" name="image" id="image" placeholder="File Upload" value="">
                    <br>
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
</div> --}}
{{-- <script>
function getEditId(id) {
    $.ajax({
        type: 'GET',
        url: '{{ url('admin/service/eidt') }}/' + id,
        contentType: "application/json",
        dataType: "json",
        success: function(data) {
            document.getElementById('id').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('rate').value = data.rate;
            document.getElementById('rate1').value = data.rate1;
            document.getElementById('deleteId').value = data.id;
        }
    });
}
</script> --}}

@endsection
