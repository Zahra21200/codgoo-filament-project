<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon; 

class AvailableSlot extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function meetings()
    {
        return $this->hasMany(Meeting::class, 'slot_id');
    }
}
