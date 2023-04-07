<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\PromoCodes;
use Illuminate\Http\Request;

class PromoCodesController extends Controller
{
    public function checkPromoCode($code){
        $promoCodeDetails = PromoCodes::where('code', $code)->first();
        if($promoCodeDetails){
            $today = now()->format('Y-m-d');
            if ($promoCodeDetails->valid_from > $today || $promoCodeDetails->valid_to < $today) {
                return response('Promo code is not valid', 404);
            } else {
                return response($promoCodeDetails, 200);
            }
        } else {
            return response('Promo code is not valid', 404);
        }
    }
}
