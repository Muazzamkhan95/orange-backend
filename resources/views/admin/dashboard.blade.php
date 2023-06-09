@extends('admin.layouts.master')
@section('title', '| Dashboard')
@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        var visitor = <?php echo $d; ?>;
        console.log(visitor);
    </script>

    <div class="container-fluid pt-8">

        <div class="card shadow overflow-hidden">

        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card  shadow overflow-hidden">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-light ls-1 mb-1">Vector Map</h6>
                                <h2 class="mb-0">World Map</h2>
                            </div>
                        </div>
                    </div>
                    @php
                        echo $d
                    @endphp
                    @if ($d === [] )
                        <div class="card-body">
                            <p></p>
                        </div>
                    @else
                        <div class="card-body">
                            <div id="map_div" style="width: auto; height: 300px"></div>
                            <style>
                                /*
                            * Always set the map height explicitly to define the size of the div element
                            * that contains the map.
                            */
                                #map {
                                    height: 100%;
                                }

                                /*
                            * Optional: Makes the sample page fill the window.
                            */
                                html,
                                body {
                                    height: 100%;
                                    margin: 0;
                                    padding: 0;
                                }

                                #floating-panel {
                                    position: absolute;
                                    top: 10px;
                                    left: 25%;
                                    z-index: 5;
                                    background-color: #fff;
                                    padding: 5px;
                                    border: 1px solid #999;
                                    text-align: center;
                                    font-family: "Roboto", "sans-serif";
                                    line-height: 30px;
                                    padding-left: 10px;
                                }

                                #map {
                                    position: initial !important;
                                }

                                #floating-panel {
                                    margin-left: -100px;
                                }
                            </style>
                            <script type="module">
                                let panorama;
                                let map;
                                let cafeMarkers = [];

                                function initMap() {
                                    let astorPlace = { lat: visitor[0].lat, lng: visitor[0].lng };
                                    map = new google.maps.Map(document.getElementById("map"), {
                                        center: astorPlace,
                                        zoom: 11,
                                        streetViewControl: false,
                                    });
                                    panorama = map.getStreetView();
                                    panorama.setPosition(astorPlace);
                                    panorama.setPov({
                                        heading: 100,
                                        pitch: 0,
                                    });
                                    // Loop through the visitor array and add markers
                                    visitor.forEach(function(item, index, arr) {
                                        let l = arr[index].lat;
                                        let g = arr[index].lng;
                                        cafeMarkers.push(new google.maps.Marker({
                                            position: { lat: l, lng: g },
                                            map: map,
                                            icon: "{{ asset('/')}}{{\App\Models\BusinessSetting::where(['type' => 'driver_icon'])->pluck('value')[0]}}",
                                            title: arr[index].Name,
                                        }));
                                    });
                                }

                                window.initMap = initMap;
                            </script>

                            <div id="map" style="position: initial;"></div>


                            <script
                                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUXE2dMW9PAoFR_f5D7g2vQl_r31TZeZs&callback=initMap&v=weekly"
                                defer></script>
                        </div>

                    @endif
                </div>
            </div>
        </div>



    </div>
    <div class="content-header">

        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard v1</li>
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">Actions</button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#"><i class="fas fa-plus mr-2"></i>Add new Page</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-eye mr-2"></i>View the page Details</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-edit mr-2"></i>Edit Page</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i> Settings</a>
                </div>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $tripToday }}</h3>

                            <p>Today Rides</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $trip }}</h3>

                            <p>Total Rides</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $customer }}</h3>

                            <p>New Users</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $driver_count }}</h3>

                            <p>Rider</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->

            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
