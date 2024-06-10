<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispatchStatusChangeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispatch_id',
        'from_status',
        'to_status',
        'recorded_location',
        'created_by',
        'updated_by'
    ];
}
