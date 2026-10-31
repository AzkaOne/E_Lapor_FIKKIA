<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'ticket_code',
        'user_id',
        'sub_department_id',
        'handler_id',
        'campus_location',
        'title',
        'description',
        'status',
        'execution_result',
        'rating',
        'rejection_reason',
        'postponed_reason',
        'resolution_notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subDepartment(): BelongsTo
    {
        return $this->belongsTo(SubDepartment::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handler_id');
    }

    public function dispositions(): HasMany
    {
        return $this->hasMany(ReportDisposition::class)->latest();
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(ReportEvidence::class);
    }

    public static function generateTicketCode(string $prefix = 'LP'): string
    {
        do {
            $year = date('Y');
            $random = strtoupper(Str::random(5));
            $code = "{$prefix}-{$year}-{$random}";
        } while (static::where('ticket_code', $code)->exists());

        return $code;
    }
}
