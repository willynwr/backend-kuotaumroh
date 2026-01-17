<?php

namespace App\Console\Commands;

use App\Models\Affiliate;
use App\Models\Freelance;
use App\Support\ReferralCodeGenerator;
use Illuminate\Console\Command;

class GenerateReferralCodes extends Command
{
    protected $signature = 'referral:generate-codes {--force : Regenerate all codes, not only missing ones}';

    protected $description = 'Generate unique ref_code for affiliates and freelances';

    public function handle(): int
    {
        $force = (bool) $this->option('force');

        $affiliateQuery = Affiliate::query();
        $freelanceQuery = Freelance::query();

        if (!$force) {
            $affiliateQuery->where(function ($q) {
                $q->whereNull('ref_code')->orWhere('ref_code', '');
            });
            $freelanceQuery->where(function ($q) {
                $q->whereNull('ref_code')->orWhere('ref_code', '');
            });
        }

        $affiliateCount = 0;
        $affiliateQuery->orderBy('id')->chunkById(200, function ($affiliates) use (&$affiliateCount) {
            foreach ($affiliates as $affiliate) {
                $affiliate->ref_code = ReferralCodeGenerator::generateUniqueCode();
                $affiliate->save();
                $affiliateCount++;
            }
        });

        $freelanceCount = 0;
        $freelanceQuery->orderBy('id')->chunkById(200, function ($freelances) use (&$freelanceCount) {
            foreach ($freelances as $freelance) {
                $freelance->ref_code = ReferralCodeGenerator::generateUniqueCode();
                $freelance->save();
                $freelanceCount++;
            }
        });

        $this->info("Updated affiliates: {$affiliateCount}");
        $this->info("Updated freelances: {$freelanceCount}");

        return self::SUCCESS;
    }
}
