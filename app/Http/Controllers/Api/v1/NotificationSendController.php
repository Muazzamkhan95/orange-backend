<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationSendController extends Controller
{
    public function updateDeviceToken(Request $request)
    {
        Auth::user()->device_token =  $request->token;

        Auth::user()->save();

        return response()->json(['Token successfully stored.']);
    }

    public function updateToken(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->device_token = $request->device_token;
        $user->update();
        return response('Token Update', 200);
    }
    public function sendNotification(Request $request)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = $request->device_token;

        // $FcmToken = User::whereNotNull('device_token')->pluck('device_token')->all();

        $serverKey = 'AAAA4PcRZoA:APA91bFflUkD9wMurjOEBylgRkFaNJf6jjqQDFXzGLHLs-ZBrdxgqd0bzSaPdUPpL7GwS0CVjzunI4e7gNk25kJg67Fz1aatYV3gEzGIeN3hhkA4yCvtlBY0vk0doChhyQc72Id7pDxx'; // ADD SERVER KEY HERE PROVIDED BY FCM

        $data = [
            "to"=> $FcmToken,
            "notification"=> [
                "title" => "Notification Title",
                "body" => "Notification Body"
            ],
            "data" => [
                "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
                "trip_id"=> $request->trip_id,
                "is_schedule"=> $request->is_schedule,
                "key"=> "Image Update" //eg: "image_update"
                ]
        ];
        $encodedData = json_encode($data);

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
        // // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        return response($data, 200);
    }
}
