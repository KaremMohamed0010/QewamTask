<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;


    protected $fillable = ['activated', 'appointment'];
    // Add other session model fields as needed

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
