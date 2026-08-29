<?php

namespace App\Services;

use App\Enums\AccountType;
use App\Enums\MerchantPaymentAccountType;
use App\Enums\NormalBalance;
use App\Models\Account;
use App\Models\Distributor;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\MerchantCustomer;
use App\Models\MerchantCustomerPayment;
use App\Models\MerchantPaymentAccount;
use App\Models\PosSale;
use App\Models\Supplier;
use App\Models\Team;
use App\Support\ChartAccountCodes;
use Illuminate\Support\Facades\DB;

class ChartOfAccountsSyncService
{
  public function baseTree(): array
  {
    return [
      ['code' => ChartAccountCodes::ASSETS, 'name' => 'الأصول', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT, 'is_system' => true, 'children' => [
        ['code' => ChartAccountCodes::FIXED_ASSETS, 'name' => 'الأصول الثابتة', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT, 'is_system' => true, 'children' => [
          ['code' => ChartAccountCodes::FURNITURE, 'name' => 'أثاث', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::EQUIPMENT, 'name' => 'الأجهزة والمعدات', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::VEHICLES, 'name' => 'وسائل النقل', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::BUILDINGS, 'name' => 'مباني', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::LAND, 'name' => 'أراضي', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::SOFTWARE, 'name' => 'برامج ومواقع', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::ACCUMULATED_DEPRECIATION, 'name' => 'مجمع إهلاك الأصول الثابتة', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::CREDIT],
        ]],
        ['code' => ChartAccountCodes::CURRENT_ASSETS, 'name' => 'الأصول المتداولة', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT, 'is_system' => true, 'children' => [
          ['code' => ChartAccountCodes::TREASURY, 'name' => 'الخزينة', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT, 'is_system' => true, 'children' => [
            ['code' => ChartAccountCodes::CASH_MAIN, 'name' => 'الخزينة الأساسية', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT],
          ]],
          ['code' => ChartAccountCodes::BANK_AND_PAYMENTS, 'name' => 'البنك ووسائل الدفع', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT, 'is_system' => true],
          ['code' => ChartAccountCodes::INVENTORY_GROUP, 'name' => 'المخزون', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT, 'is_system' => true, 'children' => [
            ['code' => ChartAccountCodes::INVENTORY, 'name' => 'مخزون البضائع', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT],
            ['code' => ChartAccountCodes::INVENTORY_VARIANCE, 'name' => 'فوارق جرد المخزون', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT],
          ]],
          ['code' => ChartAccountCodes::RECEIVABLES, 'name' => 'المدينون', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT, 'is_system' => true, 'children' => [
            ['code' => ChartAccountCodes::CUSTOMERS_PARENT, 'name' => 'العملاء', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT, 'is_system' => true],
            ['code' => ChartAccountCodes::OTHER_RECEIVABLES, 'name' => 'أطراف مدينة أخرى', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT],
            ['code' => ChartAccountCodes::EMPLOYEE_ADVANCES, 'name' => 'عهد الموظفين', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT],
            ['code' => ChartAccountCodes::NOTES_RECEIVABLE, 'name' => 'أوراق القبض', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT],
            ['code' => ChartAccountCodes::CASH_OVER_SHORT, 'name' => 'عجز وزيادة الصندوق', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT],
            ['code' => ChartAccountCodes::CURRENCY_EXCHANGE, 'name' => 'تغيير عملة', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT],
          ]],
          ['code' => ChartAccountCodes::PREPAID_EXPENSES, 'name' => 'مصروفات مدفوعة مقدماً', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT],
        ]],
      ]],
      ['code' => ChartAccountCodes::LIABILITIES, 'name' => 'الالتزامات', 'type' => AccountType::LIABILITY, 'normal_balance' => NormalBalance::CREDIT, 'is_system' => true, 'children' => [
        ['code' => ChartAccountCodes::CURRENT_LIABILITIES, 'name' => 'الخصوم المتداولة', 'type' => AccountType::LIABILITY, 'normal_balance' => NormalBalance::CREDIT, 'is_system' => true, 'children' => [
          ['code' => ChartAccountCodes::SUPPLIERS_PARENT, 'name' => 'الموردون', 'type' => AccountType::LIABILITY, 'normal_balance' => NormalBalance::CREDIT, 'is_system' => true],
          ['code' => ChartAccountCodes::DISTRIBUTORS_PARENT, 'name' => 'الموزعون', 'type' => AccountType::LIABILITY, 'normal_balance' => NormalBalance::CREDIT, 'is_system' => true],
          ['code' => ChartAccountCodes::EMPLOYEE_PAYABLES, 'name' => 'مستحقات الموظفين والعمولات', 'type' => AccountType::LIABILITY, 'normal_balance' => NormalBalance::CREDIT],
          ['code' => ChartAccountCodes::ACCRUED_EXPENSES, 'name' => 'مصاريف مستحقة الدفع', 'type' => AccountType::LIABILITY, 'normal_balance' => NormalBalance::CREDIT],
          ['code' => ChartAccountCodes::OTHER_PAYABLES, 'name' => 'التزامات مستحقة أخرى', 'type' => AccountType::LIABILITY, 'normal_balance' => NormalBalance::CREDIT],
          ['code' => ChartAccountCodes::CUSTOMER_PREPAID, 'name' => 'أرصدة عملاء دائنة (دفعات مقدمة)', 'type' => AccountType::LIABILITY, 'normal_balance' => NormalBalance::CREDIT, 'is_system' => true],
          ['code' => ChartAccountCodes::VAT_GROUP, 'name' => 'ضريبة القيمة المضافة', 'type' => AccountType::LIABILITY, 'normal_balance' => NormalBalance::CREDIT, 'is_system' => true, 'children' => [
            ['code' => ChartAccountCodes::VAT_INPUT, 'name' => 'ضريبة القيمة المضافة — مدخلات (قابلة للاسترداد)', 'type' => AccountType::ASSET, 'normal_balance' => NormalBalance::DEBIT],
            ['code' => ChartAccountCodes::VAT_OUTPUT, 'name' => 'ضريبة القيمة المضافة — مخرجات (مستحقة)', 'type' => AccountType::LIABILITY, 'normal_balance' => NormalBalance::CREDIT],
          ]],
        ]],
        ['code' => ChartAccountCodes::LONG_TERM_LIABILITIES, 'name' => 'الخصوم طويلة الأجل', 'type' => AccountType::LIABILITY, 'normal_balance' => NormalBalance::CREDIT, 'is_system' => true],
      ]],
      ['code' => ChartAccountCodes::EQUITY, 'name' => 'حقوق الملكية', 'type' => AccountType::EQUITY, 'normal_balance' => NormalBalance::CREDIT, 'is_system' => true, 'children' => [
        ['code' => ChartAccountCodes::CAPITAL, 'name' => 'رأس المال', 'type' => AccountType::EQUITY, 'normal_balance' => NormalBalance::CREDIT],
        ['code' => ChartAccountCodes::RETAINED_EARNINGS, 'name' => 'الأرباح المحتجزة', 'type' => AccountType::EQUITY, 'normal_balance' => NormalBalance::CREDIT],
        ['code' => ChartAccountCodes::OWNER_DRAWINGS, 'name' => 'مسحوبات المالك', 'type' => AccountType::EQUITY, 'normal_balance' => NormalBalance::DEBIT],
        ['code' => ChartAccountCodes::CURRENT_YEAR_PNL, 'name' => 'صافي ربح/خسارة السنة الحالية', 'type' => AccountType::EQUITY, 'normal_balance' => NormalBalance::CREDIT],
        ['code' => ChartAccountCodes::INCOME_SUMMARY, 'name' => 'ملخص الدخل (حساب إغلاق)', 'type' => AccountType::EQUITY, 'normal_balance' => NormalBalance::CREDIT, 'is_system' => true],
      ]],
      ['code' => ChartAccountCodes::REVENUE, 'name' => 'الإيرادات', 'type' => AccountType::REVENUE, 'normal_balance' => NormalBalance::CREDIT, 'is_system' => true, 'children' => [
        ['code' => ChartAccountCodes::SALES_REVENUE_GROUP, 'name' => 'إيرادات المبيعات', 'type' => AccountType::REVENUE, 'normal_balance' => NormalBalance::CREDIT, 'is_system' => true, 'children' => [
          ['code' => ChartAccountCodes::SALES_REVENUE, 'name' => 'إيرادات مبيعات البضائع', 'type' => AccountType::REVENUE, 'normal_balance' => NormalBalance::CREDIT],
          ['code' => ChartAccountCodes::SERVICE_REVENUE, 'name' => 'إيرادات الخدمات', 'type' => AccountType::REVENUE, 'normal_balance' => NormalBalance::CREDIT],
        ]],
        ['code' => ChartAccountCodes::OTHER_REVENUE_GROUP, 'name' => 'إيرادات أخرى', 'type' => AccountType::REVENUE, 'normal_balance' => NormalBalance::CREDIT, 'is_system' => true, 'children' => [
          ['code' => ChartAccountCodes::OTHER_OPERATING_REVENUE, 'name' => 'إيرادات تشغيلية أخرى', 'type' => AccountType::REVENUE, 'normal_balance' => NormalBalance::CREDIT],
          ['code' => ChartAccountCodes::SUBSCRIPTION_REVENUE, 'name' => 'إيرادات الاشتراكات والعقود', 'type' => AccountType::REVENUE, 'normal_balance' => NormalBalance::CREDIT],
          ['code' => ChartAccountCodes::MISCELLANEOUS_REVENUE, 'name' => 'إيرادات متنوعة', 'type' => AccountType::REVENUE, 'normal_balance' => NormalBalance::CREDIT],
        ]],
      ]],
      ['code' => ChartAccountCodes::EXPENSES, 'name' => 'المصروفات', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT, 'is_system' => true, 'children' => [
        ['code' => ChartAccountCodes::COGS_GROUP, 'name' => 'تكلفة المبيعات', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT, 'is_system' => true, 'children' => [
          ['code' => ChartAccountCodes::COGS, 'name' => 'تكلفة البضائع المباعة', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::DIRECT_SERVICE_COST, 'name' => 'تكلفة الخدمات المباشرة', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::CONSUMABLES_COST, 'name' => 'مستلزمات تشغيل مستهلكة', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::PURCHASE_SHIPPING, 'name' => 'شحن مشتريات', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::ALLOWED_DISCOUNT, 'name' => 'خصم مسموح به', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
        ]],
        ['code' => ChartAccountCodes::ADMIN_EXPENSES_GROUP, 'name' => 'مصروفات إدارية وعمومية', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT, 'is_system' => true, 'children' => [
          ['code' => ChartAccountCodes::RENT, 'name' => 'إيجار', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::ELECTRICITY, 'name' => 'كهرباء', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::TELECOM, 'name' => 'بريد وإنترنت وهاتف', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::MAINTENANCE, 'name' => 'صيانة وإصلاح', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::WATER, 'name' => 'مياه', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::GOVERNMENT_FEES, 'name' => 'مصاريف حكومية', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::SALARIES, 'name' => 'أجور ورواتب ومافي حكمها', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::BONUSES, 'name' => 'مكافآت وحوافز وعمولات', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::ALLOWANCES, 'name' => 'بدلات سكنية وانتقالات وطيران', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::SOCIAL_INSURANCE, 'name' => 'حصة التأمينات الاجتماعية', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::LEASEHOLD_IMPROVEMENTS, 'name' => 'تحسينات مؤجرة', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::END_OF_SERVICE, 'name' => 'مصروف نهاية الخدمة', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::OFFICE_SUPPLIES, 'name' => 'أدوات مكتبية', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::PRINTING, 'name' => 'مطبوعات وكروت', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::FUEL, 'name' => 'محروقات وزيوت وشحوم', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::HOSPITALITY, 'name' => 'ضيافة وبوفية', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::SOFTWARE_SUBSCRIPTIONS, 'name' => 'اشتراكات البرمجيات والأنظمة', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::SHIPPING_EXPENSE, 'name' => 'مصاريف النقل والشحن', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::GENERAL_ADMIN, 'name' => 'مصاريف إدارية ونثرية', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
        ]],
        ['code' => ChartAccountCodes::OTHER_EXPENSES_GROUP, 'name' => 'مصروفات أخرى', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT, 'is_system' => true, 'children' => [
          ['code' => ChartAccountCodes::DEPRECIATION, 'name' => 'مصروف الإهلاك', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::BANK_FEES, 'name' => 'رسوم بنكية وعمولات مالية', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::MARKETING, 'name' => 'دعاية وإعلان', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::BAD_DEBT, 'name' => 'الديون المعدومة', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::INVENTORY_ADJUSTMENT, 'name' => 'عجز وزيادة المخزون', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
          ['code' => ChartAccountCodes::OTHER_EXPENSES, 'name' => 'مصروفات أخرى', 'type' => AccountType::EXPENSE, 'normal_balance' => NormalBalance::DEBIT],
        ]],
      ]],
    ];
  }

  public function repairTreeForTeam(Team $team): void
  {
    DB::transaction(function () use ($team) {
      $this->normalizeConflictingRoots($team);

      try {
        Account::scoped(['team_id' => $team->id])->fixTree();
      } catch (\Throwable) {
        // الشجرة قد تكون تالفة — نكمل بإعادة بناء parent_id
      }

      foreach ($this->baseTree() as $group) {
        $this->upsertAccountTree($team, $group);
      }

      $this->syncAllForTeam($team);
      $this->reparentOrphanedAccounts($team);
      $this->cleanupLegacyAccounts($team);

      Account::scoped(['team_id' => $team->id])->fixTree();
    });
  }

  public function seedBaseTree(Team $team, bool $skipIfExists = true): void
  {
    if ($skipIfExists && Account::where('team_id', $team->id)->exists()) {
      $this->repairTreeForTeam($team);

      return;
    }

    DB::transaction(function () use ($team) {
      foreach ($this->baseTree() as $group) {
        $this->upsertAccountTree($team, $group);
      }

      Account::scoped(['team_id' => $team->id])->fixTree();
    });

    $this->syncAllForTeam($team);
  }

  public function syncAllForTeam(Team $team): void
  {
    MerchantPaymentAccount::withoutGlobalScopes()
      ->where('team_id', $team->id)
      ->each(fn (MerchantPaymentAccount $account) => $this->syncPaymentAccount($account));

    MerchantCustomer::withoutGlobalScopes()
      ->where('team_id', $team->id)
      ->each(fn (MerchantCustomer $customer) => $this->syncCustomer($customer));

    Supplier::withoutGlobalScopes()
      ->where('team_id', $team->id)
      ->each(fn (Supplier $supplier) => $this->syncSupplier($supplier));

    Distributor::withoutGlobalScopes()
      ->where('team_id', $team->id)
      ->each(fn (Distributor $distributor) => $this->syncDistributor($distributor));
  }

  public function syncPaymentAccount(MerchantPaymentAccount $paymentAccount): ?Account
  {
    $parent = $this->requireParent($paymentAccount->team_id, ChartAccountCodes::BANK_AND_PAYMENTS);

    if (! $parent) {
      return null;
    }

    $account = $this->findLinkedAccount(
      $paymentAccount->team_id,
      $paymentAccount->account_id,
      $this->paymentAccountCode($paymentAccount),
    );

    if ($account) {
      $account->update([
        'name' => $this->paymentAccountLabel($paymentAccount),
        'is_active' => $paymentAccount->is_active,
      ]);

      $this->ensureUnderParent($account, $parent);

      if ((int) $paymentAccount->account_id !== (int) $account->id) {
        $paymentAccount->updateQuietly(['account_id' => $account->id]);
      }

      return $account->fresh();
    }

    $account = Account::create([
      'team_id' => $paymentAccount->team_id,
      'parent_id' => $parent->id,
      'code' => $this->paymentAccountCode($paymentAccount),
      'name' => $this->paymentAccountLabel($paymentAccount),
      'type' => AccountType::ASSET,
      'normal_balance' => NormalBalance::DEBIT,
      'is_system' => true,
      'is_active' => $paymentAccount->is_active,
    ]);

    $paymentAccount->updateQuietly(['account_id' => $account->id]);

    return $account;
  }

  public function syncCustomer(MerchantCustomer $customer): ?Account
  {
    $parent = $this->requireParent($customer->team_id, ChartAccountCodes::CUSTOMERS_PARENT);

    if (! $parent) {
      return null;
    }

    $account = $this->findLinkedAccount(
      $customer->team_id,
      $customer->account_id,
      $this->customerAccountCode($customer),
    );

    if ($account) {
      $account->update([
        'name' => $customer->name,
        'is_active' => $customer->is_active,
      ]);

      $this->ensureUnderParent($account, $parent);

      if ((int) $customer->account_id !== (int) $account->id) {
        $customer->updateQuietly(['account_id' => $account->id]);
      }

      return $account->fresh();
    }

    $account = Account::create([
      'team_id' => $customer->team_id,
      'parent_id' => $parent->id,
      'code' => $this->customerAccountCode($customer),
      'name' => $customer->name,
      'type' => AccountType::ASSET,
      'normal_balance' => NormalBalance::DEBIT,
      'is_system' => true,
      'is_active' => $customer->is_active,
    ]);

    $customer->updateQuietly(['account_id' => $account->id]);

    return $account;
  }

  public function syncSupplier(Supplier $supplier): ?Account
  {
    $parent = $this->requireParent($supplier->team_id, ChartAccountCodes::SUPPLIERS_PARENT);

    if (! $parent) {
      return null;
    }

    $account = $this->findLinkedAccount(
      $supplier->team_id,
      $supplier->account_id,
      $this->supplierAccountCode($supplier),
    );

    if ($account) {
      $account->update([
        'name' => $supplier->name,
        'is_active' => $supplier->is_active,
      ]);

      $this->ensureUnderParent($account, $parent);

      if ((int) $supplier->account_id !== (int) $account->id) {
        $supplier->updateQuietly(['account_id' => $account->id]);
      }

      return $account->fresh();
    }

    $account = Account::create([
      'team_id' => $supplier->team_id,
      'parent_id' => $parent->id,
      'code' => $this->supplierAccountCode($supplier),
      'name' => $supplier->name,
      'type' => AccountType::LIABILITY,
      'normal_balance' => NormalBalance::CREDIT,
      'is_system' => true,
      'is_active' => $supplier->is_active,
    ]);

    $supplier->updateQuietly(['account_id' => $account->id]);

    return $account;
  }

  public function syncDistributor(Distributor $distributor): ?Account
  {
    $parent = $this->requireParent($distributor->team_id, ChartAccountCodes::DISTRIBUTORS_PARENT);

    if (! $parent) {
      return null;
    }

    $account = $this->findLinkedAccount(
      $distributor->team_id,
      $distributor->account_id,
      $this->distributorAccountCode($distributor),
    );

    if ($account) {
      $account->update([
        'name' => $distributor->name,
        'is_active' => $distributor->is_active,
      ]);

      $this->ensureUnderParent($account, $parent);

      if ((int) $distributor->account_id !== (int) $account->id) {
        $distributor->updateQuietly(['account_id' => $account->id]);
      }

      return $account->fresh();
    }

    $account = Account::create([
      'team_id' => $distributor->team_id,
      'parent_id' => $parent->id,
      'code' => $this->distributorAccountCode($distributor),
      'name' => $distributor->name,
      'type' => AccountType::LIABILITY,
      'normal_balance' => NormalBalance::CREDIT,
      'is_system' => true,
      'is_active' => $distributor->is_active,
    ]);

    $distributor->updateQuietly(['account_id' => $account->id]);

    return $account;
  }

  public function migrateTeamFromLegacy(Team $team): void
  {
    if (! $this->hasLegacyStructure($team)) {
      $this->syncAllForTeam($team);

      return;
    }

    DB::transaction(function () use ($team) {
      $legacyAccounts = Account::where('team_id', $team->id)->get();
      $legacyIdToCode = $legacyAccounts->pluck('code', 'id');

      foreach ($legacyAccounts as $account) {
        $account->update(['code' => 'LEGACY_'.$account->id]);
      }

      foreach ($this->baseTree() as $group) {
        $this->upsertAccountTree($team, $group);
      }

      Account::scoped(['team_id' => $team->id])->fixTree();

      $newAccountsByCode = Account::where('team_id', $team->id)
        ->where('code', 'not like', 'LEGACY_%')
        ->get()
        ->keyBy('code');

      $this->syncAllForTeam($team);

      $paymentAccounts = MerchantPaymentAccount::withoutGlobalScopes()
        ->where('team_id', $team->id)
        ->get()
        ->keyBy('id');

      $customers = MerchantCustomer::withoutGlobalScopes()
        ->where('team_id', $team->id)
        ->get()
        ->keyBy('id');

      $defaultBankAccount = $paymentAccounts
        ->first(fn (MerchantPaymentAccount $a) => $a->type === MerchantPaymentAccountType::BANK && $a->is_default)
        ?? $paymentAccounts->first(fn (MerchantPaymentAccount $a) => $a->type === MerchantPaymentAccountType::BANK);

      $defaultCardAccount = $paymentAccounts
        ->first(fn (MerchantPaymentAccount $a) => $a->type === MerchantPaymentAccountType::CARD && $a->is_default)
        ?? $paymentAccounts->first(fn (MerchantPaymentAccount $a) => $a->type === MerchantPaymentAccountType::CARD);

      $journalLines = JournalLine::query()
        ->whereHas('journalEntry', fn ($query) => $query->where('team_id', $team->id))
        ->with(['journalEntry', 'account'])
        ->get();

      foreach ($journalLines as $line) {
        $oldCode = $legacyIdToCode[$line->account_id] ?? null;

        if (! $oldCode) {
          continue;
        }

        $newAccountId = $this->resolveMigratedAccountId(
          $team,
          $oldCode,
          $line,
          $newAccountsByCode,
          $paymentAccounts,
          $customers,
          $defaultBankAccount,
          $defaultCardAccount,
        );

        if ($newAccountId) {
          $line->update(['account_id' => $newAccountId]);
        }
      }

      Account::where('team_id', $team->id)
        ->where('code', 'like', 'LEGACY_%')
        ->each(function (Account $account) {
          if (! $account->journalLines()->exists()) {
            $account->delete();
          }
        });
    });
  }

  public function hasLegacyStructure(Team $team): bool
  {
    return Account::where('team_id', $team->id)
      ->where('code', '1001')
      ->exists();
  }

  public function accountCodeForPaymentAccount(?MerchantPaymentAccount $paymentAccount, Team $team): string
  {
    if ($paymentAccount) {
      $account = $this->syncPaymentAccount($paymentAccount);

      if ($account) {
        return $account->code;
      }
    }

    return ChartAccountCodes::CASH_MAIN;
  }

  public function accountCodeForCustomer(?MerchantCustomer $customer): string
  {
    if ($customer) {
      $account = $this->syncCustomer($customer);

      if ($account) {
        return $account->code;
      }
    }

    return ChartAccountCodes::CUSTOMERS_PARENT;
  }

  protected function resolveMigratedAccountId(
    Team $team,
    string $oldCode,
    JournalLine $line,
    $newAccountsByCode,
    $paymentAccounts,
    $customers,
    ?MerchantPaymentAccount $defaultBankAccount,
    ?MerchantPaymentAccount $defaultCardAccount,
  ): ?int {
    if ($oldCode === '1002') {
      $paymentAccountId = $this->resolvePaymentAccountIdFromJournalLine($line, $paymentAccounts);

      if ($paymentAccountId && $paymentAccounts->has($paymentAccountId)) {
        return $this->syncPaymentAccount($paymentAccounts[$paymentAccountId])?->id;
      }

      if ($defaultBankAccount) {
        return $this->syncPaymentAccount($defaultBankAccount)?->id;
      }
    }

    if ($oldCode === '1003') {
      $paymentAccountId = $this->resolvePaymentAccountIdFromJournalLine($line, $paymentAccounts);

      if ($paymentAccountId && $paymentAccounts->has($paymentAccountId)) {
        return $this->syncPaymentAccount($paymentAccounts[$paymentAccountId])?->id;
      }

      if ($defaultCardAccount) {
        return $this->syncPaymentAccount($defaultCardAccount)?->id;
      }
    }

    if ($oldCode === '1101' && $line->subledger_type === MerchantCustomer::class && $line->subledger_id) {
      $customer = $customers->get($line->subledger_id);

      if ($customer) {
        return $this->syncCustomer($customer)?->id;
      }
    }

    if ($oldCode === '1101') {
      return $newAccountsByCode->get(ChartAccountCodes::OTHER_RECEIVABLES)?->id;
    }

    $mappedCode = ChartAccountCodes::legacyCodeMap()[$oldCode] ?? $oldCode;

    return $newAccountsByCode->get($mappedCode)?->id;
  }

  protected function resolvePaymentAccountIdFromJournalLine(JournalLine $line, $paymentAccounts): ?int
  {
    $entry = $line->journalEntry;

    if (! $entry?->reference_type || ! $entry->reference_id) {
      return null;
    }

    if ($entry->reference_type === PosSale::class) {
      return PosSale::withoutGlobalScopes()->find($entry->reference_id)?->merchant_payment_account_id;
    }

    if ($entry->reference_type === MerchantCustomerPayment::class) {
      return MerchantCustomerPayment::withoutGlobalScopes()->find($entry->reference_id)?->merchant_payment_account_id;
    }

    return null;
  }

  protected function requireParent(int $teamId, string $code): ?Account
  {
    return Account::where('team_id', $teamId)->where('code', $code)->first();
  }

  protected function paymentAccountCode(MerchantPaymentAccount $paymentAccount): string
  {
    return '122'.str_pad((string) $paymentAccount->id, 4, '0', STR_PAD_LEFT);
  }

  protected function customerAccountCode(MerchantCustomer $customer): string
  {
    return '1241'.str_pad((string) $customer->id, 4, '0', STR_PAD_LEFT);
  }

  protected function supplierAccountCode(Supplier $supplier): string
  {
    return '2110'.str_pad((string) $supplier->id, 4, '0', STR_PAD_LEFT);
  }

  protected function distributorAccountCode(Distributor $distributor): string
  {
    return '2115'.str_pad((string) $distributor->id, 4, '0', STR_PAD_LEFT);
  }

  protected function paymentAccountLabel(MerchantPaymentAccount $paymentAccount): string
  {
    $typeLabel = $paymentAccount->type === MerchantPaymentAccountType::BANK ? 'بنك' : 'بطاقة';

    return "{$paymentAccount->name} — {$typeLabel}";
  }

  protected function normalizeConflictingRoots(Team $team): void
  {
    $legacyCodes = ['8000', '6000'];

    $cogsAt5000 = Account::where('team_id', $team->id)
      ->where('code', ChartAccountCodes::EXPENSES)
      ->where('name', 'like', '%تكلفة%')
      ->first();

    if ($cogsAt5000) {
      $cogsAt5000->update(['code' => 'LEGACY_'.$cogsAt5000->id]);
    }

    foreach ($legacyCodes as $code) {
      $account = Account::where('team_id', $team->id)->where('code', $code)->first();

      if ($account) {
        $account->update(['code' => 'LEGACY_'.$account->id]);
      }
    }
  }

  protected function reparentOrphanedAccounts(Team $team): void
  {
    $allowedRoots = ChartAccountCodes::rootCodes();

    Account::where('team_id', $team->id)
      ->whereIsRoot()
      ->get()
      ->each(function (Account $account) use ($team, $allowedRoots) {
        if (in_array($account->code, $allowedRoots, true)) {
          return;
        }

        if (str_starts_with($account->code, 'LEGACY_')) {
          return;
        }

        $parent = $this->resolveParentForOrphan($team, $account);

        if ($parent) {
          $this->ensureUnderParent($account, $parent);
        }
      });
  }

  protected function cleanupLegacyAccounts(Team $team): void
  {
    Account::where('team_id', $team->id)
      ->where('code', 'like', 'LEGACY_%')
      ->orderByDesc('id')
      ->get()
      ->each(function (Account $account) {
        if ($account->journalLines()->exists()) {
          return;
        }

        if ($account->children()->exists()) {
          $account->children()->each(function (Account $child) use ($account) {
            if ($child->journalLines()->exists()) {
              return;
            }

            $child->delete();
          });
        }

        if (! $account->journalLines()->exists() && ! $account->children()->exists()) {
          $account->delete();
        }
      });
  }

  protected function resolveParentForOrphan(Team $team, Account $account): ?Account
  {
    $code = $account->code;

    if (str_starts_with($code, '122') && strlen($code) > strlen(ChartAccountCodes::BANK_AND_PAYMENTS)) {
      return $this->requireParent($team->id, ChartAccountCodes::BANK_AND_PAYMENTS);
    }

    if (str_starts_with($code, '1241') && strlen($code) > strlen(ChartAccountCodes::CUSTOMERS_PARENT)) {
      return $this->requireParent($team->id, ChartAccountCodes::CUSTOMERS_PARENT);
    }

    if (str_starts_with($code, '2110') && strlen($code) > strlen(ChartAccountCodes::SUPPLIERS_PARENT)) {
      return $this->requireParent($team->id, ChartAccountCodes::SUPPLIERS_PARENT);
    }

    if (str_starts_with($code, '2115') && strlen($code) > strlen(ChartAccountCodes::DISTRIBUTORS_PARENT)) {
      return $this->requireParent($team->id, ChartAccountCodes::DISTRIBUTORS_PARENT);
    }

    if (in_array($code, [ChartAccountCodes::VAT_INPUT, ChartAccountCodes::VAT_OUTPUT], true)) {
      return $this->requireParent($team->id, ChartAccountCodes::VAT_GROUP);
    }

    return null;
  }

  protected function findLinkedAccount(int $teamId, ?int $accountId, string $code): ?Account
  {
    if ($accountId) {
      $account = Account::where('team_id', $teamId)->find($accountId);

      if ($account) {
        return $account;
      }
    }

    return Account::where('team_id', $teamId)->where('code', $code)->first();
  }

  protected function ensureUnderParent(Account $account, Account $parent): void
  {
    if ($account->id === $parent->id) {
      return;
    }

    $account->refresh();
    $parent->refresh();

    if ((int) $account->parent_id === (int) $parent->id) {
      return;
    }

    // شجرة تالفة: الأب أصبح داخل فرع الابن — نفصله أولاً
    if ($parent->isDescendantOf($account)) {
      Account::where('id', $parent->id)->update(['parent_id' => null]);
      $parent->refresh();
    }

    Account::where('id', $account->id)->update(['parent_id' => $parent->id]);
    $account->refresh();
  }

  protected function upsertAccountTree(Team $team, array $node, ?Account $parent = null): Account
  {
    $account = Account::where('team_id', $team->id)->where('code', $node['code'])->first();

    $attributes = [
      'name' => $node['name'],
      'type' => $node['type'],
      'normal_balance' => $node['normal_balance'],
      'is_system' => $node['is_system'] ?? true,
      'is_active' => true,
    ];

    if ($account) {
      $account->update($attributes);

      if ($parent) {
        $this->ensureUnderParent($account, $parent);
      }
    } else {
      $account = Account::create(array_merge([
        'team_id' => $team->id,
        'code' => $node['code'],
        'parent_id' => $parent?->id,
      ], $attributes));
    }

    foreach ($node['children'] ?? [] as $child) {
      $this->upsertAccountTree($team, $child, $account);
    }

    return $account;
  }

  protected function createAccountTree(Team $team, array $node, ?Account $parent = null): void
  {
    $this->upsertAccountTree($team, $node, $parent);
  }
}
