<?php

namespace App\Models;

use App\Enums\MerchantPaymentAccountType;
use App\Models\Account;
use App\Models\Concerns\BelongsToTeam;
use App\Services\ChartOfAccountsSyncService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MerchantPaymentAccount extends Model
{
    use BelongsToTeam;

    protected $fillable = [
        'team_id',
        'account_id',
        'type',
        'name',
        'account_number',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'type' => MerchantPaymentAccountType::class,
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::created(function (MerchantPaymentAccount $account) {
            app(ChartOfAccountsSyncService::class)->syncPaymentAccount($account);
        });

        static::updated(function (MerchantPaymentAccount $account) {
            if ($account->wasChanged(['name', 'type', 'is_active'])) {
                app(ChartOfAccountsSyncService::class)->syncPaymentAccount($account);
            }
        });
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function posSales(): HasMany
    {
        return $this->hasMany(PosSale::class);
    }

    public function customerPayments(): HasMany
    {
        return $this->hasMany(MerchantCustomerPayment::class);
    }

    public function displayLabel(): string
    {
        return "{$this->name} — {$this->account_number}";
    }
}
