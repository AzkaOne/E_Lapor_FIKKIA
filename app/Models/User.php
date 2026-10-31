<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'identity_number',
        'position',
        'sub_department_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function subDepartment(): BelongsTo
    {
        return $this->belongsTo(SubDepartment::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function handledReports(): HasMany
    {
        return $this->hasMany(Report::class, 'handler_id');
    }

    public function sentDispositions(): HasMany
    {
        return $this->hasMany(ReportDisposition::class, 'sender_id');
    }

    public function receivedDispositions(): HasMany
    {
        return $this->hasMany(ReportDisposition::class, 'receiver_id');
    }

    public function isPelapor(): bool
    {
        return $this->role === 'pelapor';
    }

    public function isKabag(): bool
    {
        return $this->role === 'kabag';
    }

    public function isKasubag(): bool
    {
        return $this->role === 'kasubag';
    }

    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }
}
