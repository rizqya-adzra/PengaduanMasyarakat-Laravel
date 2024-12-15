<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'response_status',
        'staff_id'
    ];


    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id', 'id');
    }

public function response_progress()
{
    return $this->hasMany(ResponseProgress::class, 'response_id', 'id');
}

}
