<?php

namespace REBELinBLUE\Deployer\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use REBELinBLUE\Deployer\Events\UserWasCreated;
use REBELinBLUE\Deployer\Notifications\System\NewAccount;

/**
 * Sends an email when the user has been created.
 */
class SendSignupEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param UserWasCreated $event
     */
    public function handle(UserWasCreated $event)
    {
        $event->user->notify(new NewAccount($event->password));
    }
}
