<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\SelfServiceTrips;
use App\Models\ServiceDetail;
use App\Models\ServiceType;
use App\Models\Wallet;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Double;

class SelfServiceTripsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function booking(Request $request){
        // dd($request->all());

        $data = new SelfServiceTrips();
        $data->pickup = $request->pickup;
        $data->pickup_lat = $request->pickup_lat;
        $data->pickup_lng = $request->pickup_lng;

        $data->destination = $request->destination;
        $data->destination_lat = $request->destination_lat;
        $data->destination_lng = $request->destination_lng;
        $data->phone_number = $request->phone_number;

        $data->status = 1;
        $data->driver_id = $request->driver_id;
        $data->payment_method_id = $request->payment_method_id;
        $data->service_type_id = $request->service_type_id;
        $data->service_detail_id = $request->service_detail_id;
        $data->time = $request->time;
        $data->distance = $request->distance;

        $data->amount = $request->amount;
        $data->hours_count = $request->hours_count;
        $data->device_token = $request->device_token;
        $data->save();

        return response($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bookingStatus(Request $request){

        $trip_id = $request->id;
        // dd($trip_id);
        $trip = SelfServiceTrips::find($trip_id);
        if($trip === null){
            return response('Ride Cancel', 200);
        } else {
            if($request->status == 1){
                $trip->status = 1;
                $trip->update();
                return response('Pending', 200);
            } elseif($request->status == 2){
                ////////////// Driver Accept Status /////////////////////////////

                $trip->status = 2;
                $trip->update();

                $serviceType = ServiceType::find($trip->service_type_id);
                $servicedata = [
                    "id"=> $serviceType->id ,
                    "name"=> $serviceType->name ,
                    "image"=> $serviceType->image ,
                    "status"=> (string)$serviceType->status ,
                    "created_at"=> $serviceType->created_at,
                    "updated_at"=> $serviceType->updated_at,

                ];
                $serviceDetails = ServiceDetail::find($trip->service_detail_id);
                $serviceDetail = [
                    "id"=> $serviceDetails->id,
                    "name"=> $serviceDetails->name,
                    "booking_fee"=> (string)$serviceDetails->booking_fee,
                    "rate"=> (string)$serviceDetails->rate,
                    "rate_cal"=> (string)$serviceDetails->rate_cal
                ];
                // dd($serviceDetails);
                $data=[
                    'id'=> $trip->id,
                    'pickup'=> $trip->pickup,
                    'pickup_lat'=> (double)$trip->pickup_lat,
                    'pickup_lng'=> (double)$trip->pickup_lng,
                    'destination'=> (string)$trip->destination,
                    'destination_lat'=> (double)$trip->destination_lat,
                    'destination_lng'=> (double)$trip->destination_lng,
                    'phone_number'=> (string)$trip->phone_number,
                    'status'=> (int)$trip->status,
                    'amount'=> (string)$trip->amount,
                    'hours_count'=> (string)$trip->hours_count,
                    'time'=> (string)$trip->time,
                    'distance'=> (string)$trip->distance,
                    "serviceType"=> $servicedata,
                    "serviceDetail"=> $serviceDetail,
                ];
                return response($data, 200);
            } elseif($request->status == 3){
               ////////////// Driver Arrived Status /////////////////////////////
                $trip->status = 3;
                $trip->update();
                // dd($customer);
                return response('Arrived', 200);
            } elseif($request->status == 4){
                ////////////// Driver start trip Status /////////////////////////////
                // dd($request->all());
                // dd("help");
                $trip->status = 4;
                $trip->update();
                return response('inprogress', 200);
            } elseif($request->status == 5){
                // dd($request->amount);
                $trip->amount = $request->amount;
                $trip->time = $request->time;
                $trip->destination = $request->destination;
                $trip->destination_lat = $request->destination_lat;
                $trip->destination_lng = $request->destination_lng;
                $trip->distance = $request->distance;
                $trip->timeofendtrip = $request->timeofendtrip;
                $trip->payment_method_id = 1;
                $trip->status = 5;
                $trip->update();
                $serviceType = ServiceType::find($trip->service_type_id);
                $servicedata = [
                    "id"=> $serviceType->id ,
                    "name"=> $serviceType->name ,
                    "image"=> $serviceType->image ,
                    "status"=> (string)$serviceType->status ,
                    "created_at"=> $serviceType->created_at,
                    "updated_at"=> $serviceType->updated_at,

                ];
                $serviceDetails = ServiceDetail::find($trip->service_detail_id);
                $serviceDetail = [
                    "id"=> $serviceDetails->id,
                    "name"=> $serviceDetails->name,
                    "booking_fee"=> (string)$serviceDetails->booking_fee,
                    "rate"=> (string)$serviceDetails->rate,
                    "rate_cal"=> (string)$serviceDetails->rate_cal
                ];
                $tripdata=[
                    'id'=> $trip->id,
                    'pickup'=> $trip->pickup,
                    'pickup_lat'=> (double)$trip->pickup_lat,
                    'pickup_lng'=> (double)$trip->pickup_lng,
                    'destination'=> $trip->destination,
                    'destination_lat'=> (double)$trip->destination_lat,
                    'destination_lng'=> (double)$trip->destination_lng,
                    'status'=> (int)$trip->status,
                    'amount'=> $trip->amount,
                    'hours_count'=> (string)$trip->hours_count,
                    "driver_id"=> (string)$trip->driver_id,
                    "payment_method_id"=> (string)$trip->payment_method_id,
                    'time'=> (string)$trip->time,
                    'distance'=> (string)$trip->distance,
                    'device_token'=> $trip->device_token,
                    'timeofendtrip'=> $trip->timeofendtrip,
                    "serviceType"=> $servicedata,
                    "serviceDetail"=> $serviceDetail
                ];
                return response($tripdata, 200);
            } elseif($request->status == 6){
                $trip->delete();
                return response('Trip Delete', 200);
            } else {
                $trip->delete();
                return response('Cancel', 200);
            }
        }

    }

    public function cashOrCard(Request $request){
        if($request->payment_method_id == 1){
            $wallet =new Wallet();
            $wallet->amount = $request->total_price;
            $wallet->driver_id = $request->driver_id;
            $wallet->trip_id = $request->self_trip_id;
            $wallet->save();
            return response($wallet, 200);
        } else {
            return response('Payment Type Card or Other', 200);
        }

    }

    public function CheckTripStatus(Request $request)
    {
        $trip = SelfServiceTrips::where('id', $request->id)->where('driver_id', $request->driver_id)->first();
        // $trip = Trip::find($request->id);
        if( $trip === null ){
            return response('No Record Found', 200);
        } else {
            if($trip->status == 2){
                $serviceType = ServiceType::find($trip->service_type_id);
                $servicedata = [
                    "id"=> $serviceType->id ,
                    "name"=> $serviceType->name ,
                    "image"=> $serviceType->image ,
                    "status"=> (string)$serviceType->status ,
                    "created_at"=> $serviceType->created_at,
                    "updated_at"=> $serviceType->updated_at,
                ];
                $serviceDetails = ServiceDetail::find($trip->service_detail_id);
                $serviceDetail = [
                    "id"=> $serviceDetails->id,
                    "name"=> $serviceDetails->name,
                    "booking_fee"=> (string)$serviceDetails->booking_fee,
                    "rate"=> (string)$serviceDetails->rate,
                    "rate_cal"=> (string)$serviceDetails->rate_cal
                ];
                // dd($serviceDetails);

                $data=[
                    'id'=> $trip->id,
                    'pickup'=> (string)$trip->pickup,
                    'pickup_lat'=> (double)$trip->pickup_lat,
                    'pickup_lng'=> (double)$trip->pickup_lng,
                    'destination'=> (string)$trip->destination,
                    'destination_lat'=> (double)$trip->destination_lat,
                    'destination_lng'=> (double)$trip->destination_lng,
                    'phone_number'=> (string)$trip->phone_number,
                    'status'=> (int)$trip->status,
                    'amount'=> (string)$trip->amount,
                    'hours_count'=> (string)$trip->hours_count,
                    'time'=> (string)$trip->time,
                    'distance'=> (string)$trip->distance,
                    "serviceType"=> $servicedata,
                    "serviceDetail"=> $serviceDetail
                ];
                return response($data, 200);

            } elseif($trip->status == 3){
                $serviceType = ServiceType::find($trip->service_type_id);
                $servicedata = [
                    "id"=> $serviceType->id ,
                    "name"=> $serviceType->name ,
                    "image"=> $serviceType->image ,
                    "status"=> (string)$serviceType->status ,
                    "created_at"=> $serviceType->created_at,
                    "updated_at"=> $serviceType->updated_at,
                ];
                $serviceDetails = ServiceDetail::find($trip->service_detail_id);
                $serviceDetail = [
                    "id"=> $serviceDetails->id,
                    "name"=> $serviceDetails->name,
                    "booking_fee"=> (string)$serviceDetails->booking_fee,
                    "rate"=> (string)$serviceDetails->rate,
                    "rate_cal"=> (string)$serviceDetails->rate_cal
                ];
                // dd($serviceDetails);

                $data=[
                    'id'=> $trip->id,
                    'pickup'=> (string)$trip->pickup,
                    'pickup_lat'=> (double)$trip->pickup_lat,
                    'pickup_lng'=> (double)$trip->pickup_lng,
                    'destination'=> (string)$trip->destination,
                    'destination_lat'=> (double)$trip->destination_lat,
                    'destination_lng'=> (double)$trip->destination_lng,
                    'phone_number'=> (string)$trip->phone_number,
                    'status'=> (int)$trip->status,
                    'amount'=> (string)$trip->amount,
                    'hours_count'=> (string)$trip->hours_count,
                    'time'=> (string)$trip->time,
                    'distance'=> (string)$trip->distance,
                    "serviceType"=> $servicedata,
                    "serviceDetail"=> $serviceDetail,
                ];


                return response($data, 200);
            } elseif($trip->status == 4){
                $serviceType = ServiceType::find($trip->service_type_id);
                $servicedata = [
                    "id"=> $serviceType->id ,
                    "name"=> $serviceType->name ,
                    "image"=> $serviceType->image ,
                    "status"=> (string)$serviceType->status ,
                    "created_at"=> $serviceType->created_at,
                    "updated_at"=> $serviceType->updated_at,

                ];
                $serviceDetails = ServiceDetail::find($trip->service_detail_id);
                $serviceDetail = [
                    "id"=> $serviceDetails->id,
                    "name"=> $serviceDetails->name,
                    "booking_fee"=> (string)$serviceDetails->booking_fee,
                    "rate"=> (string)$serviceDetails->rate,
                    "rate_cal"=> (string)$serviceDetails->rate_cal
                ];
                // dd($serviceDetails);

                $data=[
                    'id'=> $trip->id,
                    'pickup'=> (string)$trip->pickup,
                    'pickup_lat'=> (double)$trip->pickup_lat,
                    'pickup_lng'=> (double)$trip->pickup_lng,
                    'destination'=> (string)$trip->destination,
                    'destination_lat'=> (double)$trip->destination_lat,
                    'destination_lng'=> (double)$trip->destination_lng,
                    'phone_number'=> (string)$trip->phone_number,
                    'status'=> (int)$trip->status,
                    'amount'=> (string)$trip->amount,
                    'hours_count'=> (string)$trip->hours_count,
                    'time'=> (string)$trip->time,
                    'distance'=> (string)$trip->distance,
                    "serviceType"=> $servicedata,
                    "serviceDetail"=> $serviceDetail
                ];
                return response($data, 200);
            } else {
                $data = [
                    "status"=> ''
                ];
                return response($data, 404);
            }

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SelfServiceTrips  $selfServiceTrips
     * @return \Illuminate\Http\Response
     */
    public function edit(SelfServiceTrips $selfServiceTrips)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SelfServiceTrips  $selfServiceTrips
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SelfServiceTrips $selfServiceTrips)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SelfServiceTrips  $selfServiceTrips
     * @return \Illuminate\Http\Response
     */
    public function destroy(SelfServiceTrips $selfServiceTrips)
    {
        //
    }
}
