<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrewSettings extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'health_facility_id',
        'squad_name',
        'updated_by',
        'created_by',
        'status'
    ];

    public function healthFacility(): BelongsTo {
        return $this->belongsTo(HealthFacility::class);
    }

    public function crewMembers(): HasMany {
        return $this->hasMany(CrewMember::class);
    }

    public function dispatch(): HasMany {
        return $this->hasMany(Dispatch::class);
    }

    public function getStatus(int $status) {
        $statusVals = [
            1 => 'Open',
            2 => 'Dispatched',
            3 => 'On Break'
        ];

        return $statusVals[$status];
    }
}
