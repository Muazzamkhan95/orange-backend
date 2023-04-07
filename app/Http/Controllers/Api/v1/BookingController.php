<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DeclineTrip;
use App\Models\Driver;
use App\Models\DriverStatus;
use App\Models\DriverToCustomerLocation;
use App\Models\PromoCodes;
use App\Models\ServiceDetail;
use App\Models\ServiceType;
use App\Models\Trip;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function booking(Request $request){
        // dd($request->all());

        $data = new Trip();
        $data->pickup = $request->pickup;
        $data->pickup_lat = $request->pickup_lat;
        $data->pickup_lag = $request->pickup_lag;

        $data->destination = $request->destination;
        $data->destination_lat = $request->destination_lat;
        $data->destination_lag = $request->destination_lag;

        $data->status = 1;
        $data->driver_id = 1;
        $data->customer_id = $request->customer_id;
        $data->car_id = $request->car_id;
        $data->payment_method_id = $request->payment_method_id;
        $data->service_type_id = $request->service_type_id;
        $data->service_detail_id = $request->service_detail_id;
        $data->notes = $request->notes;
        $data->time = $request->time;
        $data->distance = $request->distance;

        $data->amount = $request->amount;
        $data->discount_price = 0;
        $data->total_price = 0;
        $data->hours_count = $request->hours_count;
        $data->device_token = $request->device_token;
        $data->promo_code_id  = $request->promo_code_id ;

        // sechudule
        if($request->isSchedule == 1){
            $data->isSchedule = $request->isSchedule;
        } else {
            $data->isSchedule = 0;
        }
        $data->time_of_schedule_booking = $request->time_of_schedule_booking;
        $data->date_of_schedule_booking = $request->date_of_schedule_booking;

        // return response($data, 200);
        $data->save();
        // $FcmToken ='';
        if($request->isSchedule != 1){
            $driverstatus = DriverStatus::where('status', 1)->get();
            sleep(10);
            foreach($driverstatus as $ds){
                $FcmToken = $ds->device_token;
                $url = 'https://fcm.googleapis.com/fcm/send';

                $serverKey = 'AAAA4PcRZoA:APA91bFflUkD9wMurjOEBylgRkFaNJf6jjqQDFXzGLHLs-ZBrdxgqd0bzSaPdUPpL7GwS0CVjzunI4e7gNk25kJg67Fz1aatYV3gEzGIeN3hhkA4yCvtlBY0vk0doChhyQc72Id7pDxx'; // ADD SERVER KEY HERE PROVIDED BY FCM

                $body = [
                    "pickup_lat" => $data->pickup_lat,
                    "pickup_lag" => $data->pickup_lat,
                    "destination_lat" => $data->destination_lat,
                    "destination_lag" => $data->destination_lag
                ];
                $data1 = [
                    "to"=> $FcmToken,
                    "notification"=> [
                        "title" => "New booking request received.",
                        "body" => "Please review and confirm your availability"
                    ],
                    "data" => [
                        "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
                        "key"=> "Image Update" //eg: "image_update"
                        ]
                ];
                // $data1 = [
                //     "to" => $FcmToken,
                //     "data" => [
                //         "body" => $body,
                //     ]
                // ];
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
            }
        }


        return response($data, 200);
    }
    public function bookingStatus(Request $request){

        $trip_id = $request->id;
        // dd($trip_id);
        $trip = Trip::find($trip_id);
        if($trip === null){
            return response('Ride Cancel', 200);
        } else {
            $FcmToken = $trip->device_token;
            // dd($FcmToken);
            $url = 'https://fcm.googleapis.com/fcm/send';

            $serverKey = 'AAAA4PcRZoA:APA91bFflUkD9wMurjOEBylgRkFaNJf6jjqQDFXzGLHLs-ZBrdxgqd0bzSaPdUPpL7GwS0CVjzunI4e7gNk25kJg67Fz1aatYV3gEzGIeN3hhkA4yCvtlBY0vk0doChhyQc72Id7pDxx'; // ADD SERVER KEY HERE PROVIDED BY FCM
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

            $customer = Customer::where('id', $trip->customer_id)->first();

            if($request->status == 1){
                $trip->status = 1;
                $trip->update();
                return response('Pending', 200);
            } elseif($request->status == 2){
                ////////////// Driver Accept Status /////////////////////////////
                if($trip->status == 2){
                    return response('Someone Already Accepted', 200);
                } else {
                    if($trip->status == 6){
                        return response('Ride Cancel By User', 200);
                    } else {
                        $trip->status = 2;
                        $trip->driver_id = $request->driver_id;
                        $trip->update();
                        // dd($customer);
                        $driver_id = $request->driver_id;
                        $driver_Status = DriverStatus::where('driver_id', $driver_id)->first();
                        $driver_Status->status = $trip->status;
                        $driver_Status->update();

                        $drivertocustomer = DriverToCustomerLocation::where('driver_id', $driver_id)->first();
                        if($drivertocustomer){
                            $drivertocustomer->lat = $request->lat;
                            $drivertocustomer->lag = $request->lag;
                            $drivertocustomer->driver_id = $request->driver_id;
                            $drivertocustomer->update();
                        } else {
                            $drivertocustomer =new DriverToCustomerLocation();
                            $drivertocustomer->lat = $request->lat;
                            $drivertocustomer->lag = $request->lag;
                            $drivertocustomer->driver_id = $request->driver_id;
                            $drivertocustomer->save();
                        }

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
                        $promocode1 = PromoCodes::find($trip->promo_code_id);
                        if($promocode1){
                            // dd($promocode1);
                            $promocode = [
                                "id" => $promocode1->id,
                                "code" => (string)$promocode1->code,
                                "discount" => (double)$promocode1->discount,
                                "valid_from" => $promocode1->valid_from,
                                "valid_to" => $promocode1->valid_to
                            ];
                        } else {
                            $promocode =  [
                                "id" => 0,
                                "code" => "",
                                "discount" => (double) 0,
                                "valid_from" => "",
                                "valid_to" => ""
                            ];
                        }

                        $data=[
                            'id'=> $trip->id,
                            'pickup'=> $trip->pickup,
                            'pickup_lat'=> (string)$trip->pickup_lat,
                            'pickup_lag'=> (string)$trip->pickup_lag,
                            'destination'=> (string)$trip->destination,
                            'destination_lat'=> (string)$trip->destination_lat,
                            'destination_lag'=> (string)$trip->destination_lag,
                            'status'=> (int)$trip->status,
                            'amount'=> (string)$trip->amount,
                            'hours_count'=> (string)$trip->hours_count,
                            'time'=> (string)$trip->time,
                            'distance'=> (string)$trip->distance,
                            "customer_name"=> (string)$customer->name,
                            "customer_user_id"=> (int)$customer->user_id,
                            "serviceType"=> $servicedata,
                            "serviceDetail"=> $serviceDetail,
                            "promo_code"=>  $promocode,
                        ];
                        if( $trip->isSchedule == 1){
                            $data1 = [
                                "to"=> $FcmToken,
                                "notification"=> [
                                    "title" => "Your Schedule Ride request has been accepted!",
                                    "body" => "Driver will be arriving shortly, on time around [".$trip->time_of_schedule_booking."]."
                                ],
                                "data" => [
                                    "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
                                    "trip_id"=> $trip->id,
                                    "is_schedule"=> 1,
                                    "key"=> "Image Update" //eg: "image_update"
                                    ]
                            ];
                        } else {
                            $data1 = [
                                "to"=> $FcmToken,
                                "notification"=> [
                                    "title" => "Your Ride request has been accepted!",
                                    "body" => "Your driver will be arriving shortly."
                                ],
                                "data" => [
                                    "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
                                    "key"=> "Image Update" //eg: "image_update"
                                    ]
                            ];
                        }


                        $encodedData = json_encode($data1);

                        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
                        // Execute post
                        $result = curl_exec($ch);
                        if ($result === FALSE) {
                            die('Curl failed: ' . curl_error($ch));
                        }
                        // Close connection
                        curl_close($ch);
                        return response($data, 200);
                    }
                }
            } elseif($request->status == 3){
               ////////////// Driver Arrived Status /////////////////////////////

                $trip->driver_id = $request->driver_id;
                $trip->status = 3;
                $trip->update();
                // dd($customer);
                $driver_id = $request->driver_id;
                $drivertocustomer = DriverToCustomerLocation::where('driver_id', $driver_id)->first();
                $drivertocustomer->lat = $request->lat;
                $drivertocustomer->lag = $request->lag;
                $drivertocustomer->driver_id = $request->driver_id;
                $drivertocustomer->update();

                if( $trip->isSchedule == 1){
                    $data1 = [
                        "to"=> $FcmToken,
                        "notification"=> [
                            "title" => "Driver Arrived",
                            "body" => "Your driver has arrived at the pickup location."
                        ],
                        "data" => [
                            "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
                            "trip_id"=> $trip->id,
                            "is_arrived"=> 1,
                            "key"=> "Image Update" //eg: "image_update"
                            ]
                    ];
                } else {
                $data1 = [
                    "to"=> $FcmToken,
                    "notification"=> [
                        "title" => "Driver Arrived",
                        "body" => "Your driver has arrived at the pickup location."
                    ],
                    "data" => [
                        "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
                        "key"=> "Image Update" //eg: "image_update"
                        ]
                ];
                }
                $encodedData = json_encode($data1);

                curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
                // Execute post
                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('Curl failed: ' . curl_error($ch));
                }
                // Close connection
                curl_close($ch);
                return response('Arrived', 200);
            } elseif($request->status == 4){
                ////////////// Driver start trip Status /////////////////////////////
                // dd($request->all());
                $trip->status = 4;
                $trip->update();
                $driver_id = $request->driver_id;
                $drivertocustomer = DriverToCustomerLocation::where('driver_id', $driver_id)->first();
                $drivertocustomer->lat = $request->lat;
                $drivertocustomer->lag = $request->lag;
                $drivertocustomer->driver_id = $request->driver_id;
                $drivertocustomer->update();

                if( $trip->isSchedule == 1){
                    $data1 = [
                        "to"=> $FcmToken,
                        "notification"=> [
                            "title" => "Your Ride is in progress.",
                            "body" => "Sit back, relax, and enjoy the journey."
                        ],
                        "data" => [
                            "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
                            "trip_id"=> $trip->id,
                            "is_start"=> 1,
                            "key"=> "Image Update" //eg: "image_update"
                            ]
                    ];
                } else {
                    $data1 = [
                        "to"=> $FcmToken,
                        "notification"=> [
                            "title" => "Your Ride is in progress.",
                            "body" => "Sit back, relax, and enjoy the journey."
                        ],
                        "data" => [
                            "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
                            "key"=> "Image Update" //eg: "image_update"
                            ]
                    ];
                }
                $encodedData = json_encode($data1);

                curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
                // Execute post
                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('Curl failed: ' . curl_error($ch));
                }
                // Close connection
                curl_close($ch);
                return response('inprogress', 200);
            } elseif($request->status == 5){
                // dd($request->amount);
                $trip->amount = $request->amount;
                $trip->discount_price = $request->discount_price;
                $trip->total_price = $request->total_price;
                $trip->time = $request->time;
                $trip->destination = $request->destination;
                $trip->destination_lat = $request->destination_lat;
                $trip->destination_lag = $request->destination_lag;
                $trip->distance = $request->distance;
                $trip->timeofendtrip = $request->timeofendtrip;
                $trip->payment_method_id = 1;
                $trip->status = 5;

                $trip->update();

                $driver_Status = DriverStatus::where('driver_id', $trip->driver_id)->first();
                $driver_Status->status = 1;
                $driver_Status->update();

                $data1 = [
                    "to"=> $FcmToken,
                    "notification"=> [
                        "title" => "Your Ride is complete.",
                        "body" => "The total fare for your trip is [".$trip->amount."]"
                    ],
                    "data" => [
                        "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
                        "key"=> "Image Update" //eg: "image_update"
                        ]
                ];
                $encodedData = json_encode($data1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
                // Execute post
                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('Curl failed: ' . curl_error($ch));
                }
                // Close connection
                curl_close($ch);
                $tripdata=[
                    'id'=> $trip->id,
                    'pickup'=> $trip->pickup,
                    'pickup_lat'=> (string)$trip->pickup_lat,
                    'pickup_lag'=> (string)$trip->pickup_lag,
                    'destination'=> $trip->destination,
                    'destination_lat'=> $trip->destination_lat,
                    'destination_lag'=> $trip->destination_lag,
                    'status'=> (int)$trip->status,
                    'amount'=> $trip->amount,
                    'discount_price'=> (double)$trip->discount_price,
                    'total_price'=> (double)$trip->total_price,
                    'hours_count'=> (string)$trip->hours_count,
                    "driver_id"=> (string)$trip->driver_id,
                    "customer_id"=> (string)$trip->customer_id,
                    "car_id"=> (string)$trip->car_id,
                    "payment_method_id"=> (string)$trip->payment_method_id,
                    "service_type_id"=> (string)$trip->service_type_id,
                    "service_detail_id"=> (string)$trip->service_detail_id,
                    'time'=> (string)$trip->time,
                    'distance'=> (string)$trip->distance,
                    'notes'=> (string)$trip->notes,
                    'created_at'=> $trip->created_at,
                    'updated_at'=> $trip->updated_at,
                    'device_token'=> $trip->device_token,
                    'timeofendtrip'=> $trip->timeofendtrip,
                    'promo_code_id'=> (int)$trip->promo_code_id,
                ];
                return response($tripdata, 200);
            } elseif($request->status == 6){
                $trip->delete();
                return response('Trip Delete', 200);
            } else {
                $trip->status = 0;
                $trip->update();

                $data1 = [
                    "to"=> $FcmToken,
                    "notification"=> [
                        "title" => "Trip Cancel",
                        "body" => "Trip Cancel"
                    ],
                    "data" => [
                        "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
                        "key"=> "Image Update" //eg: "image_update"
                        ]
                ];
                $encodedData = json_encode($data1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
                // Execute post
                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('Curl failed: ' . curl_error($ch));
                }
                // Close connection
                curl_close($ch);
                return response('Cancel', 200);
            }
        }

    }
    public function cashOrCard(Request $request){
        if($request->payment_method_id == 1){
            $wallet =new Wallet();
            $wallet->amount = $request->total_price;
            $wallet->driver_id = $request->driver_id;
            // $wallet->trip_id = $request->trip_id;
            $wallet->save();
            return response($wallet, 200);
        } else {
            return response('Payment Type Card or Other', 200);
        }

    }
    public function bookingAcceptStatus(Request $request){
        $trip_id = $request->id;
        $trip = Trip::where('id', $trip_id)->first();

        return response();

    }
    public function getBookingDetails($id){
        // $id = $request->current_trip_id;
        // dd($id);
        $tripDetail = Trip::where('id', '=', $id)->where('status', 2)->where('driver_id', '!=' , 1)->first();
        // dd($tripDetail);

        $driver_id = $tripDetail->driver_id;
        $driverlocation = DriverToCustomerLocation::where('driver_id', $driver_id)->first();
        // dd($driverlocation);
        $tripDetails= [
            "id"=> $tripDetail->id,
            "pickup"=> (string)$tripDetail->pickup,
            "pickup_lat"=> (string)$tripDetail->pickup_lat,
            "pickup_lag"=> (string)$tripDetail->pickup_lag,
            "destination"=> (string)$tripDetail->destination,
            "destination_lat"=> (string)$tripDetail->destination_lat,
            "destination_lag"=> (string)$tripDetail->destination_lag,
            "driver"=> [
                "id"=> $tripDetail->driver->id,
                "name"=> (string)$tripDetail->driver->name,
                "phone"=> (string)$tripDetail->driver->phone,
                "email"=> (string)$tripDetail->driver->email,
                "profile_image"=> (string)$tripDetail->driver->profile_image,
                "user_id"=> (string)$tripDetail->driver->user_id,
            ],
            "driverlocation"=> [
                "id"=> $driverlocation->id,
                "driver_id"=> (string)$driverlocation->driver_id,
                "lat"=> (string)$driverlocation->lat,
                "lag"=> (string)$driverlocation->lag
            ]
        ];
        return response($tripDetails , 200);
    }
    public function getScheduleBookingDetails($id){
        // $id = $request->current_trip_id;
        // dd($id);
        $tripDetail = Trip::where('id', '=', $id)->where('driver_id', '!=' , 1)->first();
        // dd($tripDetail);

        $driver_id = $tripDetail->driver_id;
        $driverlocation = DriverToCustomerLocation::where('driver_id', $driver_id)->first();
        // dd($driverlocation);
        $tripDetails= [
            "id"=> $tripDetail->id,
            "pickup"=> (string)$tripDetail->pickup,
            "pickup_lat"=> (string)$tripDetail->pickup_lat,
            "pickup_lag"=> (string)$tripDetail->pickup_lag,
            "destination"=> (string)$tripDetail->destination,
            "destination_lat"=> (string)$tripDetail->destination_lat,
            "destination_lag"=> (string)$tripDetail->destination_lag,
            "driver"=> [
                "id"=> $tripDetail->driver->id,
                "name"=> (string)$tripDetail->driver->name,
                "phone"=> (string)$tripDetail->driver->phone,
                "email"=> (string)$tripDetail->driver->email,
                "profile_image"=> (string)$tripDetail->driver->profile_image,
                "user_id"=> (string)$tripDetail->driver->user_id,
            ],
            "driverlocation"=> [
                "id"=> $driverlocation->id,
                "driver_id"=> (string)$driverlocation->driver_id,
                "lat"=> (string)$driverlocation->lat,
                "lag"=> (string)$driverlocation->lag
            ]
        ];
        return response($tripDetails , 200);
    }
    public function getCompleteBookingDetails($id){
        // $id = $request->current_trip_id;
        // dd($id);
        $tripDetail = Trip::where('id', '=', $id)->where('status', 5)->where('driver_id', '!=' , 1)->first();
        // dd($tripDetail);

        $driver_id = $tripDetail->driver->id;
        $driverlocation = DriverToCustomerLocation::where('driver_id', $driver_id)->first();
        // dd($driverlocation);
        $serviceType = ServiceType::find($tripDetail->service_type_id);
        $servicedata = [
            "id"=> $serviceType->id ,
            "name"=> $serviceType->name ,
            "image"=> $serviceType->image ,
            "status"=> (string)$serviceType->status ,
            "created_at"=> $serviceType->created_at,
            "updated_at"=> $serviceType->updated_at,

        ];
        $serviceDetails = ServiceDetail::find($tripDetail->service_detail_id);
        $serviceDetail = [
            "id"=> $serviceDetails->id,
            "name"=> $serviceDetails->name,
            "booking_fee"=> (string)$serviceDetails->booking_fee,
            "rate"=> (string)$serviceDetails->rate,
            "rate_cal"=> (string)$serviceDetails->rate_cal
        ];
        $tripDetails= [
            "id"=> $tripDetail->id,
            "pickup"=> $tripDetail->pickup,
            "pickup_lat"=> (string)$tripDetail->pickup_lat,
            "pickup_lag"=> (string)$tripDetail->pickup_lag,
            "destination"=> $tripDetail->destination,
            "destination_lat"=> (string)$tripDetail->destination_lat,
            "destination_lag"=> (string)$tripDetail->destination_lag,
            "amount"=> (string)$tripDetail->amount,
            'discount_price'=> (double)$tripDetail->discount_price,
            'total_price'=> (double)$tripDetail->total_price,
            "time"=> (string)$tripDetail->time,
            "date"=> $tripDetail->updated_at->format('d-m-Y'),
            "timeofendtrip"=> (string)$tripDetail->timeofendtrip,
            "serviceType"=> $servicedata,
            "serviceDetail"=> $serviceDetail
        ];
        return response($tripDetails , 200);
    }

    public function getBookingArrivedStatus($id){
        $trip = Trip::find($id);
        if($trip->status == 3){
            $tripstatus = $trip->status;
            return response($tripstatus, 200);
        } else {
            $tripstatus = $trip->status;
            return response($tripstatus, 200);
        }
    }
    public function getBookingInprogressStatus($id){
        $trip = Trip::find($id);
        if($trip->status == 4){
            $tripstatus = $trip->status;
            return response($tripstatus, 200);
        } else {
            $tripstatus = $trip->status;
            return response($tripstatus, 200);
        }
    }
    public function getBookingCompeletStatus($id){
        $trip = Trip::find($id);
        if($trip->status == 5){
            $tripstatus = $trip->status;
            $tripData = [
                "id"=> $trip->id,
                "pickup"=> (string)$trip->pickup ,
                "pickup_lat"=> (string)$trip->pickup_lat ,
                "pickup_lag"=> (string)$trip->pickup_lag ,
                "destination"=> (string)$trip->destination ,
                "destination_lat"=> (string)$trip->destination_lat ,
                "destination_lag"=> (string)$trip->destination_lag ,
                "status"=> (string)$trip->status ,
                "amount"=> (string)$trip->amount ,
                "hours_count"=> (string)$trip->hours_count ,
                "driver_id"=> (string)$trip->driver_id ,
                "customer_id"=> (string)$trip->customer_id ,
                "car_id"=> (string)$trip->car_id ,
                "payment_method_id"=> (string)$trip->payment_method_id ,
                "service_type_id"=> (string)$trip->service_type_id ,
                "service_detail_id"=> (string)$trip->service_detail_id ,
                "time"=> (string)$trip->time ,
                "distance"=> (string)$trip->distance ,
                "notes"=> (string)$trip->notes ,
                "created_at"=> (string)$trip->created_at ,
                "updated_at"=> (string)$trip->updated_at ,
                "device_token"=> (string)$trip->device_token ,
                "timeofendtrip"=> (string)$trip->timeofendtrip,
                "isSchedule"=> $trip->isSchedule,
                "time_of_schedule_booking"=> (string)$trip->time_of_schedule_booking,
                "date_of_schedule_booking"=> (string)$trip->date_of_schedule_booking,
                "promo_code_id"=> $trip->promo_code_id,
            ];
            return response($tripData, 200);
        } else {
            $tripstatus = $trip->status;
            return response($tripstatus, 200);
        }
    }

    public function bookingStatusCancel(Request $request){
        $data = Trip::find($request->id)->first();
            $data->status = 0;
            $data->update();
            return response('Cancel', 200);
    }
    public function driverDeclineBooking(Request $request){
        $data =new DeclineTrip();
        $data->trip_id= $request->trip_id;
        // $data->isDecline= $request->isDecline;
        $data->driver_id= $request->driver_id;
        $data->save();
        return response('Trip Decline', 200);
    }
    public function getPendingBooking($id){
        // $data = Trip::
        // $decline = DeclineTrip::all();
        // dd($decline);
        $driver_check = Driver::find($id);

        if($driver_check->status === 3 ){
           return response('Driver Account Block', 200);
        } else {
            $data = Trip::with('car', 'customer', 'driver',
            'paymentMethod', 'serviceType')->where('status', 1)->where('isSchedule', 0)->get();
            // dd($data);
            $arr=array();
            // dd($data->count());
            foreach($data as $d){
                // dd($d);
                $declinetrips = DeclineTrip::where('driver_id', $id)->where('trip_id', $d->id)->first();
                // dd($declinetrips);
                if($declinetrips){
                } else {
                    $promocode1 = PromoCodes::find($d->promo_code_id);
                    if($promocode1){
                        // dd($promocode1);
                        $promocode = [
                            "id" => $promocode1->id,
                            "code" => (string)$promocode1->code,
                            "discount" => (double)$promocode1->discount,
                            "valid_from" => $promocode1->valid_from,
                            "valid_to" => $promocode1->valid_to
                        ];
                    } else {
                        $promocode =  [
                            "id" => 0,
                            "code" => "",
                            "discount" => (double) 0,
                            "valid_from" => "",
                            "valid_to" => ""
                        ];
                    }
                    $pendingTrip = [
                        "id"=> $d->id,
                        "pickup"=> $d->pickup,
                        "pickup_lat"=> (string)$d->pickup_lat,
                        "pickup_lag"=> (string)$d->pickup_lag,
                        "destination"=> $d->destination,
                        "destination_lat"=> (string)$d->destination_lat,
                        "destination_lag"=> (string)$d->destination_lag,
                        "status"=> (string)$d->status,
                        "amount"=> (string)$d->amount,
                        "hours_count"=> (string)$d->hours_count,
                        "driver_id"=> (string)$d->driver_id,
                        "customer_id"=> (string) $d->customer_id,
                        "car_id"=> (string)$d->car_id,
                        "payment_method_id"=> (string)$d->payment_method_id,
                        "service_type_id"=> (string)$d->service_type_id,
                        "service_detail_id"=> (string)$d->service_detail_id,
                        "time"=> $d->time,
                        "distance"=> $d->distance,
                        "notes"=> $d->notes,
                        "created_at"=> $d->created_at,
                        "updated_at"=> $d->updated_at,
                        "device_token"=> $d->device_token,
                        "timeofendtrip"=> $d->timeofendtrip,
                        "promo_code"=>  $promocode,
                        // "isSchedule"=> $d->isSchedule,
                        // "time_of_schedule_booking"=> $d->time_of_schedule_booking,
                        // "date_of_schedule_booking"=> $d->date_of_schedule_booking,
                        "car"=> [
                            "id"=> $d->car->id,
                            "name"=> $d->car->name,
                            "plate_code"=> (string)$d->car->plate_code,
                            "plate_number"=> (string)$d->car->plate_number,
                            "transmission_type"=> (string)$d->car->transmission_type,
                            "comprehensive_insurance"=> (string)$d->car->comprehensive_insurance,
                            "status"=> (string)$d->car->status,
                            "color"=> $d->car->color,
                            "customer_id"=> (string)$d->car->customer_id,
                            "state_id"=> (string)$d->car->state_id,
                            "brand_id"=> (string)$d->car->brand_id,
                            "created_at"=> $d->car->created_at,
                            "updated_at"=> $d->car->updated_at
                        ],
                        "customer"=> [
                            "id"=> $d->customer->id,
                            "user_id"=> (string)$d->customer->user_id,
                            "name"=> $d->customer->name,
                            "email"=> $d->customer->email,
                            "phone"=> $d->customer->phone,
                            "dob"=> $d->customer->dob,
                            "gender"=> $d->customer->gender,
                            "status"=> (string)$d->customer->status,
                            "profile_image"=> $d->customer->profile_image,
                            "created_at"=> $d->customer->created_at,
                            "updated_at"=> $d->customer->updated_at
                        ],
                        "driver"=> [
                            "id"=> $d->driver->id,
                            "name"=> $d->driver->name,
                            "phone"=> (string)$d->driver->phone,
                            "email"=> $d->driver->email,
                            "profile_image"=> $d->driver->profile_image,
                            "user_id"=> (string)$d->driver->user_id,
                            "cv_file"=> $d->driver->cv_file,
                            "cnic"=> $d->driver->cnic,
                            "lic"=> $d->driver->lic,
                            "cnic_front"=> $d->driver->cnic_front,
                            "cnic_back"=> $d->driver->cnic_back,
                            "lic_front"=> $d->driver->lic_front,
                            "lic_back"=> $d->driver->lic_back,
                            "rider_role"=> (string)$d->driver->rider_role,
                            "status"=> (string)$d->driver->status,
                            "created_at"=> $d->driver->created_at,
                            "updated_at"=> $d->driver->updated_at

                        ],
                        "payment_method"=> [
                            "id"=> $d->paymentMethod->id,
                            "name"=> $d->paymentMethod->name,
                            "created_at"=> $d->paymentMethod->created_at,
                            "updated_at"=> $d->paymentMethod->updated_at

                        ],
                        "service_type"=> [
                            "id"=> $d->serviceType->id,
                            "name"=> $d->serviceType->name,
                            "image"=> $d->serviceType->image,
                            "status"=> (string)$d->serviceType->status,
                            "created_at"=> $d->serviceType->created_at,
                            "updated_at"=> $d->serviceType->updated_at

                        ]
                    ];
                    array_push($arr, $pendingTrip);
                }
            }

            // dd($arr);
            return response($arr, 200);
        }
    }
    public function getBookingCompleteStatus(){

    }
    public function getAcceptBookingDetail(Request $request){

    }
    public function getAcceptedBooking(Request $request){

        $trip = Trip::where('id', $request->id)->where('');

        return response($trip, 200);
    }
    public function getScheduleBooking($id){
        $trips = Trip::where('customer_id', $id)->where('status', 1)->where('isSchedule', 1)->get();
        $array = array();
        foreach($trips as $trip){
            $tripdata=[
                'id'=> $trip->id,
                'isSchedule'=> (int)$trip->isSchedule,
                'pickup'=> (string)$trip->pickup,
                'pickup_lat'=> (double)$trip->pickup_lat,
                'pickup_lag'=> (double)$trip->pickup_lag,
                'destination'=> (string)$trip->destination,
                'destination_lat'=> (double)$trip->destination_lat,
                'destination_lag'=> (double)$trip->destination_lag,
                'status'=> (int)$trip->status,
                'amount'=> (double)$trip->amount,
                'hours_count'=> (int)$trip->hours_count,
                'time'=> (string)$trip->time,
                'distance'=> (string)$trip->distance,
                'date_of_schedule_booking'=> (string)$trip->date_of_schedule_booking,
                'time_of_schedule_booking'=> (string)$trip->time_of_schedule_booking,
            ];
            array_push($array, $tripdata);
        }
        return response($array, 200);
    }

    public function getDriverScheduleBooking($id){
        $trips = Trip::where('driver_id', $id)->where('status', 1)->where('isSchedule', 1)->get();
        $array_data = array();
        foreach($trips as $trip){
            $tripdata=[
                'id'=> $trip->id,
                'isSchedule'=> (int)$trip->isSchedule,
                'pickup'=> (string)$trip->pickup,
                'pickup_lat'=> (double)$trip->pickup_lat,
                'pickup_lag'=> (double)$trip->pickup_lag,
                'destination'=> (string)$trip->destination,
                'destination_lat'=> (double)$trip->destination_lat,
                'destination_lag'=> (double)$trip->destination_lag,
                'status'=> (int)$trip->status,
                'amount'=> (double)$trip->amount,
                'hours_count'=> (int)$trip->hours_count,
                'time'=> (string)$trip->time,
                'distance'=> (string)$trip->distance,
                'date_of_schedule_booking'=> (string)$trip->date_of_schedule_booking,
                'time_of_schedule_booking'=> (string)$trip->time_of_schedule_booking,
            ];
            array_push($array_data, $tripdata);
        }
        return response($array_data, 200);
    }
    public function CheckTripStatus(Request $request)
    {
        $trip = Trip::where('id', $request->id)->where('driver_id', $request->driver_id)->first();
        // $trip = Trip::find($request->id);
        if( $trip === null ){
            return response('No Record Found', 200);
        } else {
            $customer = Customer::find($trip->customer_id);
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
                $promocode1 = PromoCodes::find($trip->promo_code_id);
                if($promocode1){
                    // dd($promocode1);
                    $promocode = [
                        "id" => $promocode1->id,
                        "code" => (string)$promocode1->code,
                        "discount" => (double)$promocode1->discount,
                        "valid_from" => $promocode1->valid_from,
                        "valid_to" => $promocode1->valid_to
                    ];
                } else {
                    $promocode =  [
                        "id" => 0,
                        "code" => "",
                        "discount" => (double) 0,
                        "valid_from" => "",
                        "valid_to" => ""
                    ];
                }

                $data=[
                    'id'=> $trip->id,
                    'pickup'=> $trip->pickup,
                    'pickup_lat'=> (string)$trip->pickup_lat,
                    'pickup_lag'=> (string)$trip->pickup_lag,
                    'destination'=> (string)$trip->destination,
                    'destination_lat'=> (string)$trip->destination_lat,
                    'destination_lag'=> (string)$trip->destination_lag,
                    'status'=> (int)$trip->status,
                    'amount'=> (string)$trip->amount,
                    'hours_count'=> (string)$trip->hours_count,
                    'time'=> (string)$trip->time,
                    'distance'=> (string)$trip->distance,
                    "customer_name"=> (string)$customer->name,
                    "customer_user_id"=> (int)$customer->user_id,
                    "serviceType"=> $servicedata,
                    "serviceDetail"=> $serviceDetail,
                    "promo_code"=>  $promocode,
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
                $promocode1 = PromoCodes::find($trip->promo_code_id);
                if($promocode1){
                    // dd($promocode1);
                    $promocode = [
                        "id" => $promocode1->id,
                        "code" => (string)$promocode1->code,
                        "discount" => (double)$promocode1->discount,
                        "valid_from" => $promocode1->valid_from,
                        "valid_to" => $promocode1->valid_to
                    ];
                } else {
                    $promocode =  [
                        "id" => 0,
                        "code" => "",
                        "discount" => (double) 0,
                        "valid_from" => "",
                        "valid_to" => ""
                    ];
                }

                $data=[
                    'id'=> $trip->id,
                    'pickup'=> $trip->pickup,
                    'pickup_lat'=> (string)$trip->pickup_lat,
                    'pickup_lag'=> (string)$trip->pickup_lag,
                    'destination'=> (string)$trip->destination,
                    'destination_lat'=> (string)$trip->destination_lat,
                    'destination_lag'=> (string)$trip->destination_lag,
                    'status'=> (int)$trip->status,
                    'amount'=> (string)$trip->amount,
                    'hours_count'=> (string)$trip->hours_count,
                    'time'=> (string)$trip->time,
                    'distance'=> (string)$trip->distance,
                    "customer_name"=> (string)$customer->name,
                    "customer_user_id"=> (int)$customer->user_id,
                    "serviceType"=> $servicedata,
                    "serviceDetail"=> $serviceDetail,
                    "promo_code"=>  $promocode,
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
                $promocode1 = PromoCodes::find($trip->promo_code_id);
                if($promocode1){
                    // dd($promocode1);
                    $promocode = [
                        "id" => $promocode1->id,
                        "code" => (string)$promocode1->code,
                        "discount" => (double)$promocode1->discount,
                        "valid_from" => $promocode1->valid_from,
                        "valid_to" => $promocode1->valid_to
                    ];
                } else {
                    $promocode =  [
                        "id" => 0,
                        "code" => "",
                        "discount" => (double) 0,
                        "valid_from" => "",
                        "valid_to" => ""
                    ];
                }

                $data=[
                    'id'=> $trip->id,
                    'pickup'=> $trip->pickup,
                    'pickup_lat'=> (string)$trip->pickup_lat,
                    'pickup_lag'=> (string)$trip->pickup_lag,
                    'destination'=> (string)$trip->destination,
                    'destination_lat'=> (string)$trip->destination_lat,
                    'destination_lag'=> (string)$trip->destination_lag,
                    'status'=> (int)$trip->status,
                    'amount'=> (string)$trip->amount,
                    'hours_count'=> (string)$trip->hours_count,
                    'time'=> (string)$trip->time,
                    'distance'=> (string)$trip->distance,
                    "customer_name"=> (string)$customer->name,
                    "customer_user_id"=> (int)$customer->user_id,
                    "serviceType"=> $servicedata,
                    "serviceDetail"=> $serviceDetail,
                    "promo_code"=>  $promocode,
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
    public function screentoDisplay($trip_id)
    {
        $trip = Trip::find($trip_id);
        if( $trip === null ){
            return response('No Record Found', 200);
        } else {
            if($trip->status == 1){
                return response($trip->status, 200);
            } elseif($trip->status == 2){
                // $driver_id = $trip->driver->id;
                // $driverlocation = DriverToCustomerLocation::where('driver_id', $driver_id)->first();

                // $data= [
                //     "id"=> $trip->id,
                //     "status"=> $trip->status,
                //     "pickup"=> (string)$trip->pickup,
                //     "pickup_lat"=> (string)$trip->pickup_lat,
                //     "pickup_lag"=> (string)$trip->pickup_lag,
                //     "destination"=> (string)$trip->destination,
                //     "destination_lat"=> (string)$trip->destination_lat,
                //     "destination_lag"=> (string)$trip->destination_lag,
                //     "driver"=> [
                //         "id"=> $trip->driver->id,
                //         "name"=> (string)$trip->driver->name,
                //         "phone"=> (string)$trip->driver->phone,
                //         "email"=> (string)$trip->driver->email,
                //         "profile_image"=> (string)$trip->driver->profile_image,
                //         "user_id"=> (string)$trip->driver->user_id,
                //     ],
                //     "driverlocation"=> [
                //         "id"=> $driverlocation->id,
                //         "driver_id"=> (string)$driverlocation->driver_id,
                //         "lat"=> (string)$driverlocation->lat,
                //         "lag"=> (string)$driverlocation->lag
                //     ]
                // ];
                return response($trip->status, 200);
            } elseif($trip->status == 3){
                $tripstatus = $trip->status;
                return response($tripstatus, 200);
            } elseif($trip->status == 4){
                $tripstatus = $trip->status;
                return response($tripstatus, 200);
            } elseif($trip->status == 5){
                $tripstatus = $trip->status;
                return response($tripstatus, 200);
            } else {
                return response(0, 200);
            }
        }
    }

}
