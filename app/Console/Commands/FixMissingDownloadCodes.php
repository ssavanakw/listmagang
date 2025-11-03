<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Download;
use App\Models\User;
use Carbon\Carbon;

class FixMissingDownloadCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-missing-download-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing downloads with missing codes using User::createMemberCard()...');

        $downloads = Download::whereNull('code')->orWhere('code', '')->get();
        $fixed = 0;

        foreach ($downloads as $download) {
            // Match user by name (or improve with user_id if available)
            $user = \App\Models\User::where('name', $download->name)->first();

            if (!$user) {
                $this->warn("Skipping: user not found for '{$download->name}'");
                continue;
            }

            try {
                $user->createMemberCard(); // this handles everything
                $this->info("✅ Synced: {$user->name}");
                $fixed++;
            } catch (\Throwable $e) {
                $this->error("❌ Error syncing {$user->name}: {$e->getMessage()}");
            }
        }

        $this->info("Done! Synced $fixed membercard code(s).");
    }

}
