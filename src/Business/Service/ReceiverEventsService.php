<?php

namespace CleverReachCore\Business\Service;

use CleverReachCore\Core\BusinessLogic\Receiver\ReceiverEventsService as BaseReceiverEventsService;

/**
 *Class ReceiverEventsService
 *
 * @package CleverReachCore\Business\Service
 */
class ReceiverEventsService extends BaseReceiverEventsService
{
    private const WEBHOOK_ENDPOINT = 'https://610c-178-222-249-248.eu.ngrok.io/api/cleverreach/webhook';

    /**
     * Provides url that will listen to web hook requests.
     *
     * @return string
     */
    public function getEventUrl()
    {
        return self::WEBHOOK_ENDPOINT;
    }
}
