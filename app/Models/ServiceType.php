<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;
    public function trips(){
        return $this->hasMany(Trip::class);
    }
    public function servicesDetail(){
        return $this->hasMany(ServiceDetail::class);
    }
}
