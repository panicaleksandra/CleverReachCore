<?php

namespace CleverReachCore\Controller\API\Frontend;

use CleverReachCore\Business\Bootstrap;
use CleverReachCore\Business\Service\ReceiverEventsService;
use CleverReachCore\Business\Service\WebhookService;
use CleverReachCore\Core\BusinessLogic\Receiver\ReceiverEventsService as BaseReceiverEventsService;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use CleverReachCore\Utility\Initializer;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;

/**
 * Class WebhookController
 *
 * @package CleverReachCore\Controller\API\Frontend
 */
class WebhookController extends AbstractController
{
    private Initializer $initializer;

    /**
     * Creates WebhookController.
     *
     * @param Initializer $initializer
     */
    public function __construct(Initializer $initializer)
    {
        $this->initializer = $initializer;
    }

    /**
     * Handles upsert receiver event.
     * @RouteScope(scopes={"api"})
     * @Route(path="/api/cleverreach/webhook",
     *        defaults={"auth_required"=false},
     *        name="api.cleverreach.webhook",
     *        methods={"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handle(Request $request): Response
    {
        try {
            Bootstrap::init();
            $this->initializer->registerServices();

            if ($request->getMethod() === 'GET') {
                $secret = $request->query->get('secret');
                /** @var ReceiverEventsService $receiverEventsService */
                $receiverEventsService = ServiceRegister::getService(BaseReceiverEventsService::class);
                $verificationToken = $receiverEventsService->getVerificationToken();

                return new Response($verificationToken . ' ' . $secret);
            }

            $requestBody = json_decode($request->getContent(), true);

            if ($requestBody['event'] !== 'receiver.created' &&
                $requestBody['event'] !== 'receiver.updated') {
                return new Response();
            }

            /** @var WebhookService $webhookService */
            $webhookService = ServiceRegister::getService(WebhookService::class);
            $webhookService->handleUpsertReceiver($requestBody);

            return new Response();
        } catch (Exception $exception) {
            return new Response($exception->getMessage(), 500);
        }
    }
}
