<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Rating;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    //
    public function getDriverRating($id){
        $rating = Rating::with('driver')->where('driver_id' , $id)->get();

        // $rating1 = Rating::groupBy('customer_id');
        // dd($rating1);
        $totalRatingCount = $rating->count();
        $totalAverageRating = $rating->avg('rating');
        $totalTrip = Trip::where('driver_id' , $id)->count();
        // dd($rat);
        // dd($rating);
        $ratingcount = DB::table('ratings')
            ->selectRaw('customer_id, count(*) as count')
            ->groupBy('customer_id')->where('driver_id' , $id)
            ->get();
        $arr = array();
        foreach($ratingcount as $rate){
            $customerName = Customer::where('id', $rate->customer_id)->first();
            $customerImage = Customer::where('id', $rate->customer_id)->first();

            $ratingcount1 = Rating::where('customer_id', $rate->customer_id)->avg('rating');
            $ratedata = [
                "customerName"=> $customerName->name,
                "profileImage"=> $customerImage->profile_image,
                "totalRideRate"=> (int)$rate->count,
                "eachUserAvg"=> round($ratingcount1, 1),
            ];
            array_push($arr, $ratedata);
        }
        $dataReturn = [
            "totalRatingCount"=> $totalRatingCount,
            "totalAverageRating"=> round($totalAverageRating, 1),
            "totalTrip"=> $totalTrip,
            "ratingList"=> $arr
        ];
        return response($dataReturn, 200);
    }
    public function rateTrip(Request $request){
        $rating = new Rating();
        $rating->rating = $request->rating;
        $rating->feedback = $request->feedback;
        $rating->driver_id = $request->driver_id;
        $rating->customer_id = $request->customer_id;
        $rating->trip_id = $request->trip_id;
        $rating->save();
        return response('Rating Save', 200);
    }
    public function getCurrentTripRating($trip_id){
        $rating = Rating::where('trip_id', $trip_id)->first();
        return response($rating, 200);
    }
}
