<?php

namespace App\Models;

use App\Policies\ContactPolicy;
use Database\Factories\ContactFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[UsePolicy(ContactPolicy::class)]
class Contact extends Model
{
    /** @use HasFactory<ContactFactory> */
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'avatar',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jiris(): BelongsToMany
    {
        return $this->belongsToMany(Jiri::class, 'attendances')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function implementations(): HasMany
    {
        return $this->hasMany(Implementation::class);
    }

    public function assignments(): BelongsToMany
    {
        return $this->belongsToMany(Assignment::class, 'implementations')
            ->withTimestamps();
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::get(function (): string {
            $disk = Storage::disk('public');
            $dimensions = '200x200';
            $relativePath = "images/contact/{$dimensions}/{$this->avatar}";
                return $disk->url($relativePath);
        });
    }


}
