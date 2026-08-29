<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Concerns\BelongsToTeam;
use App\Services\ChartOfAccountsSyncService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MerchantCustomer extends Model
{
    use BelongsToTeam;

    protected $fillable = [
        'team_id',
        'account_id',
        'user_id',
        'name',
        'phone',
        'email',
        'balance',
        'credit_balance',
        'is_active',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'credit_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::created(function (MerchantCustomer $customer) {
            app(ChartOfAccountsSyncService::class)->syncCustomer($customer);
        });

        static::updated(function (MerchantCustomer $customer) {
            if ($customer->wasChanged(['name', 'is_active'])) {
                app(ChartOfAccountsSyncService::class)->syncCustomer($customer);
            }
        });
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function debtBalance(): float
    {
        return (float) $this->balance;
    }

    public function prepaidBalance(): float
    {
        return (float) $this->credit_balance;
    }

    public function hasPrepaidBalance(): bool
    {
        return $this->prepaidBalance() > 0;
    }

    public function hasDebt(): bool
    {
        return $this->debtBalance() > 0;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posSales(): HasMany
    {
        return $this->hasMany(PosSale::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(MerchantCustomerPayment::class);
    }

    public function financialTransfers(): HasMany
    {
        return $this->hasMany(MerchantCustomerFinancialTransfer::class);
    }

    public function statementShares(): HasMany
    {
        return $this->hasMany(MerchantCustomerStatementShare::class);
    }

    public function activeStatementShare(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MerchantCustomerStatementShare::class)
            ->where('is_active', true)
            ->latestOfMany('shared_at');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function isLinkedToUser(): bool
    {
        return $this->user_id !== null;
    }

    public function isStatementShared(): bool
    {
        if ($this->relationLoaded('activeStatementShare')) {
            return $this->activeStatementShare !== null;
        }

        return $this->activeStatementShare()->exists();
    }

    public static function acrossTeams(): Builder
    {
        return static::withoutGlobalScopes();
    }
}
