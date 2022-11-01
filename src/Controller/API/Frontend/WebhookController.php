<?php

namespace CleverReachCore\Controller\API\Frontend;

use CleverReachCore\Business\Service\WebhookService;
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
            $this->initializer->init();

            $webhookService = $this->getWebhookService();

            if ($request->getMethod() === 'GET') {
                $secret = $request->query->get('secret');

                return new Response($webhookService->getVerificationToken() . ' ' . $secret);
            }

            $requestBody = json_decode($request->getContent(), true);
            $webhookService->handleUpsertReceiver($requestBody);

            return new Response();
        } catch (Exception $exception) {
            return new Response($exception->getMessage(), 500);
        }
    }

    /**
     * @return WebhookService
     */
    private function getWebhookService(): WebhookService
    {
        /** @var WebhookService $webhookService */
        $webhookService = ServiceRegister::getService(WebhookService::class);

        return $webhookService;
    }
}
