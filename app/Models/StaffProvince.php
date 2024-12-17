<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffProvince extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'province'
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class , 'id', 'user_id');
    }

    public function response(){
        return $this->hasMany(Response::class);
    }
}
