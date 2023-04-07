<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\PromoCodes;
use Illuminate\Http\Request;

class PromoCodesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promocodes = PromoCodes::all();
        return view('admin.promocode.index', ['promocodes' => $promocodes]);
    }

    public function create()
    {
        return view('promocodes.create');
    }

    public function store(Request $request)
    {
        $promocode = new PromoCodes();
        $promocode->code = $request->code;
        $promocode->discount = $request->discount;
        $promocode->valid_from = $request->valid_from;
        $promocode->valid_to = $request->valid_to;
        $promocode->save();

        toastr('Promo Code Created!', 'success');
        return back();
    }

    public function edit($id, PromoCodes $promocode)
    {
        $data =PromoCodes::find($id);
        return response($data, 200);
    }

    public function update(Request $request, PromoCodes $promocode)
    {
        $promocode= PromoCodes::find($request->id);
        $promocode->code = $request->code;
        $promocode->discount = $request->discount;
        $promocode->valid_from = $request->valid_from;
        $promocode->valid_to = $request->valid_to;
        $promocode->update();

        toastr('Promo Code Updated!', 'success');
        return back();
    }

    public function destroy(PromoCodes $promocode, Request $request)
    {
        $promocode= PromoCodes::find($request->id);
        $promocode->delete();
        toastr('Promo Code Deleted!', 'success');
        return back();
    }
}
