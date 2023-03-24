<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Property;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'email',
        'message'

    ];

    public function property(){
        return $this -> belongsTo(Property :: class);
    }
}