<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'response_id', 'histories'
    ];

    public function response()
    {
        return $this->belongsTo(Response::class, 'response_id', 'id');
    }
    
}
