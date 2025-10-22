<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    protected $table = 'assignments';
    protected $fillable = [
        'jiri_id',
        'project_id',
    ];

    public function implementations(): HasMany
    {
        return $this->hasMany(Implementation::class);
    }

    public function jiri(): BelongsTo
    {
        return $this->belongsTo(Jiri::class);
    }

    public function contact(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class, 'implementations')->withTimestamps();
    }

    public function project(): BelongsTo
    {
        return $this->BelongsTo(Project::class);
    }
}
