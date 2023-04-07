<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\DriverStatus;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $customer = Customer::count();
        $driver = Driver::count();
        $trip = Trip::where('status', 5)->count();
        $tripToday = Trip::whereDate('created_at', Carbon::today())->count();
        $driverstatus1 = DriverStatus::where('status', 1)->get();

        // $driverstatus[] = ['Lat','Long','Name'];
        // foreach ($driverstatus1 as $key => $value) {
        //     $driverstatus[++$key] = [(double)$value->lat, (double)$value->lag, 'New'];
        // }
        // $d = json_encode($driverstatus);
        $driverstatus = array();
        foreach ($driverstatus1 as $key => $value) {
            $driver_name = Driver::find($value->driver_id);
            // $driverstatus[++$key] = [(double)$value->lat, (double)$value->lag, 'New'];
            $data1 = [
                'lat'=> $value->lat,
                'lng'=> $value->lag,
                'Name'=> $driver_name->name,
            ];
            array_push($driverstatus, $data1);
        }
        $d = json_encode($driverstatus);

        $driver_count = $driver - 1;
        return view('admin.dashboard', compact('driver_count',
        'customer', 'trip', 'tripToday', 'd'));
    }

    public function driverstatus(){
        $driverstatus1 = DriverStatus::where('status', 1)->get();
        $driverstatus[] = ['Lat','Long','Name'];
        foreach ($driverstatus1 as $key => $value) {
            $driverstatus[++$key] = [(double)$value->lat, (double)$value->lag, 'New'];
        }
        return response(json_encode($driverstatus), 200);
        // dd($driverstatus1);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
