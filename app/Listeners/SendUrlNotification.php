<?php

namespace REBELinBLUE\Deployer\Listeners;

use Illuminate\Foundation\Bus\DispatchesJobs;
use REBELinBLUE\Deployer\Events\UrlChanged;
use REBELinBLUE\Deployer\Events\UrlUp;
use REBELinBLUE\Deployer\Notifications\Configurable\UrlDown;
use REBELinBLUE\Deployer\Notifications\Configurable\UrlRecovered;

/**
 * Event handler class for URL notifications.
 **/
class SendUrlNotification
{
    use DispatchesJobs;

    /**
     * Handle the event.
     *
     * @param UrlChanged $event
     */
    public function handle(UrlChanged $event)
    {
        $url = $event->url;

        $notification = UrlRecovered::class;
        $event        = 'link_recovered';
        if (!$url->isHealthy()) {
            $notification = UrlDown::class;
            $event        = 'link_down';

            if ($url->missed > 1) {
                $event = 'link_still_down';
            }
        }

        foreach ($url->project->channels->where('on_' . $event, true) as $channel) {
            $channel->notify(new $notification($url));
        }
    }
}
