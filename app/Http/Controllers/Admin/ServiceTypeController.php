<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ServiceType::all();
        return view('admin.servicetype.index', compact('data'));
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
        $data =new ServiceType();
        $data->name= $request->name;
        $data->status= 1;
        $data->image= $request->image;
        if (!empty(request()->file('image'))) {
            $destinationPath = 'storage/servicetype';
            $extension = request()->file('image')->getClientOriginalExtension();
            $fileName ='storage/servicetype/'. 'service-type-' . time() . rand() . $data->id . '.' . $extension;
            request()->file('image')->move($destinationPath, $fileName);
            $data->image = $fileName;
        }
        // dd($data->image);
        $data->save();
        // dd($request->all());
        toastr('Service Type Created!', 'success');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceType $serviceType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function edit($id, ServiceType $serviceType)
    {
        $data =ServiceType::find($id);
        // dd($data->id);
        return response($data, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceType $serviceType)
    {
        $data =ServiceType::find($request->id);
        $data->name= $request->name;
        if (!empty(request()->file('image'))) {
            $destinationPath = 'storage/servicetype';
            $extension = request()->file('image')->getClientOriginalExtension();
            $fileName ='storage/servicetype/'. 'service-type-' . time() . rand() . $data->id . '.' . $extension;
            request()->file('image')->move($destinationPath, $fileName);
            $data->image = $fileName;
            // $data->image= $request->image;
        }
        $data->update();
        toastr('Service Type Update!', 'success');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,ServiceType $serviceType)
    {
        //

        // dd($request->all());
        $data =ServiceType::find($request->id);
        // dd($data);
        $data->delete();
        toastr('Service Type Delete!', 'success');
        return back();
    }
}
