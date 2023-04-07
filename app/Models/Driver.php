<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;
    public function rating(){
        return $this->hasMany(Rating::class);
    }
    public function trips(){
        return $this->hasMany(Trip::class);
    }
    public function wallet(){
        return $this->hasMany(Wallet::class);
    }
    public function driverLocation(){
        return $this->hasOne(DriverToCustomerLocation::class);
    }

}
