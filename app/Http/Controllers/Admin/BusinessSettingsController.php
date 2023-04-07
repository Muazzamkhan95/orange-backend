<?php

namespace App\Http\Controllers\Admin;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusinessSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function companyInfo()
    {
        $company_name = BusinessSetting::where('type', 'company_name')->first();
        $company_email = BusinessSetting::where('type', 'company_email')->first();
        $company_phone = BusinessSetting::where('type', 'company_phone')->first();
        return view('admin.business-settings.website-info', [
            'company_name' => $company_name,
            'company_email' => $company_email,
            'company_phone' => $company_phone,
        ]);
    }
    public function updateInfo(Request $request){
        // dd($request);
        // if ($request['email_verification'] == 1) {
        //     $request['phone_verification'] = 0;
        // } elseif ($request['phone_verification'] == 1) {
        //     $request['email_verification'] = 0;
        // }

        //comapy shop banner
        // $imgBanner = BusinessSetting::where(['type' => 'shop_banner'])->first();
        // if ($request->has('shop_banner')) {
        //     $imgBanner = ImageManager::update('shop/', $imgBanner, 'png', $request->file('shop_banner'));
        //     DB::table('business_settings')->updateOrInsert(['type' => 'shop_banner'], [
        //         'value' => $imgBanner
        //     ]);
        // }
        // comapny name
        DB::table('business_settings')->updateOrInsert(['type' => 'company_name'], [
            'value' => $request['company_name']
        ]);
        // company email
        DB::table('business_settings')->updateOrInsert(['type' => 'company_email'], [
            'value' => $request['company_email']
        ]);
        // company Phone
        DB::table('business_settings')->updateOrInsert(['type' => 'company_phone'], [
            'value' => $request['company_phone']
        ]);
        //company copy right text
        DB::table('business_settings')->updateOrInsert(['type' => 'company_copyright_text'], [
            'value' => $request['company_copyright_text']
        ]);
        //company time zone
        DB::table('business_settings')->updateOrInsert(['type' => 'timezone'], [
            'value' => $request['timezone']
        ]);
        //country
        DB::table('business_settings')->updateOrInsert(['type' => 'country_code'], [
            'value' => $request['country']
        ]);
        //phone verification
        // DB::table('business_settings')->updateOrInsert(['type' => 'phone_verification'], [
        //     'value' => $request['phone_verification']
        // ]);
        //email verification
        // DB::table('business_settings')->updateOrInsert(['type' => 'email_verification'], [
        //     'value' => $request['email_verification']
        // ]);

        // DB::table('business_settings')->updateOrInsert(['type' => 'order_verification'], [
        //     'value' => $request['order_verification']
        // ]);

        // DB::table('business_settings')->updateOrInsert(['type' => 'forgot_password_verification'], [
        //     'value' => $request['forgot_password_verification']
        // ]);
        // DB::table('business_settings')->updateOrInsert(['type' => 'decimal_point_settings'], [
        //     'value' => $request['decimal_point_settings']
        // ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'company_address'], [
            'value' => $request['company_address']
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'cancellation_fee'], [
            'value' => $request['cancellation_fee']
        ]);


        //web logo
        $webLogo = BusinessSetting::where(['type' => 'company_web_logo'])->first();
        if ($request->has('company_web_logo')) {
                $destinationPath = 'storage/company';
                $extension = request()->file('company_web_logo')->getClientOriginalExtension();
                $fileName ='storage/company/'. time() . rand() . '.' . $extension;
                request()->file('company_web_logo')->move($destinationPath, $fileName);
                $webLogo = $fileName;
            // $webLogo = ImageManager::update('company/', $webLogo, 'png', $request->file('company_web_logo'));
            BusinessSetting::where(['type' => 'company_web_logo'])->update([
                'value' => $webLogo,
            ]);
        }
        $driver_icon = BusinessSetting::where(['type' => 'driver_icon'])->first();
        if ($request->has('driver_icon')) {
                $destinationPath = 'storage/company';
                $extension = request()->file('driver_icon')->getClientOriginalExtension();
                $fileName ='storage/company/'. time() . rand() . '.' . $extension;
                request()->file('driver_icon')->move($destinationPath, $fileName);
                $webLogo = $fileName;
            // $webLogo = ImageManager::update('company/', $webLogo, 'png', $request->file('driver_icon'));
            BusinessSetting::where(['type' => 'driver_icon'])->update([
                'value' => $webLogo,
            ]);
        }
        // dd('Test 3');


        //mobile logo
        $mobileLogo = BusinessSetting::where(['type' => 'company_mobile_logo'])->first();
        if ($request->has('company_mobile_logo')) {
            // $mobileLogo = ImageManager::update('company/', $mobileLogo, 'png', $request->file('company_mobile_logo'));
            // BusinessSetting::where(['type' => 'company_mobile_logo'])->update([
            //     'value' => $mobileLogo,
            // ]);
            if($mobileLogo == null){
                // dd('1');
                $mobileLogo =new BusinessSetting();
                $mobileLogo->type = 'company_mobile_logo';
                $destinationPath = 'storage/company';
                $extension = request()->file('company_mobile_logo')->getClientOriginalExtension();
                $fileName ='storage/company/'. time() . rand() . '.' . $extension;
                request()->file('company_mobile_logo')->move($destinationPath, $fileName);
                $mobileLogo_file = $fileName;
                $mobileLogo->value = $mobileLogo_file;
                $mobileLogo->save();
            } else {
                $destinationPath = 'storage/company';
                $extension = request()->file('company_mobile_logo')->getClientOriginalExtension();
                $fileName ='storage/company/'. time() . rand() . '.' . $extension;
                request()->file('company_mobile_logo')->move($destinationPath, $fileName);
                $company_mobile_logo = $fileName;
                // $webFooterLogo = ImageManager::update('company/', $webFooterLogo, 'png', $request->file('company_mobile_logo'));
                BusinessSetting::where(['type' => 'company_mobile_logo'])->update([
                    'value' => $company_mobile_logo,
                ]);
            }
        }
        //web footer logo
        $webFooterLogo = BusinessSetting::where(['type' => 'company_footer_logo'])->first();
        if ($request->has('company_footer_logo')) {
            if($webFooterLogo == null){
                // dd('1');
                $company_footer_logo =new BusinessSetting();
                $company_footer_logo->type = 'company_footer_logo';
                $destinationPath = 'storage/company';
                $extension = request()->file('company_footer_logo')->getClientOriginalExtension();
                $fileName ='storage/company/'. time() . rand() . '.' . $extension;
                request()->file('company_footer_logo')->move($destinationPath, $fileName);
                $webLogo = $fileName;
                $company_footer_logo->value = $webLogo;
                $company_footer_logo->save();
            } else {
                $destinationPath = 'storage/company';
                $extension = request()->file('company_footer_logo')->getClientOriginalExtension();
                $fileName ='storage/company/'. time() . rand() . '.' . $extension;
                request()->file('company_footer_logo')->move($destinationPath, $fileName);
                $company_footer_logo = $fileName;
                // $webFooterLogo = ImageManager::update('company/', $webFooterLogo, 'png', $request->file('company_footer_logo'));
                BusinessSetting::where(['type' => 'company_footer_logo'])->update([
                    'value' => $company_footer_logo,
                ]);
            }
        }
        //fav icon
        $favIcon = BusinessSetting::where(['type' => 'company_fav_icon'])->first();
        if ($request->has('company_fav_icon')) {
            if($favIcon == null){
                $favIcon =new BusinessSetting();
                $favIcon->type = 'company_fav_icon';
                $destinationPath = 'storage/company';
                $extension = request()->file('company_fav_icon')->getClientOriginalExtension();
                $fileName ='storage/company/'. time() . rand() . '.' . $extension;
                request()->file('company_fav_icon')->move($destinationPath, $fileName);
                $favIcon_file = $fileName;
                $favIcon->value = $favIcon_file;
                $favIcon->save();
            } else {
                $destinationPath = 'storage/company';
                $extension = request()->file('company_fav_icon')->getClientOriginalExtension();
                $fileName ='storage/company/'. time() . rand() . '.' . $extension;
                request()->file('company_fav_icon')->move($destinationPath, $fileName);
                $favIcon_file = $fileName;
                // $webFooterLogo = ImageManager::update('company/', $webFooterLogo, 'png', $request->file('company_footer_logo'));
                BusinessSetting::where(['type' => 'company_fav_icon'])->update([
                    'value' => $favIcon_file,
                ]);
            }
        }

        //loader gif
        $loader_gif = BusinessSetting::where(['type' => 'loader_gif'])->first();
        if ($request->has('loader_gif')) {

            if($loader_gif == null){
                $loadergif =new BusinessSetting();
                $loadergif->type = 'loader_gif';
                $destinationPath = 'storage/company';
                $extension = request()->file('loader_gif')->getClientOriginalExtension();
                $fileName ='storage/company/'. time() . rand() . '.' . $extension;
                request()->file('loader_gif')->move($destinationPath, $fileName);
                $loadergif_file = $fileName;
                $loadergif->value = $favIcon_file;
                $loadergif->save();
            } else {
                $destinationPath = 'storage/company';
                $extension = request()->file('loader_gif')->getClientOriginalExtension();
                $fileName ='storage/company/'. time() . rand() . '.' . $extension;
                request()->file('loader_gif')->move($destinationPath, $fileName);
                $loadergif_file = $fileName;
                // $webFooterLogo = ImageManager::update('company/', $webFooterLogo, 'png', $request->file('company_footer_logo'));
                BusinessSetting::where(['type' => 'loader_gif'])->update([
                    'value' => $loadergif_file,
                ]);
            }
        }
        // web color setup
        $colors = BusinessSetting::where('type', 'colors')->first();
        if (isset($colors)) {
            BusinessSetting::where('type', 'colors')->update([
                'value' => json_encode(
                    [
                        'primary' => $request['primary'],
                        'secondary' => $request['secondary'],
                    ]),
            ]);
        } else {
            DB::table('business_settings')->insert([
                'type' => 'colors',
                'value' => json_encode(
                    [
                        'primary' => $request['primary'],
                        'secondary' => $request['secondary'],
                    ]),
            ]);
        }

        // DB::table('business_settings')->updateOrInsert(['type' => 'default_location'], [
        //     'value' => json_encode(
        //         [   'lat' => $request['latitude'],
        //             'lng' => $request['longitude'],
        //         ]),
        // ]);

        //pagination
        // $request->validate([
        //     'pagination_limit' => 'numeric',
        // ]);
        // DB::table('business_settings')->updateOrInsert(['type' => 'pagination_limit'], [
        //     'value' => $request['pagination_limit'],
        // ]);
        // Toastr::success('');
        toastr()->success('Updated successfully!', 'Congrats');

        // Toastr::success('Updated successfully');
        return back();
    }
}
