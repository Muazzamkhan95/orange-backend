<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverStatus;
use App\Models\DriverToCustomerLocation;
use App\Models\Role;
use App\Models\ServiceType;
use App\Models\Trip;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Driver::all();
        // dd($data);
        return response($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Driver $driver)
    {
        //
        $data = Driver::find($id);
        return response($data, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Driver $driver)
    {
        // dd($id);
        $data = User::where('id', $request->user_id)->first();
        $data->name = $request->name;
        $data->email = $request->email;

        $data->save();
        $driver = Driver::where('user_id', $data->id)->first();
        $driver->name = $request->name;
        // $driver->phone = $request->phone;
        $driver->email = $request->email;
        $driver->cnic = $request->cnic;
        $driver->lic = $request->lic;
        if (!empty(request()->file('profile_image'))) {
            $destinationPath = 'storage/profile_image';
            $extension = request()->file('profile_image')->getClientOriginalExtension();
            $fileName ='/storage/profile_image/'. 'pi-' . time() . rand() . $driver->id . '.' . $extension;
            request()->file('profile_image')->move($destinationPath, $fileName);
            $driver->profile_image = $fileName;
        }


        // dd($driver->profile_image);
        // dd($data->file, $data->image, $data->imag1, $data->imag2, $data->imag3);

        $driver->save();
        $drivereturn = Driver::find($driver->id);
        $updateProfile = [
            'id'=> $drivereturn->id,
            'user_id'=> (string)$drivereturn->user_id,
            'name'=> $drivereturn->name,
            'email'=> $drivereturn->email,
            'profile_image'=> $drivereturn->profile_image,
            'phone'=> $drivereturn->phone,
            'license'=> $drivereturn->lic,
            'cnic'=> $drivereturn->cnic,
            'rider_role'=> (string)$drivereturn->rider_role,
            ];

        return response($updateProfile, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Driver $driver)
    {
        //
    }
    public function getTripAccept($id){
        $data = Trip::with('driver')->where('driver_id', $id)->where('status', 1)->get();
        $accept = $data->avg('status');
        return response($accept, 200);

    }
    public function getTripCancel($id){
        $data = Trip::with('driver')->where('driver_id', $id)->where('status', 2)->get();
        $cancel = $data->avg('status');
        return response($cancel, 200);

    }
    public function storeDriverStatus(Request $request)
    {
        $driverstatus = DriverStatus::where('driver_id', $request->id)->first();
        // dd($request->all());
        if($driverstatus != null){
            $driverstatus->lat= $request->lat;
            $driverstatus->lag= $request->lag;
            $driverstatus->driver_id= $request->id;
            $driverstatus->status= $request->status;
            $driverstatus->device_token = $request->device_token;
            $driverstatus->update();
            return response('Driver Status Update', 200);
        } else {
            // dd($driverstatus);
            $data =new  DriverStatus();
            $data->lat= $request->lat;
            $data->lag= $request->lag;
            $data->driver_id= $request->id;
            $data->device_token = $request->device_token;
            $data->status= 1;
            $data->save();
            return response('Driver Status Update', 200);
        }


    }
    public function getDriverStatus($id)
    {
        $driverstatus = DriverStatus::where('driver_id', $id)->first();
        if ($driverstatus !=null ){
            return response($driverstatus, 200);
        } else {
            return response('Driver Not Exist', 200);
        }
    }
    public function storeDriverLocation(Request $request){
        $id= $request->id;
        $driverlocation = DriverToCustomerLocation::find($id);
        $driverlocation->lat = $request->lat;
        $driverlocation->lat = $request->lat;
        $driverlocation->driver_id = $id;
        return response($driverlocation, 200);
    }
    public function getDriverLocation($id){
        $driverlocation = DriverToCustomerLocation::find($id);
        return response($driverlocation, 200);
    }
    public function myTrip($id){
        $data = Trip::where('driver_id', $id)->where('status', 5)->orderBy('id', 'DESC')->limit(20)->get();
        // Carbon::noew
        // dd($data);

        $trip = array();
        foreach($data as $t){
            $trip_data = [
                "id"=> $t->id,
                "pickup"=> (string)$t->pickup,
                "pickup_lat"=> (string)$t->pickup_lat,
                "pickup_lag"=> (string)$t->pickup_lag,
                "destination"=> (string)$t->destination,
                "destination_lat"=> (string)$t->destination_lat,
                "destination_lag"=> (string)$t->destination_lag,
                "status"=> (string)$t->status,
                "amount"=> (string)$t->amount,
                "hours_count"=> (string)$t->hours_count,
                "driver_id"=> (string)$t->driver_id,
                "customer_id"=> (string)$t->customer_id,
                "car_id"=> (string)$t->car_id,
                "payment_method_id"=> (string)$t->payment_method_id,
                "service_type_id"=> (string)$t->service_type_id,
                "service_detail_id"=> (string)$t->service_detail_id,
                "time"=> (string)$t->time,
                "distance"=> (string)$t->driver_id,
                "notes"=> (string)$t->notes,
                "date"=> (string)$t->created_at->format('d-m-Y'),
                "timeofendtrip"=> (string)$t->timeofendtrip,
                "device_token"=> (string)$t->device_token,
            ];
            array_push($trip, $trip_data);
        }

        return response($trip, 200);
    }
    public function getTodayEarningDetail($driver_id){

        $todayRide = Trip::whereDate('created_at', Carbon::today())->where('driver_id', $driver_id)->where('status', 5)->count();
        $todaysum = Trip::whereDate('created_at', Carbon::today())->where('driver_id', $driver_id)->where('status', 5)->sum('total_price');
        $todayRideDetial = Trip::whereDate('created_at', Carbon::today())->with('customer')->where('driver_id', $driver_id)->where('status', 5)->get();
        $todayRidesDetails = array();

        foreach($todayRideDetial as $totalDetial){
            $serviceType = ServiceType::where('id', $totalDetial->service_type_id)->first();
            $data = [
                "id"=> $totalDetial->id,
                "customer_id"=> $totalDetial->customer_id,
                "service_name"=> $serviceType->name,
                "customer_name"=> $totalDetial->customer->name,
                "amount"=> $totalDetial->amount,
                "total_price"=> $totalDetial->total_price,
            ];
            array_push($todayRidesDetails, $data);
        }
        $todayDetail = [
            "todayTotalRide"=> $todayRide,
            "todayamountSum"=> $todaysum,
            "todaytotalRideDetial"=> $todayRidesDetails
        ];


        // $Ride = Trip::whereBetween('created_at', [$from, $to])->where('driver_id', $driver_id)->where('status', 5)->count();
        // $Sum = Trip::whereBetween('created_at', [$from, $to])->where('driver_id', $driver_id)->where('status', 5)->sum('total_price');
        // $RideDetial = Trip::whereBetween('created_at', [$from, $to])->with('customer')->where('driver_id', $driver_id)->where('status', 5)->get();

        // dd($amountsum, $totalRide);
        // $ridesDetails = array();
        // foreach($RideDetial as $totalDetial){
        //     $data = [
        //         "id"=> $totalDetial->id,
        //         "customer_id"=> $totalDetial->customer_id,
        //         "customer_name"=> $totalDetial->customer->name,
        //         "amount"=> $totalDetial->amount,
        //         "total_price"=> $totalDetial->total_price,
        //     ];
        //     array_push($ridesDetails, $data);
        // }

        // $Alldetails = [
        //     "totalRide"=> $Ride,
        //     "amountsum"=> $Sum,
        //     "totalRideDetial"=> $ridesDetails
        // ];

        // return response([$todayDetail, $weekdetails, $Alldetails], 200);
        return response($todayDetail, 200);
    }
    public function getWeekEarningDetail($driver_id){
        $from = Carbon::now()->startOfWeek();
        $to = Carbon::now()->endOfWeek();
        $weekRide = Trip::whereBetween('created_at', [$from, $to])->where('driver_id', $driver_id)->where('status', 5)->count();
        $weekSum = Trip::whereBetween('created_at', [$from, $to])->where('driver_id', $driver_id)->where('status', 5)->sum('total_price');
        $weekRideDetial = Trip::whereBetween('created_at', [$from, $to])->with('customer')->where('driver_id', $driver_id)->where('status', 5)->get();

        // dd($amountsum, $totalRide);
        $weekridesDetails = array();
        foreach($weekRideDetial as $totalDetial){
            $serviceType = ServiceType::where('id', $totalDetial->service_type_id)->first();
            $data = [
                "id"=> $totalDetial->id,
                "customer_id"=> $totalDetial->customer_id,
                "service_name"=> $serviceType->name,
                "customer_name"=> $totalDetial->customer->name,
                "amount"=> $totalDetial->amount,
                "total_price"=> $totalDetial->total_price,
            ];
            array_push($weekridesDetails, $data);
        }

        $weekdetails = [
            "weektotalRide"=> $weekRide,
            "weekamountsum"=> $weekSum,
            "weektotalRideDetial"=> $weekridesDetails
        ];

        // return response([$todayDetail, $weekdetails, $Alldetails], 200);
        return response($weekdetails, 200);
    }
    public function getWalletDetail($driver_id){
        $wallet = Wallet::select('amount')->where('driver_id', $driver_id)->sum();
        return response($wallet, 200);

        // $amountsum = Trip::where('driver_id', $driver_id)->where('status', 5)->sum('amount');
        // $totalRideDetial = Trip::with('customer')->where('driver_id', $driver_id)->where('status', 5)->get();

        // $ridesDetails = array();
        // foreach($totalRideDetial as $totalDetial){
        //     $data = [
        //         "id"=> $totalDetial->id,
        //         "customer_id"=> $totalDetial->customer_id,
        //         "customer_id"=> $totalDetial->customer->name,
        //         "amount"=> $totalDetial->amount,
        //         "total_price"=> $totalDetial->total_price,
        //     ];
        //     array_push($ridesDetails, $data);
        // }

        // $details = [
        //     "totalRide"=> $totalRide,
        //     "amountsum"=> $amountsum,
        //     "totalRideDetial"=> $ridesDetails
        // ];

        // return response($details, 200);
    }
    public function getChartData($driver_id){

        $dates = array();
        foreach( range( -6, 0 ) AS $i ) {
            $date = Carbon::now()->addDays( $i )->format( 'Y-m-d' );
            array_push($dates, $date);
            // $dates->put( $date, 0);
        }
        // dd($dates);

        // Get the post counts
        $totat_price_sum = array();
        foreach($dates as $dat){

            // $d = new DateTime($date);
            // $d->format('l');  //pass l for lion aphabet in format

            $posts = Trip::where('driver_id', $driver_id)->where('created_at', '>=', $dat)->sum('total_price');
            // $datenow = Trip::where('created_at', '>=', $dat)->first()->pluck( 'created_at');
            $day = Carbon::parse($dat)->format('l');
            $item = [
                "DayName"=> $day,
                "sum"=> $posts,
            ];
            // dd($posts);
            array_push($totat_price_sum, $item);
        }
        return response( $totat_price_sum, 200);
    }
    public function getWeekRides($driver_id)
    {
        // $trip = Trip::where('driver_id', $id)->where('status', 5)->get();
        $from = Carbon::now()->startOfWeek();
        $to = Carbon::now()->endOfWeek();
        $weekRide = Trip::whereBetween('created_at', [$from, $to])->where('driver_id', $driver_id)->where('status', 5)->count();
        $dates = array();
        foreach( range( -6, 0 ) AS $i ) {
            $date = Carbon::now()->addDays( $i )->format( 'Y-m-d' );
            array_push($dates, $date);
            // $dates->put( $date, 0);
        }
        // dd($dates);

        // Get the post counts
        $totat_price_sum = array();
        foreach($dates as $dat){

            // $d = new DateTime($date);
            // $d->format('l');  //pass l for lion aphabet in format

            $posts = Trip::where('driver_id', $driver_id)->where('created_at', '>=', $dat)->count();
            // $datenow = Trip::where('created_at', '>=', $dat)->first()->pluck( 'created_at');
            $day = Carbon::parse($dat)->format('l');
            $item = [
                "DayName"=> $day,
                "count"=> $posts,
            ];
            // dd($posts);
            array_push($totat_price_sum, $item);
        }
        $week_count= [
            'weekRide'=> $weekRide
        ];
        $data = [
            "total_price_sum"=> $totat_price_sum,
            "week_count"=> $week_count
        ];

        return response($data, 200);
    }
}
