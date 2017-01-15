<?php

namespace REBELinBLUE\Deployer\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use REBELinBLUE\Deployer\CheckUrl as CheckUrlModel;
use REBELinBLUE\Deployer\Jobs\RequestProjectCheckUrl;

/**
 * Schedule the url check.
 */
class CheckUrls extends Command
{
    use DispatchesJobs;

    const URLS_TO_CHECK = 10;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deployer:checkurls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Request the project check URL and notify when failed.';

    /**
     * Execute the console command.
     *
     * @return bool
     *
     * @dispatches RequestProjectCheckUrl
     */
    public function handle()
    {
        $period = [];

        $minute = intval(date('i'));
        if ($minute === 0) {
            $period = [60, 30, 10, 5];
        } else {
            if ($minute % 30 === 0) {
                $period = [30, 10, 5];
            } elseif ($minute % 10 === 0) {
                $period = [10, 5];
            } elseif ($minute % 5 === 0) {
                $period = [5];
            }
        }

        if (!empty($period)) {
            CheckUrlModel::whereIn('period', $period)->chunk(self::URLS_TO_CHECK, function ($urls) {
                $this->dispatch(new RequestProjectCheckUrl($urls));
            });
        }

        return true;
    }
}
