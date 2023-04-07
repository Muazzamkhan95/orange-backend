<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceDetail;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceDetailController extends Controller
{
    public function index()
    {
        $serviceType = ServiceType::all();
        $data = ServiceDetail::with('serviceType')->get();
        // dd($data->serviceType()->name);

        return view('admin.servicedetail.index', compact('data', 'serviceType'));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $data =new ServiceDetail();
        $data->name= $request->name;
        $data->description= $request->description;
        $data->booking_fee= $request->booking_fee;
        $data->rate= $request->rate;
        $data->rate_cal= $request->rate_cal;
        $data->subtitle= $request->subtitle;
        $data->service_type_id= $request->service_type_id;

        // dd($data->image);
        $data->save();
        // dd($request->all());
        toastr('Service Details Created!', 'success');
        return back();
    }
    public function edit($id, ServiceType $serviceDetail)
    {
        $data =ServiceDetail::find($id);
        return response($data, 200);
    }
    public function update(Request $request)
    {
        // dd($request->all());
        $data =ServiceDetail::find($request->id);
        $data->name= $request->name;
        $data->description= $request->description;
        $data->booking_fee= $request->booking_fee;
        $data->rate= $request->rate;
        $data->rate_cal= $request->rate_cal;
        $data->subtitle= $request->subtitle;
        $data->service_type_id= $request->service_type_id;

        // dd($data->image);
        $data->update();
        // dd($request->all());
        toastr('Service Details Created!', 'success');
        return back();
    }
    public function destroy(Request $request,ServiceType $serviceType)
    {
        $data =ServiceDetail::find($request->id);
        $data->delete();
        toastr('Service Detail Delete!', 'success');
        return back();
    }
}
