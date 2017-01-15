<?php

namespace REBELinBLUE\Deployer\Notifications\Configurable;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use NotificationChannels\HipChat\HipChatMessage;
use NotificationChannels\Twilio\TwilioSmsMessage as TwilioMessage;
use NotificationChannels\Webhook\WebhookMessage;
use REBELinBLUE\Deployer\Channel;
use REBELinBLUE\Deployer\Notifications\Notification;

/**
 * Base class for URL notifications.
 */
abstract class UrlChanged extends Notification
{
}

//public function notificationPayload()
//{
//    $message = Lang::get('checkUrls.message', ['link' => $this->title]);
//
//    $payload = [
//        'attachments' => [
//            [
//                'fallback' => $message,
//                'text'     => $message,
//                'color'    => 'danger',
//                'fields'   => [
//                    [
//                        'title' => Lang::get('notifications.project'),
//                        'value' => sprintf(
//                            '<%s|%s>',
//                            route('projects', ['id' => $this->project_id]),
//                            $this->project->name
//                        ),
//                        'short' => true,
//                    ],
//                ],
//                'footer' => Lang::get('app.name'),
//                'ts'     => time(),
//            ],
//        ],
//    ];
//
//    return $payload;
//}
