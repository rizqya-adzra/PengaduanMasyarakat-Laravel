<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'description', 'type', 'province', 'regency', 'subdistrict', 'village', 'image', 'statement', 'voting'
    ];

    protected $casts = [
        'voting' => 'array',
    ];

    public function comment()
    {
        return $this->hasMany(Comment::class, 'id', 'report_id');
    }

    public function response()
    {
        return $this->hasOne(Response::class, 'id', 'report_id');
    }
}