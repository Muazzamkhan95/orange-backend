<?php

use App\Http\Controllers\Api\ServiceTypeController as ApiServiceTypeController;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\BookingController;
use App\Http\Controllers\Api\v1\BrandController;
use App\Http\Controllers\Api\v1\CarController;
use App\Http\Controllers\Api\v1\CustomerController;
use App\Http\Controllers\Api\v1\DriverController;
use App\Http\Controllers\Api\v1\NotificationSendController;
use App\Http\Controllers\Api\v1\RateTypeController;
use App\Http\Controllers\Api\v1\RatingController;
use App\Http\Controllers\Api\v1\ServiceTypeController;
use App\Http\Controllers\Api\v1\StateController;
use App\Http\Controllers\Api\v1\ChatController;
use App\Http\Controllers\Api\v1\PaymentMethodController;
use App\Http\Controllers\Api\v1\PromoCodesController;
use App\Http\Controllers\Api\v1\SelfServiceTripsController;
use App\Models\DriverToCustomerLocation;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('phone/auth', [AuthController::class, 'phonAuth']);
Route::post('driver/signup', [AuthController::class, 'signupRider']);
Route::post('driver/login', [AuthController::class, 'loginRider']);
Route::post('driver/verify', [AuthController::class, 'verify']);

Route::post('user/signup', [AuthController::class, 'signupUser']);
Route::post('user/verify', [AuthController::class, 'verify']);
Route::post('user/login', [AuthController::class, 'loginUser']);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('store-token', [NotificationSendController::class, 'updateDeviceToken'])->name('store.token');
Route::post('user/signup', [AuthController::class, 'signupUser']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::post('update/profile', [CustomerController::class, 'update']);

        // Auth
        Route::get('services/type', [ServiceTypeController::class, 'getAllServicesType']);
        Route::get('brand/list', [BrandController::class, 'index']);
        Route::get('state/list', [StateController::class, 'index']);
        // Rate

        Route::get('rate/type', [RateTypeController::class, 'getAllRateType']);
        Route::get('rate/type/single/{id}', [RateTypeController::class, 'getSingleRateType']);

        // Booking
        Route::post('book/trip', [BookingController::class, 'booking']);
        Route::post('book/trip/cancel', [BookingController::class, 'bookingStatusCancel']);
        Route::post('book/trip/status', [BookingController::class, 'bookingStatus']);
        Route::post('trip/notaccept', [BookingController::class, 'bookingStatus']);

        Route::get('trip/details/{id}', [BookingController::class, 'getBookingDetails']);
        Route::get('trip/schedule/details/{id}', [BookingController::class, 'getScheduleBookingDetails']);

        Route::get('trip/complete/details/{id}', [BookingController::class, 'getCompleteBookingDetails']);

        Route::get('trip/arrived/status/{id}', [BookingController::class, 'getBookingArrivedStatus']);
        Route::get('trip/start/status/{id}', [BookingController::class, 'getBookingInprogressStatus']);
        Route::get('trip/complete/{id}', [BookingController::class, 'getBookingCompeletStatus']);

        Route::get('mytrip/{id}', [CustomerController::class, 'myTrip']);

        // Driver Location
        Route::get('get/driver/location/{driver_id}', [DriverController::class, 'getDriverLocation']);
        // Car
        Route::post('car/add', [CarController::class, 'store']);
        Route::get('car/getAllCar', [CarController::class, 'index']);
        Route::get('car/get/{id}', [CarController::class, 'getCar']);
        Route::get('car/status/{id}', [CarController::class, 'getStatus']);
        Route::get('car/customer/{id}', [CarController::class, 'getCustomerCar']);

        // Rating
        Route::post('rating', [RatingController::class, 'rateTrip']);
        // All trip detail
        Route::get('myFare/today/{customer_id}', [CustomerController::class, 'getTodayFareDetail']);
        Route::get('myFare/week/{customer_id}', [CustomerController::class, 'getWeekFareDetail']);
        Route::get('myFare/chart/{customer_id}', [CustomerController::class, 'getChartData']);

        // chat
        Route::post('chat', [ChatController::class, 'store']);
        Route::get('chat/{trip_id}', [ChatController::class, 'index']);

        Route::get('schedule/trips/{id}', [BookingController::class, 'getScheduleBooking']);
        // Check Promo Code
        Route::get('promo/code/check/{code}', [PromoCodesController::class, 'checkPromoCode']);

        Route::get('get/payment/method', [PaymentMethodController::class, 'index']);

        Route::get('check/screen/display/{trip_id}', [BookingController::class, 'screentoDisplay']);

    });
    // return $request->user();
    Route::post('driver/update-notification', [NotificationSendController::class, 'updateToken']);
    Route::post('user/update-notification', [NotificationSendController::class, 'updateToken']);
    Route::post('send-notification', [NotificationSendController::class, 'sendNotification']);
    Route::group(['prefix' => 'driver', 'as' => 'driver.'], function () {


        Route::post('logout/{id}', [AuthController::class, 'logout']);
        Route::post('trip/status', [BookingController::class, 'bookingStatus']);
        Route::post('trip/cancel', [BookingController::class, 'bookingStatusCancel']);
        Route::get('trip/pending', [BookingController::class, 'getPendingBooking']);
        Route::post('trip/accept/status', [BookingController::class, 'bookingStatus']);
        Route::get('trip/get/accept/status', [BookingController::class, 'bookingAcceptStatus']);
        Route::get('trip/pending/{id}', [BookingController::class, 'getPendingBooking']);
        Route::post('trip/decline', [BookingController::class, 'driverDeclineBooking']);
        Route::post('trip/arrived/status', [BookingController::class, 'bookingStatus']);
        Route::post('trip/start/status', [BookingController::class, 'bookingStatus']);
        Route::post('trip/complete/status', [BookingController::class, 'bookingStatus']);
        Route::post('trip/payby', [BookingController::class, 'cashOrCard']);

        Route::get('mytrip/{id}', [DriverController::class, 'myTrip']);

        Route::get('get/all', [DriverController::class, 'index']);
        Route::get('edit/{id}', [DriverController::class, 'edit']);
        Route::post('update', [DriverController::class, 'update']);

        Route::post('check/trip/status', [BookingController::class, 'CheckTripStatus']);
        // Route::post('check/trip/arrived', [BookingController::class, 'openTripArrivedScreen']);

        Route::post('status/store', [DriverController::class, 'storeDriverStatus']);
        Route::get('status/get/{id}', [DriverController::class, 'getDriverStatus']);

        Route::post('location', [DriverController::class, 'storeDriverLocation']);

        Route::get('trip/accept/{id}', [DriverController::class, 'getTripAccept']);
        Route::get('trip/cancel/{id}', [DriverController::class, 'getTripCancel']);
        Route::get('rating/{id}', [RatingController::class, 'getDriverRating']);

        Route::get('current/trip/rating', [RatingController::class, 'getCurrentTripRating']);
        Route::get('myEarning/today/{driver_id}', [DriverController::class, 'getTodayEarningDetail']);
        Route::get('myEarning/week/{driver_id}', [DriverController::class, 'getWeekEarningDetail']);
        Route::get('myWallet/{driver_id}', [DriverController::class, 'getWalletDetail']);


        Route::get('myEarning/chart/{driver_id}', [DriverController::class, 'getChartData']);

        Route::post('chat', [ChatController::class, 'store']);
        Route::get('chat/{trip_id}', [ChatController::class, 'index']);

        Route::get('schedule/trips/{driver_id}', [BookingController::class, 'getDriverScheduleBooking']);

        // Self Services
        Route::post('self/book/trip', [SelfServiceTripsController::class, 'booking']);
        Route::post('self/accept/status', [SelfServiceTripsController::class, 'bookingStatus']);
        Route::post('self/arrived/status', [SelfServiceTripsController::class, 'bookingStatus']);
        Route::post('self/start/status', [SelfServiceTripsController::class, 'bookingStatus']);
        Route::post('self/complete/status', [SelfServiceTripsController::class, 'bookingStatus']);
        Route::post('self/payby', [SelfServiceTripsController::class, 'cashOrCard']);
        Route::post('self/check/trip/status', [SelfServiceTripsController::class, 'CheckTripStatus']);


    });
});
