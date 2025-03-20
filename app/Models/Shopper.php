<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shopper extends Model
{
    use HasFactory;

    protected $fillable = ['shopper_id', 'first_name', 'last_name', 'email', 'status', 'payment_link'];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
