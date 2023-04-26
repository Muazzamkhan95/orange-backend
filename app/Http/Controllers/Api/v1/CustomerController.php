<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ServiceType;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function update(Request $request){
        $data = User::where('id', $request->user_id)->first();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;

        $data->update();
        $customer = Customer::where('user_id', $data->id)->first();
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->dob = $request->dob;
        $customer->gender = $request->gender;
        if (!empty(request()->file('profile_image'))) {
            $destinationPath = 'storage/profile_image';
            $extension = request()->file('profile_image')->getClientOriginalExtension();
            $fileName ='/storage/profile_image/'. 'pi-' . time() . rand() . $customer->id . '.' . $extension;
            request()->file('profile_image')->move($destinationPath, $fileName);
            $customer->profile_image = $fileName;
        }


        // dd($driver->profile_image);
        // dd($data->file, $data->image, $data->imag1, $data->imag2, $data->imag3);

        $customer->update();
        $customerreturn = Customer::find($customer->id);
        $updateProfile = [
            'id'=> $customerreturn->id,
            'user_id'=> (string)$customerreturn->user_id,
            'name'=> $customerreturn->name,
            'email'=> $customerreturn->email,
            'profile_image'=> $customerreturn->profile_image,
            'phone'=> $customerreturn->phone,
            'gender'=> $customerreturn->gender,
            'dob'=> $customerreturn->dob,
            'customer_role'=> (string)$customerreturn->rider_role,
            ];

        return response($updateProfile, 200);
    }

    public function myTrip($id){
        $data = Trip::where('customer_id', $id)->where('status', 5)->orderBy('id', 'DESC')->limit(20)->get();
        $trip = array();
        foreach($data as $t){
            $trip_data = [
                "id"=> $t->id,
                "pickup"=> $t->pickup,
                "pickup_lat"=> (string)$t->pickup_lat,
                "pickup_lag"=> (string)$t->pickup_lag,
                "destination"=> $t->destination,
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
                "notes"=> $t->notes,
                "date"=> $t->created_at->format('d-m-Y'),
                "timeofendtrip"=> $t->timeofendtrip,
                "device_token"=> $t->device_token,
            ];
            array_push($trip, $trip_data);
        }
        return response($trip, 200);
    }
    public function getTodayFareDetail($customer_id){
        $todayRide = Trip::whereDate('created_at', Carbon::today())->where('customer_id', $customer_id)->where('status', 5)->count();
        $todaysum = Trip::whereDate('created_at', Carbon::today())->where('customer_id', $customer_id)->where('status', 5)->sum('total_price');
        $todayRideDetial = Trip::whereDate('created_at', Carbon::today())->with('driver')->where('customer_id', $customer_id)->where('status', 5)->get();
        $todayRidesDetails = array();
        foreach($todayRideDetial as $totalDetial){
            $serviceType = ServiceType::where('id', $totalDetial->service_type_id)->first();
            $data = [
                "id"=> $totalDetial->id,
                "driver_id"=> $totalDetial->driver_id,
                "service_name"=> $serviceType->name,
                "driver_name"=> $totalDetial->driver->name,
                "amount"=> $totalDetial->amount,
                "total_price"=> $totalDetial->total_price,
                "pickup"=> $totalDetial->pickup,
                "destination"=> $totalDetial->destination,
                "timeofendtrip"=> $totalDetial->timeofendtrip,
            ];
            array_push($todayRidesDetails, $data);
        }
        $todayDetail = [
            "todayTotalRide"=> $todayRide,
            "todayamountSum"=> $todaysum,
            "todaytotalRideDetial"=> $todayRidesDetails
        ];

        // $Ride = Trip::whereBetween('created_at', [$from, $to])->where('customer_id', $customer_id)->where('status', 5)->count();
        // $Sum = Trip::whereBetween('created_at', [$from, $to])->where('customer_id', $customer_id)->where('status', 5)->sum('total_price');
        // $RideDetial = Trip::whereBetween('created_at', [$from, $to])->with('customer')->where('customer_id', $customer_id)->where('status', 5)->get();

        // dd($amountsum, $totalRide);
        // $ridesDetails = array();
        // foreach($RideDetial as $totalDetial){
        //     $data = [
        //         "id"=> $totalDetial->id,
        //         "driver_id"=> $totalDetial->driver_id,
        //         "driver_name"=> $totalDetial->driver->name,
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

        return response($todayDetail, 200);
        // return response([$todayDetail, $weekdetails, $Alldetails], 200);
    }
    public function getWeekFareDetail($customer_id){

        $from = Carbon::now()->startOfWeek();
        $to = Carbon::now()->endOfWeek();
        $weekRide = Trip::whereBetween('created_at', [$from, $to])->where('customer_id', $customer_id)->where('status', 5)->count();
        $weekSum = Trip::whereBetween('created_at', [$from, $to])->where('customer_id', $customer_id)->where('status', 5)->sum('total_price');
        $weekRideDetial = Trip::whereBetween('created_at', [$from, $to])->with('customer')->where('customer_id', $customer_id)->where('status', 5)->get();

        // dd($amountsum, $totalRide);
        $weekridesDetails = array();
        foreach($weekRideDetial as $totalDetial){
            $serviceType = ServiceType::where('id', $totalDetial->service_type_id)->first();
            $data = [
                "id"=> $totalDetial->id,
                "driver_id"=> $totalDetial->driver_id,
                "service_name"=> $serviceType->name,
                "driver_name"=> $totalDetial->driver->name,
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

        return response($weekdetails, 200);
        // return response([$todayDetail, $weekdetails, $Alldetails], 200);

    }
    public function getChartData($customer_id){

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

            $posts = Trip::where('customer_id', $customer_id)->where('created_at', '>=', $dat)->sum('total_price');
            // $datenow = Trip::where('created_at', '>=', $dat)->first()->pluck( 'created_at');
            $day = Carbon::parse($dat)->format('l');
            $item = [
                "DayName"=> $day,
                "sum"=> $posts,
            ];
            // dd($posts);
            array_push($totat_price_sum, $item);
        }
        // dd($totat_price_sum);
        // Merge the two collections; any results in `$posts` will overwrite the zero-value in `$dates`
        // $dates = $dates->merge( $posts );
        // dd($dates);
        return response( $totat_price_sum, 200);
    }
}
