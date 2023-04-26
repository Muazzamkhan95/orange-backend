<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\DriverStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function phonAuth(Request $request)
    {
        // dd($request->phone);
        // $phonenumber = $request->phone;
        // return "OK";

        $token = env('TWILIO_AUTH_TOKEN', '47cc4930d2bd260a1645397ac38ebb75');
        $twilio_sid = env('TWILIO_SID', 'ACe8c3d321c5982d30d06058179a4cbe84');
        $twilio_verify_sid = env('TWILIO_VERIFY_SID', 'VA62f5835a3be4bf8df9317f5c6e366153');
        // $token = env('TWILIO_AUTH_TOKEN','d013a35b70ecf8f1f092ad21d5955942');
        // $twilio_sid = env('TWILIO_SID','ACf24d4b159d33a125bedafaf8127fee72');
        // $twilio_verify_sid = env('TWILIO_VERIFY_SID','VA6f964f944769aaa4c6a48df14f9f01d3');
        // $token = "d013a35b70ecf8f1f092ad21d5955942";
        // $twilio_sid = "ACf24d4b159d33a125bedafaf8127fee72";
        // $twilio_verify_sid = "VA6f964f944769aaa4c6a48df14f9f01d3" ;


        $twilio = new Client($twilio_sid, $token);
        if ($twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create($request['phone'], "sms")
        ) {

            return response('SMS Send to Your Phone Number', 200);
        } else {
            return response('Error', 500);
        }
        // dd($html);


    }
    protected function verify(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'verification_code' => ['required', 'numeric'],
            'phone' => ['required', 'string'],
        ]);
        // dd($data);
        /* Get credentials from .env */
        // $token = env('TWILIO_AUTH_TOKEN','d013a35b70ecf8f1f092ad21d5955942');
        // $twilio_sid = env('TWILIO_SID','ACf24d4b159d33a125bedafaf8127fee72');
        // $twilio_verify_sid = env('TWILIO_VERIFY_SID','VA6f964f944769aaa4c6a48df14f9f01d3');

        $token = env('TWILIO_AUTH_TOKEN', '47cc4930d2bd260a1645397ac38ebb75');
        $twilio_sid = env('TWILIO_SID', 'ACe8c3d321c5982d30d06058179a4cbe84');
        $twilio_verify_sid = env('TWILIO_VERIFY_SID', 'VA62f5835a3be4bf8df9317f5c6e366153');

        // $token = getenv("TWILIO_AUTH_TOKEN");
        // $twilio_sid = getenv("TWILIO_SID");
        // $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $verification = $twilio->verify->v2->services($twilio_verify_sid)
            ->verificationChecks
            ->create([
                "to" => $data['phone'],
                "code" => $data["verification_code"]
            ]);
        if ($verification->valid) {
            $user = tap(User::where('phone', $data['phone']))->update(['isVerified' => true]);
            /* Authenticate user */
            // Auth::login($user->first());
            return response($verification->status, 200);
            // return redirect()->route('home')->with(['message' => 'Phone number verified']);
        } else {
            return response($verification->status, 200);
        }
        // return back()->with(['phone_number' => $data['phone_number'], 'error' => 'Invalid verification code entered!']);
    }
    public function signupUser(Request $request)
    {
        // dd($request);
        $validated = $request->validate([
            'name' => 'required', 'string', 'max:255',
            'email' => 'required', 'string', 'email', 'max:255', 'unique:users',
            'password' => 'required',
            'phone' => 'required',
        ]);
        // dd($validated);

        $data = new User();
        $data->name = $validated['name'];
        $data->email = $validated['email'];
        $data->phone = $validated['phone'];
        $data->password = Hash::make($request->password);
        // dd($data->file, $data->image, $data->imag1, $data->imag2, $data->imag3);
        $role = Role::where('name', 'User')->first();

        $data->role_id = $role->id;
        $data->save();
        // dd($data);
        $customer = new Customer();
        $customer->user_id = $data->id;
        $customer->name = $validated['name'];
        $customer->email = $validated['email'];
        $customer->phone = $validated['phone'];
        $customer->save();
        // $res = [
        //     $data,
        //     $customer
        // ];
        return response('User Register Successfully', 200);
    }
    public function loginUser(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // dd(auth()->user()->role_id);
            if (auth()->user()->role_id == 3) {
                $auth_id = auth()->user()->id;
                $customer = Customer::where('user_id', $auth_id)->first();
                $dob = $customer->dob;
                $profile_image = $customer->profile_image;
                $gender = $customer->gender;
                $data = [
                    'id' => $customer->id,
                    'user_id' => (string) $customer->user_id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'profile_image' => $profile_image,
                    'dob' => $dob,
                    'gender' => $gender
                ];
                $response['data'] = $data;
                $response['accessToken'] = auth()->user()->createToken('API Access Token')->plainTextToken;
                $response['status'] = '200';
                return response($response, 200)->header('Content-Type', 'application/json');
            } else {
                $response['message'] = 'invalid Username';
            }
        } else {
            $response['message'] = 'invalid Username or password';
            $response['status'] = '403';
            return response($response, 403)->header('Content-Type', 'application/json');
        }
    }
    public function signupRider(Request $request)
    {
        $email = User::where('email', $request->email)->first();
        if ($email == null) {

            $validated = $request->validate([
                'name' => 'required', 'string', 'max:255',
                'email' => 'required', 'string', 'email', 'max:255', 'unique:users',
                'password' => 'required',
                'phone' => 'required',
                'cv_file' => 'required',
                'cnic_front' => 'required',
                'cnic_back' => 'required',
                'lic_front' => 'required',
                'lic_back' => 'required',
                'cnic' => 'required',
                'lic' => 'required',
            ]);
            // $token = getenv("TWILIO_AUTH_TOKEN");
            // $twilio_sid = getenv("TWILIO_SID");
            // $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
            // $twilio = new Client($twilio_sid, $token);
            // $twilio->verify->v2->services($twilio_verify_sid)
            //     ->verifications
            //     ->create($validated['phone'], "sms");


            $data = new User();
            $data->name = $validated['name'];
            $data->email = $validated['email'];
            $data->phone = $validated['phone'];
            $data->password = Hash::make($request->password);
            $role = Role::where('name', 'Rider')->first();
            // dd($role->id);

            $data->role_id = $role->id;
            $data->save();

            $driver = new Driver();
            $driver->user_id = $data->id;
            $driver->name = $validated['name'];
            $driver->phone = $validated['phone'];
            $driver->email = $validated['email'];
            $driver->cnic = $validated['cnic'];
            $driver->lic = $validated['lic'];
            if (!empty(request()->file('profile_image'))) {
                $destinationPath = 'storage/cv';
                $extension = request()->file('profile_image')->getClientOriginalExtension();
                $fileName = '/storage/profile/' . 'image-' . time() . rand() . $driver->id . '.' . $extension;
                request()->file('profile_image')->move($destinationPath, $fileName);
                $driver->profile_image  = $fileName;
            }
            if (!empty(request()->file('cv_file'))) {
                $destinationPath = 'storage/cv';
                $extension = request()->file('cv_file')->getClientOriginalExtension();
                $fileName = '/storage/cv/' . 'cv-' . time() . rand() . $driver->id . '.' . $extension;
                request()->file('cv_file')->move($destinationPath, $fileName);
                $driver->cv_file = $fileName;
            }
            if (!empty(request()->file('cnic_front'))) {
                $destinationPath = 'storage/cnic';
                $extension = request()->file('cnic_front')->getClientOriginalExtension();
                $fileName = '/storage/cnic/' . 'cv-' . time() . rand() . $driver->id . '.' . $extension;
                request()->file('cnic_front')->move($destinationPath, $fileName);
                $driver->cnic_front = $fileName;
            }
            if (!empty(request()->file('cnic_back'))) {
                $destinationPath = 'storage/cnic';
                $extension = request()->file('cnic_back')->getClientOriginalExtension();
                $fileName = '/storage/cnic/' . 'cv-' . time() . rand() . $driver->id . '.' . $extension;
                request()->file('cnic_back')->move($destinationPath, $fileName);
                $driver->cnic_back = $fileName;
            }
            if (!empty(request()->file('lic_front'))) {
                $destinationPath = 'storage/driver';
                $extension = request()->file('lic_front')->getClientOriginalExtension();
                $fileName = '/storage/driver/' . 'cv-' . time() . rand() . $driver->id . '.' . $extension;
                request()->file('lic_front')->move($destinationPath, $fileName);
                $driver->lic_front = $fileName;
            }
            if (!empty(request()->file('lic_back'))) {
                $destinationPath = 'storage/driver';
                $extension = request()->file('lic_back')->getClientOriginalExtension();
                $fileName = '/storage/driver/' . 'cv-' . time() . rand() . $driver->id . '.' . $extension;
                request()->file('lic_back')->move($destinationPath, $fileName);
                $driver->lic_back = $fileName;
            }

            // dd($data->file, $data->image, $data->imag1, $data->imag2, $data->imag3);

            $driver->save();
            // $resp = [
            //     $driver,
            //     $data
            // ];
            return response('Driver Register Successfully', 200);
        } else {
            return response('Email Already Exist', 403);
        }
        // dd($request->all());
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function loginRider(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if (auth()->user()->role_id == 2 && auth()->user()->isVerified == 1) {
                // if(auth()->user()->role_id == 2 && auth()->user()->	isVerified){
                $auth_id = auth()->user()->id;
                $driver = Driver::where('user_id', $auth_id)->first();
                $accessToken = auth()->user()->createToken('API Access Token')->plainTextToken;
                $data = [
                    'id' => $driver->id,
                    'user_id' => (string)$driver->user_id,
                    'name' => $driver->name,
                    'email' => $driver->email,
                    'phone' => $driver->phone,
                    'profile_image' => $driver->profile_image,
                    'license' => $driver->lic,
                    'cnic' => $driver->cnic,
                    'rider_role' => (string)$driver->rider_role,
                    'accessToken' => $accessToken,
                ];
                // dd($data);
                $response['data'] = $data;
                $response['status'] = '200';
                return response($response, 200)->header('Content-Type', 'application/json');
            } else {
                // dd(auth()->user()->isVerified);
                if (auth()->user()->isVerified == 2) {
                    $response['message'] = 'Your Profile is under review';
                    return response($response, 200)->header('Content-Type', 'application/json');
                } elseif (auth()->user()->isVerified == 3) {
                    $response['message'] = 'Driver Account Has been Blocked';
                    return response($response, 200)->header('Content-Type', 'application/json');
                } else {
                    $response['message'] = 'invalid Username';
                    return response($response, 403)->header('Content-Type', 'application/json');
                }
            }
        } else {
            $response['message'] = 'invalid Username or password';
            $response['status'] = '403';
            return response($response, 403)->header('Content-Type', 'application/json');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function forgetPassword(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        // dd($user);
        if($user !== null ){
            $token = env('TWILIO_AUTH_TOKEN', 'c34ecca0c360128c418aaa27bab9a705');
        $twilio_sid = env('TWILIO_SID', 'ACe8c3d321c5982d30d06058179a4cbe84');
        $twilio_verify_sid = env('TWILIO_VERIFY_SID', 'VA62f5835a3be4bf8df9317f5c6e366153');
        // $token = env('TWILIO_AUTH_TOKEN','d013a35b70ecf8f1f092ad21d5955942');
        // $twilio_sid = env('TWILIO_SID','ACf24d4b159d33a125bedafaf8127fee72');
        // $twilio_verify_sid = env('TWILIO_VERIFY_SID','VA6f964f944769aaa4c6a48df14f9f01d3');
        // $token = "d013a35b70ecf8f1f092ad21d5955942";
        // $twilio_sid = "ACf24d4b159d33a125bedafaf8127fee72";
        // $twilio_verify_sid = "VA6f964f944769aaa4c6a48df14f9f01d3" ;


        $twilio = new Client($twilio_sid, $token);
            if ($twilio->verify->v2->services($twilio_verify_sid)
                ->verifications
                ->create($request['phone'], "sms")
            ) {

                return response('Varification SMS Send to Your Phone Number', 200);
            } else {
                return response('Error', 500);
            }
        } else {
            return response('Record Not Found', 404);
        }
    }
    public function resetPassword(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        $user->password = Hash::make($request->password);
        $user->update();
        return response('Password Updated', 200);
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
    public function logout($id, Request $request)
    {
        $driverstatus = DriverStatus::where('driver_id', $id)->first();
        $driverstatus->device_token = '';
        $driverstatus->update();
        $request->user()->tokens()->delete();

        return response('User Logout', 200);
    }
}
