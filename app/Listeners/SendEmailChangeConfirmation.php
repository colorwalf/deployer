<?php

namespace REBELinBLUE\Deployer\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use REBELinBLUE\Deployer\Events\EmailChangeRequested;
use REBELinBLUE\Deployer\Notifications\System\ChangeEmail;

/**
 * Request email change handler.
 */
class SendEmailChangeConfirmation implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param EmailChangeRequested $event
     */
    public function handle(EmailChangeRequested $event)
    {
        $token = $event->user->requestEmailToken();

        $event->user->notify(new ChangeEmail($token));
    }
}
