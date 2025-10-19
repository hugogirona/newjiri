<?php

namespace App\Models;

use App\Enums\ContactRoles;
use Database\Factories\JiriFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jiri extends Model
{
    /** @use HasFactory<JiriFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'starts_at',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class, 'attendances')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'assignments')->withTimestamps();
    }

    public function attendances(): hasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function assignments(): hasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function evaluators(): BelongsToMany {
        return $this->contacts()->wherePivot('role', '=', ContactRoles::Evaluator->value);
    }

    public function evaluated(): BelongsToMany {
        return $this->contacts()->wherePivot('role', '=', ContactRoles::Evaluated->value);
    }

}
