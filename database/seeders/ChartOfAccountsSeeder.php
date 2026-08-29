<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Team;
use App\Services\ChartOfAccountsSyncService;
use Illuminate\Database\Seeder;

class ChartOfAccountsSeeder extends Seeder
{
  public function run(?Team $team = null): void
  {
    $teams = $team ? collect([$team]) : Team::all();
    $syncService = app(ChartOfAccountsSyncService::class);

    foreach ($teams as $teamRecord) {
      $syncService->seedBaseTree($teamRecord);
    }
  }

  public function seedForTeam(Team $team, bool $force = false): void
  {
    $syncService = app(ChartOfAccountsSyncService::class);

    if ($force) {
      $syncService->seedBaseTree($team, skipIfExists: false);
    } else {
      $syncService->seedBaseTree($team);
    }

    $syncService->syncAllForTeam($team);
  }
}
