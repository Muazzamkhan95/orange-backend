<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Car::all();
        $array = array();
        foreach($data as $d){
            $car = [
                "id"=> $d->id,
                "name"=> $d->name,
                "plate_code"=> $d->plate_code,
                "plate_number"=> $d->plate_number,
                "transmission_type"=> $d->transmission_type,
                "comprehensive_insurance"=> $d->comprehensive_insurance,
                "customer_id"=> (string)$d->customer_id,
                "state_id"=> (string)$d->state_id,
                "brand_id"=> (string)$d->brand_id,
                "color"=> $d->color,
                "status"=> (string)$d->status,
                "updated_at"=> $d->updated_at,
                "created_at"=> $d->created_at,
            ];
            array_push($array, $car);
        }
        return response($array, 200);
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
        $brand = Brand::find($request->brand_id);
        $data = new Car();
        $data->name = $brand->name;
        $data->plate_code = $request->plate_code;
        $data->plate_number = $request->plate_number;
        $data->transmission_type = $request->transmission_type;
        $data->comprehensive_insurance = $request->comprehensive_insurance;
        $data->customer_id = $request->customer_id;
        $data->state_id = $request->state_id;
        $data->brand_id = $request->brand_id;
        $data->color = $request->color;
        $data->status = 1;
        if (!empty(request()->file('image'))) {
            $destinationPath = 'storage/cars';
            $extension = request()->file('image')->getClientOriginalExtension();
            $fileName ='storage/cars/'. 'car-' . time() . rand() . $data->id . '.' . $extension;
            request()->file('image')->move($destinationPath, $fileName);
            $data->image = $fileName;
        }

        $data->save();
        return response($data, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show(Car $car)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit(Car $car)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Car $car)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(Car $car)
    {
        //
    }
    public function getCar($id){
        $data = Car::find($id);
        if($data){
            $array = array();
            foreach($data as $d){
                $car = [
                    "id"=> $d->id,
                    "name"=> $d->name,
                    "plate_code"=> $d->plate_code,
                    "plate_number"=> $d->plate_number,
                    "transmission_type"=> $d->transmission_type,
                    "comprehensive_insurance"=> $d->comprehensive_insurance,
                    "customer_id"=> (string)$d->customer_id,
                    "state_id"=> (string)$d->state_id,
                    "brand_id"=> (string)$d->brand_id,
                    "color"=> $d->color,
                    "status"=> (string)$d->status,
                    "updated_at"=> $d->updated_at,
                    "created_at"=> $d->created_at,
                ];
                array_push($array, $car);
            }
            return response($array, 200);

        } else {
            return response('Car Not Exist', 200);
        }

    }
    public function getStatus($id){
        $data = Car::find($id);
        if($data){
            $status = $data->status;
            return response($status, 200);
        } else {
            return response('Car Not Exist', 200);
        }

    }
    public function getCustomerCar($customer_id){
        // $data = Car::where('customer_id', $customer_id)->get();
        $data = Car::with('brand')->with('state')->where('customer_id', $customer_id)->get();
        if($data){
            $car = array();
            foreach($data as $d){
                // dd($d);
                $car_data= [
                    "id"=> $d->id,
                    "name"=> $d->name,
                    "plate_code"=> $d->plate_code,
                    "plate_number"=> $d->plate_number,
                    "transmission_type"=> (string)$d->transmission_type,
                    "comprehensive_insurance"=> (string)$d->comprehensive_insurance,
                    "status"=> (string)$d->status,
                    "color"=> $d->color,
                    "customer_id"=> (string)$d->customer_id,
                    "state_id"=> (string)$d->state_id,
                    "state_name"=> $d->state->name,
                    "brand_id"=> (string)$d->brand_id,
                    "brand_name"=> $d->brand->name,
                    "brand_image"=> $d->brand->brand_image,
                    "created_at"=> $d->created_at,
                    "updated_at"=> $d->updated_at,
                ];
                // dd($car_data);
                array_push($car, $car_data);
            }
            return response($car, 200);
        } else {
            return response('Car Not Exist', 200);
        }

    }
}
