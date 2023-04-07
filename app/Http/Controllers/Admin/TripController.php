<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverStatus;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Trip::with('driver', 'customer', 'paymentMethod', 'serviceType')->where('status', '!=', 0)->get();
        // dd($data);
        return view('admin.booking.index', compact('data'));
    }
    public function todayBooking()
    {
        $data = Trip::with('driver', 'customer', 'paymentMethod', 'serviceType')->where('status', '!=', 0)->where('created_at', '>=', Carbon::now()->toDateString())->get();
        // dd($data);
        return view('admin.booking.index', compact('data'));
    }
    public function schedule()
    {
        $data = Trip::with('driver', 'customer', 'paymentMethod', 'serviceType')->where('status', '!=', 0)->where('isSchedule', '=', 1)->get();
        // dd($data);
        $driverData = Driver::all();
        return view('admin.booking.schedule', compact('data', 'driverData'));
    }
    public function todaySchedule()
    {
        $data = Trip::with('driver', 'customer', 'paymentMethod', 'serviceType')->where('status', '!=', 0)->where('status', '!=', 5)->where('isSchedule', '=', 1)->where('date_of_schedule_booking', '>=', Carbon::now()->toDateString())->get();
        // dd($data);
        $driverData = Driver::all();
        return view('admin.booking.today-schedule', compact('data', 'driverData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function assignDriver(Request $request)
    {
        // return $request->all();
        // dd($request->driver_id);
        $trip = Trip::find($request->trip_id);
        $trip->driver_id =  $request->driver_id;
        $trip->update();
        $driverdata = Driver::find($trip->driver_id);
        $driver_name = $driverdata->name;
        $driver_status = DriverStatus::where('driver_id',$trip->driver_id)->first();
        $FcmToken = $driver_status->device_token;
        $url = 'https://fcm.googleapis.com/fcm/send';

        $serverKey = 'AAAAQJvFPAM:APA91bHKs3Xi-orSH1ujmohd3ZVrMw_cB-Qu6BN5PGcZWj9WxNwgaIEU2aTMdNMpTP278WIWvwjHZzys8ypGv1YXk5tjgyJLEVPlNOu09fSAzdo3nsDrrSvSV0Jq2hpYEEfJRejY_y_5'; // ADD SERVER KEY HERE PROVIDED BY FCM

        $data1 = [
                "to"=> $FcmToken,
                "notification"=> [
                    "title" => "New Schedule booking request received.",
                    "body" => "Please review and confirm your availability"
                ],
                "data" => [
                    "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
                    "key"=> "Image Update" //eg: "image_update"
                    ]
                ];
            $encodedData = json_encode($data1);



            $headers = [
                'Authorization:key=' . $serverKey,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            // Close connection
            curl_close($ch);

            $FcmToken1 = $trip->device_token;
            // dd($FcmToken1);
            $data2 = [
                "to"=> $FcmToken1,
                "notification"=> [
                    "title" => "Your Schedule booking Assign to Driver [".$driver_name."].",
                    "body" => "Please Get Ready on time around [".$trip->time_of_schedule_booking."]."
                ],
                "data" => [
                    "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
                    "key"=> "Image Update" //eg: "image_update"
                    ]
            ];
            $encodedData1 = json_encode($data2);

            $headers1 = [
                'Authorization:key=' . $serverKey,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData1);
            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            curl_close($ch);

            toastr('Trip Assign To Driver!', 'success');
            return response($trip, 200);

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
     * Display the specified resource.
     *
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function show(Trip $trip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function edit(Trip $trip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trip $trip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trip $trip)
    {
        //
    }
}
