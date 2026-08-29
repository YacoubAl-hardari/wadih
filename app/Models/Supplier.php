<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Concerns\BelongsToTeam;
use App\Services\ChartOfAccountsSyncService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use BelongsToTeam;

    protected $fillable = [
        'team_id',
        'account_id',
        'name',
        'phone',
        'email',
        'tax_number',
        'balance',
        'is_active',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::created(function (Supplier $supplier) {
            app(ChartOfAccountsSyncService::class)->syncSupplier($supplier);
        });

        static::updated(function (Supplier $supplier) {
            if ($supplier->wasChanged(['name', 'is_active'])) {
                app(ChartOfAccountsSyncService::class)->syncSupplier($supplier);
            }
        });
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function distributors(): HasMany
    {
        return $this->hasMany(Distributor::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(MerchantProduct::class);
    }
}
