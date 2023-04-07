<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;
    public function driver(){
        return $this->belongsTo(Driver::class);
    }
    public function car(){
        return $this->belongsTo(Car::class);
    }
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function paymentMethod(){
        return $this->belongsTo(PaymentMethod::class);
    }
    public function paymentMethod1(){
    }
    public function serviceType(){
        return $this->belongsTo(ServiceType::class);
    }
}
