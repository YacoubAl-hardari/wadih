<?php

namespace App\Support;

final class ChartAccountCodes
{
  public const ASSETS = '1000';

  public const FIXED_ASSETS = '1100';

  public const FURNITURE = '1110';

  public const EQUIPMENT = '1120';

  public const VEHICLES = '1130';

  public const BUILDINGS = '1140';

  public const LAND = '1150';

  public const SOFTWARE = '1160';

  public const ACCUMULATED_DEPRECIATION = '1190';

  public const CURRENT_ASSETS = '1200';

  public const TREASURY = '1210';

  public const CASH_MAIN = '1211';

  public const BANK_AND_PAYMENTS = '1220';

  public const INVENTORY_GROUP = '1230';

  public const INVENTORY = '1231';

  public const INVENTORY_VARIANCE = '1232';

  public const RECEIVABLES = '1240';

  public const CUSTOMERS_PARENT = '1241';

  public const OTHER_RECEIVABLES = '1242';

  public const EMPLOYEE_ADVANCES = '1243';

  public const NOTES_RECEIVABLE = '1244';

  public const CASH_OVER_SHORT = '1245';

  public const CURRENCY_EXCHANGE = '1246';

  public const PREPAID_EXPENSES = '1250';

  public const LIABILITIES = '2000';

  public const CURRENT_LIABILITIES = '2100';

  public const SUPPLIERS_PARENT = '2110';

  public const DISTRIBUTORS_PARENT = '2115';

  public const EMPLOYEE_PAYABLES = '2120';

  public const ACCRUED_EXPENSES = '2125';

  public const OTHER_PAYABLES = '2130';

  public const CUSTOMER_PREPAID = '2131';

  public const LONG_TERM_LIABILITIES = '2200';

  public const EQUITY = '3000';

  public const CAPITAL = '3001';

  public const RETAINED_EARNINGS = '3002';

  public const OWNER_DRAWINGS = '3003';

  public const CURRENT_YEAR_PNL = '3004';

  public const INCOME_SUMMARY = '3005';

  public const REVENUE = '4000';

  public const SALES_REVENUE_GROUP = '4100';

  public const SALES_REVENUE = '4101';

  public const SERVICE_REVENUE = '4102';

  public const OTHER_REVENUE_GROUP = '4200';

  public const OTHER_OPERATING_REVENUE = '4201';

  public const SUBSCRIPTION_REVENUE = '4202';

  public const MISCELLANEOUS_REVENUE = '4203';

  public const COGS_GROUP = '5100';

  public const COGS = '5101';

  public const DIRECT_SERVICE_COST = '5102';

  public const CONSUMABLES_COST = '5103';

  public const PURCHASE_SHIPPING = '5104';

  public const ALLOWED_DISCOUNT = '5105';

  public const EXPENSES = '5000';

  public const ADMIN_EXPENSES_GROUP = '6100';

  public const RENT = '6101';

  public const ELECTRICITY = '6102';

  public const TELECOM = '6103';

  public const MAINTENANCE = '6104';

  public const WATER = '6105';

  public const GOVERNMENT_FEES = '6106';

  public const SALARIES = '6107';

  public const BONUSES = '6108';

  public const ALLOWANCES = '6109';

  public const SOCIAL_INSURANCE = '6110';

  public const LEASEHOLD_IMPROVEMENTS = '6111';

  public const END_OF_SERVICE = '6112';

  public const OFFICE_SUPPLIES = '6113';

  public const PRINTING = '6114';

  public const FUEL = '6115';

  public const HOSPITALITY = '6116';

  public const SOFTWARE_SUBSCRIPTIONS = '6117';

  public const SHIPPING_EXPENSE = '6118';

  public const GENERAL_ADMIN = '6119';

  public const OTHER_EXPENSES_GROUP = '6200';

  public const DEPRECIATION = '6201';

  public const BANK_FEES = '6202';

  public const MARKETING = '6203';

  public const BAD_DEBT = '6204';

  public const INVENTORY_ADJUSTMENT = '6205';

  public const OTHER_EXPENSES = '6206';

  public const VAT_GROUP = '2140';

  public const VAT_INPUT = '8001';

  public const VAT_OUTPUT = '8002';

  /** @return list<string> */
  public static function rootCodes(): array
  {
    return [
      self::ASSETS,
      self::LIABILITIES,
      self::EQUITY,
      self::REVENUE,
      self::EXPENSES,
    ];
  }

  /** @return array<string, string> */
  public static function legacyCodeMap(): array
  {
    return [
      '1001' => self::CASH_MAIN,
      '1101' => self::CUSTOMERS_PARENT,
      '1201' => self::INVENTORY,
      '1202' => self::INVENTORY_VARIANCE,
      '1301' => self::PREPAID_EXPENSES,
      '1401' => self::FURNITURE,
      '1402' => self::EQUIPMENT,
      '1499' => self::ACCUMULATED_DEPRECIATION,
      '2001' => self::SUPPLIERS_PARENT,
      '2002' => self::EMPLOYEE_PAYABLES,
      '2004' => self::ACCRUED_EXPENSES,
      '2005' => self::OTHER_PAYABLES,
      '2101' => self::CUSTOMER_PREPAID,
      '4001' => self::SERVICE_REVENUE,
      '4002' => self::OTHER_OPERATING_REVENUE,
      '4003' => self::SALES_REVENUE,
      '4004' => self::SUBSCRIPTION_REVENUE,
      '4005' => self::MISCELLANEOUS_REVENUE,
      '5001' => self::COGS,
      '5000' => self::COGS_GROUP,
      '5002' => self::DIRECT_SERVICE_COST,
      '5003' => self::CONSUMABLES_COST,
      '6001' => self::RENT,
      '6002' => self::TELECOM,
      '6003' => self::MARKETING,
      '6004' => self::MAINTENANCE,
      '6005' => self::SALARIES,
      '6006' => self::SOFTWARE_SUBSCRIPTIONS,
      '6007' => self::SHIPPING_EXPENSE,
      '6008' => self::GENERAL_ADMIN,
      '6009' => self::DEPRECIATION,
      '6010' => self::BANK_FEES,
      '6000' => self::EXPENSES,
      '8000' => self::VAT_GROUP,
    ];
  }
}
