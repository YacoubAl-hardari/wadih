<?php

use App\Models\Team;
use App\Services\ChartOfAccountsSyncService;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
  public function up(): void
  {
    $syncService = app(ChartOfAccountsSyncService::class);

    foreach (Team::all() as $team) {
      $syncService->migrateTeamFromLegacy($team);
    }
  }

  public function down(): void
  {
    // لا يمكن التراجع بأمان دون فقدان ربط الحسابات الديناميكية
  }
};
