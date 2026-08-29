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
      $syncService->repairTreeForTeam($team);
    }
  }

  public function down(): void
  {
    //
  }
};
