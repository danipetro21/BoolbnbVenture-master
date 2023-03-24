<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Service;
use App\Models\Sponsorship;
use App\Models\View;


class Property extends Model
{
    use HasFactory;
    protected $fillable = [
        
        'title',
        'description',
        'room_number',
        'bath_number',
        'mq2',
        'address',
        'lat',
        'lon',
        'img',
        'visible',
        'price_per_night',
        'bed_number',

    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function services(){
        return $this->belongsToMany(Service::class, 'property_service');
    }
    
    public function sponsorships()
    {
        return $this->belongsToMany(Sponsorship::class, 'property_sponsorship')->withPivot('start_date', 'end_date');
    }

    public function messages(){
        return $this->hasMany(Message::class, 'property_id');
    }
    
    public function views(){
        return $this->hasMany(View::class, 'property_id');
    }

    
}