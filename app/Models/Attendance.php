<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = ['role'];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function jiri(): BelongsTo
    {
        return $this->belongsTo(Jiri::class);
    }
}
