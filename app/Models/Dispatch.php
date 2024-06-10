<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Dispatch extends Model
{
    use HasFactory;

    protected $fillable = [
        "incident_id",
        "ambulance_id",
        "driver_id",
        "crew_settings_id",
        "tagged_ambulance_datetime",
        "start_datetime",
        "arrived_on_site_datetime",
        "moved_out_from_site_datetime",
        "arrived_to_destination_datetime",
        "endorsed_patient_datetime",
        "completed_datetime",
        "last_recorded_location",
        "remarks",
        "status",
        "updated_by",
        "created_by"
    ];

    public function incident(): BelongsTo {
        return $this->belongsTo(Incident::class);
    }

    public function ambulance(): BelongsTo {
        return $this->belongsTo(Ambulance::class);
    }

    public function driver(): BelongsTo {
        return $this->belongsTo(Driver::class);
    }

    public function activeSquad(): BelongsTo {
        return $this->belongsTo(CrewSettings::class);
    }

    public function crews(): HasMany {
        return $this->hasMany(Crew::class);
    }

    public static function getStatus(string $status) {
        $statusVals = [
            'pending' => "Pending",
            'start' => 'Started',
            'arrived-on-site' => "Arrived On Site",
            'moved-out-from-site' => "Moved Out From Site",
            'arrived-at-destination' => "Arrived At Destination",
            'endorsed-patient' => "Endorsed Patient",
            'completed' => "Completed"
        ];

        return $statusVals[$status];
    } 
}
