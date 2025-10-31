<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserBalanceEntry extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
        'before_balance' => 'decimal:2',
        'after_balance' => 'decimal:2',
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
