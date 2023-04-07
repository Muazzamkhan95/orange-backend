<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    //
    public function getAllServicesType(){
        $data = ServiceType::with('servicesDetail')->where('status', '1')->get();
        $array = array();
        foreach($data as $d){
			$array1 = [];
            $array1 = array();
            foreach($d->servicesDetail as $serviceDetail){
				$servicesd = [
                    "id"=> $serviceDetail->id,
                    "name"=> $serviceDetail->name,
                    "booking_fee"=> (string)$serviceDetail->booking_fee,
                    "rate"=> (string)$serviceDetail->rate,
                    "description"=> $serviceDetail->description,
                    "status"=> (string)$serviceDetail->status,
                    "service_type_id"=> (string)$serviceDetail->service_type_id,
                    "created_at"=> $serviceDetail->created_at,
                    "updated_at"=> $serviceDetail->updated_at,
                    "isHourly"=> (string)$serviceDetail->isHourly,
                    "subtitle"=> $serviceDetail->subtitle,
                    "rate_cal"=> (string)$serviceDetail->rate_cal
                ];
                array_push($array1, $servicesd);
            }
            $serviceType = [
                "id"=> $d->id,
                "name"=> $d->name,
                "image"=> $d->image,
                "status"=> (string) $d->status,
                "created_at"=> $d->created_at,
                "updated_at"=> $d->updated_at,
                "services_detail"=> $array1,
            ];
            array_push($array, $serviceType);
        }
        return response()->json($array, 200);
    }
}
