<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    public function Customer(){
        return $this->belongsTo(Customer::class);
    }
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
    public function brand(){
        return $this->belongsTo(Brand::class);
    }
    public function state(){
        return $this->belongsTo(State::class);
    }
}
