<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    public function Cars(){
        return $this->hasMany(Car::class);
    }


    public function trips(){
        return $this->hasMany(Trip::class);
    }
    public function rating(){
        return $this->hasMany(Rating::class);
    }
}
