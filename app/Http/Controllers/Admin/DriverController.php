<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Driver;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

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
        return view('admin.driver.index', compact('data'));
    }
    public function getPendingDriver()
    {
        $data = Driver::where('status', 0)->get();
        return view('admin.driver.pending', compact('data'));
    }
    public function detail($id)
    {
        $driver = Driver::find($id);
        return view('admin.driver.detail', compact('driver'));
    }
    public function isVarifed(Request $request)
    {
        $driver = Driver::find($request->id);
        $driver->status = $request->status;
        $driver->update();
        $user = User::find($driver->user_id);
        $user->isVerified = $request->status;
        $user->update();
        if($user->isVerified == 1){
            toastr('Project Approved!', 'success');
        } else {
            toastr('Project Approved!', 'danger');
        }
        return back();
        # code...
    }
    public function rides()
    {
        $driver = Driver::with('rating')->with('trips')->whereHas('trips', function ($query) {
            $query->where('status', 5);
        })->get();
        // $rides = array();
        // foreach ($driver as $d) {
        //     $total_ride = $d->trips->count();
        //     $rating_avg = $d->rating->avg('rating');
        //     $data = [
        //         'id'=> $d->id,
        //         'name'=> $d->name,
        //         'totalrides'=> $total_ride,
        //         'avgrating'=> $rating_avg
        //     ];
        //     array_push($rides, $data);
        // }
        // dd($rides);

        return view('admin.driver.rides', compact('driver'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function wallet()
    {
        $driver = Driver::with('wallet')->get();
        return view('admin.driver.wallet', compact('driver'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getWalletDetails($id)
    {
        $wallet = Wallet::with('driver')->where('driver_id', $id)->get();
        $data = json_encode($wallet);
        return response($data, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(Driver $driver)
    {
        //
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
}
