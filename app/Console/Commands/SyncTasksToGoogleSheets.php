<?php

namespace App\Console\Commands;

use App\Services\GoogleSheetsSyncService;
use Illuminate\Console\Command;

class SyncTasksToGoogleSheets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:sync-google-sheets {--all : Force sync all tasks regardless of last sync time}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize inspection tasks and work orders to Google Sheets spreadsheet';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting Google Sheets inspection tasks synchronization...');

        $forceAll = (bool) $this->option('all');
        $results = GoogleSheetsSyncService::syncAllTasks($forceAll);

        $this->info("Synchronization finished: Total {$results['total']} tasks processed ({$results['synced']} synced, {$results['failed']} failed).");

        return self::SUCCESS;
    }
}
