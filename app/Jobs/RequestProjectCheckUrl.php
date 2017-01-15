<?php

namespace REBELinBLUE\Deployer\Jobs;

use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use REBELinBLUE\Deployer\CheckUrl;

/**
 * Request the urls.
 */
class RequestProjectCheckUrl extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $links;

    /**
     * RequestProjectCheckUrl constructor.
     *
     * @param CheckUrl[] $links
     */
    public function __construct($links)
    {
        $this->links = $links;
    }

    /**
     * Execute the command.
     */
    public function handle()
    {
        foreach ($this->links as $link) {
            try {
                (new Client(['timeout'  => 30]))->get($link->url, [
                    'headers' => [
                        'User-Agent' => USER_AGENT,
                    ],
                ]);

                $has_error = false;
                $missed    = 0;
            } catch (\Exception $error) {
                $has_error = true;
                $missed    = $link->missed + 1;
            }

            $link->last_status = $has_error;
            $link->missed      = $missed;
            $link->save();

            // TODO: Throw event
            if ($has_error) {
                foreach ($link->project->notifications as $notification) {
                    try {
                        //$this->dispatch(new SlackNotify($notification, $link->notificationPayload()));
                    } catch (\Exception $error) {
                        // Don't worry about this error
                    }
                }
            }
        }
    }
}
