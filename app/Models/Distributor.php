<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Concerns\BelongsToTeam;
use App\Services\ChartOfAccountsSyncService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Distributor extends Model
{
    use BelongsToTeam;

    protected $fillable = [
        'team_id',
        'account_id',
        'supplier_id',
        'name',
        'phone',
        'contact_info',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::created(function (Distributor $distributor) {
            app(ChartOfAccountsSyncService::class)->syncDistributor($distributor);
        });

        static::updated(function (Distributor $distributor) {
            if ($distributor->wasChanged(['name', 'is_active'])) {
                app(ChartOfAccountsSyncService::class)->syncDistributor($distributor);
            }
        });
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(MerchantProduct::class);
    }
}