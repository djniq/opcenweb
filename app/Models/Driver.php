<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'health_facility_id',
        'first_name',
        'last_name',
        'middle_name',
        'updated_by',
        'created_by',
        'status'
    ];

    function healthFacility(): BelongsTo {
        return $this->belongsTo(HealthFacility::class);
    }

    function dispatch(): HasMany {
        return $this->hasMany(Dispatch::class);
    }

    public function getStatus(int $status) {
        $statusVals = [
            1 => 'Active',
            2 => 'Inactive'
        ];

        return $statusVals[$status];
    }
}
