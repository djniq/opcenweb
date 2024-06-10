<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrewMember extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "crew_settings_id",
        "created_by"
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function crewSettings(): BelongsTo {
        return $this->belongsTo(CrewSettings::class);
    }
}
