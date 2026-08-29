<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('merchant_payment_accounts', function (Blueprint $table) {
      $table->foreignId('account_id')->nullable()->after('team_id')->constrained('accounts')->nullOnDelete();
    });

    Schema::table('merchant_customers', function (Blueprint $table) {
      $table->foreignId('account_id')->nullable()->after('team_id')->constrained('accounts')->nullOnDelete();
    });

    Schema::table('suppliers', function (Blueprint $table) {
      $table->foreignId('account_id')->nullable()->after('team_id')->constrained('accounts')->nullOnDelete();
    });

    Schema::table('distributors', function (Blueprint $table) {
      $table->foreignId('account_id')->nullable()->after('team_id')->constrained('accounts')->nullOnDelete();
    });
  }

  public function down(): void
  {
    Schema::table('distributors', function (Blueprint $table) {
      $table->dropConstrainedForeignId('account_id');
    });

    Schema::table('suppliers', function (Blueprint $table) {
      $table->dropConstrainedForeignId('account_id');
    });

    Schema::table('merchant_customers', function (Blueprint $table) {
      $table->dropConstrainedForeignId('account_id');
    });

    Schema::table('merchant_payment_accounts', function (Blueprint $table) {
      $table->dropConstrainedForeignId('account_id');
    });
  }
};
