@extends('admin.layouts.master')
@section('title', '| Driver Detail')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
{{-- <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css'> --}}
<div class="container-fluid pt-8">
    <div class="page-header mt-0 shadow p-3">
        <ol class="breadcrumb mb-sm-0">
            <li class="breadcrumb-item"><a href="#">Driver</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
        <div class="btn-group mb-0">
            {{-- <a class="btn btn-primary btn-sm " href="" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus mr-2"></i>Add Service Type</a> --}}
            {{-- <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
            <div class="dropdown-menu"> --}}
            </div>
        </div>
    </div>
    <div class="card shadow overflow-hidden">
        <div class="card pt-2 px-5">
            <div class="row">
                <table class="w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>CNIC No</th>
                            <th>Licence No</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $driver->id}}</td>
                            <td>{{ $driver->name}}</td>
                            <td>{{ $driver->email}}</td>
                            <td>{{ $driver->phone}}</td>
                            <td>{{ $driver->cnic}}</td>
                            <td>{{ $driver->lic}}</td>
                            <td>
                                <form action="{{ route('admin.driver.varify')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $driver->id }}">
                                    <select name="status" class="form-select">

                                        <option value="1" selected>
                                            @if ($driver->status == 0)
                                                Pending
                                            @elseif ($driver->status == 1)
                                                Approved
                                            @elseif ($driver->status == 2)
                                                Reject
                                            @elseif ($driver->status == 3)
                                            Disabled
                                            @else
                                            @endif
                                        </option>
                                        <option value="1">Approved</option>
                                        <option value="2">Reject</option>
                                        <option value="3">Disabled</option>

                                    </select>
                                    <br>
                                    <button class="btn btn-primary waves-effect waves-light" id="save" type="submit"> {{ __('Save') }}</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <h5 class="mb-0 mx-2">Profile Image</h5>
                    <img src="{{ asset('/') }}{{ $driver->profile_image}}" data-action="zoom" data-original="{{ asset('/') }}{{ $driver->profile_image}}" alt="">
                </div>
                <div class="col-lg-6">
                    <h5 class="mb-0 mx-2">CV File</h5>
                    <a href="{{ asset('/') }}{{ $driver->cv_file}}">CV Download</a>
                </div>
                <div class="col-lg-6">
                    <h5 class="mb-0 mx-2">CNIC Front</h5>
                    <img src="{{ asset('/') }}{{ $driver->cnic_front}}" class="img-fluid" data-action="zoom" data-original="{{ asset('/') }}{{ $driver->cnic_front}}" alt="">
                </div>
                <div class="col-lg-6">
                    <h5 class="mb-0 mx-2">CNIC Back</h5>
                    <img src="{{ asset('/') }}{{ $driver->cnic_back}}"  class="img-fluid" data-action="zoom" data-original="{{ asset('/') }}{{ $driver->cnic_back}}" alt="">
                </div>
                <div class="col-lg-6">
                    <h5 class="mb-0 mx-2">Licence Front</h5>
                    <div class="value-img">
                        <img src="{{ asset('/') }}{{ $driver->lic_front}}" data-action="zoom" data-original="{{ asset('/') }}{{ $driver->lic_front}}" alt="journey_thumbnail"  class="img-fluid" alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <h5 class="mb-0">Licence Back</h5>
                    <img src="{{ asset('/') }}{{ $driver->lic_back}}" data-action="zoom" data-original="{{ asset('/') }}{{ $driver->lic_back}}" alt="">
                </div>
            </div>

        </div>
    </div>



</div>
<script src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/zooming.min.js'></script>
<script>
    /* eslint-disable */

var config = Zooming.config(),
TRANSITION_DURATION_DEFAULT = config.transitionDuration,
TRANSITION_DURATION_SLOW = 1.0,
TRANSITION_DURATION_FAST = 0.2,
BG_COLOR_DEFAULT = config.bgColor,
BG_COLOR_DARK = '#000',
ENABLE_GRAB_DEFAULT = config.enableGrab,
ACTIVE_CLASS = 'button-primary',

btnFast = document.getElementById('btn-fast'),
btnSlow = document.getElementById('btn-slow'),
btnDark = document.getElementById('btn-dark'),
btnNoGrab = document.getElementById('btn-no-grab');

function isActive(el) {
  return el.classList.contains(ACTIVE_CLASS);
}

function activate(el) {
  el.classList.add(ACTIVE_CLASS);
}

function deactivate(el) {
  el.classList.remove(ACTIVE_CLASS);
}

function fast() {
  var t;
  if (isActive(btnFast)) {
    t = TRANSITION_DURATION_DEFAULT;
    deactivate(btnFast);
  } else {
    t = TRANSITION_DURATION_FAST;
    activate(btnFast);
    deactivate(btnSlow);
  }

  Zooming.config({ transitionDuration: t });
}

function slow() {
  var t;
  if (isActive(btnSlow)) {
    t = TRANSITION_DURATION_DEFAULT;
    deactivate(btnSlow);
  } else {
    t = TRANSITION_DURATION_SLOW;
    activate(btnSlow);
    deactivate(btnFast);
  }

  Zooming.config({ transitionDuration: t });
}

function dark() {
  var c;
  if (isActive(btnDark)) {
    c = BG_COLOR_DEFAULT;
    deactivate(btnDark);
  } else {
    c = BG_COLOR_DARK;
    activate(btnDark);
  }

  Zooming.config({ bgColor: c });
}

function noGrab() {
  var enable;
  if (isActive(btnNoGrab)) {
    enable = ENABLE_GRAB_DEFAULT;
    deactivate(btnNoGrab);
  } else {
    enable = !ENABLE_GRAB_DEFAULT;
    activate(btnNoGrab);
  }

  Zooming.config({ enableGrab: enable });
}
</script>

@endsection
