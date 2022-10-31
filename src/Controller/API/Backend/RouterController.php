<?php

namespace CleverReachCore\Controller\API\Backend;

use Shopware\Core\Framework\Api\Response\JsonApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;

/**
 * Class RouterController
 *
 * @package CleverReachCore\Controller\API\Backend
 */
class RouterController extends AbstractController
{
    public const LANDING_STATE_CODE = 'landing';

    /**
     * Returns page for display.
     * @RouteScope(scopes={"api"})
     * @Route(path="/api/cleverreach/router",
     *        name="api.cleverreach.router",
     *        methods={"GET", "POST"})
     *
     * @return JsonApiResponse
     */
    public function handle(): JsonApiResponse
    {
        return new JsonApiResponse(['page' => $this->getPage()]);
    }

    /**
     * Returns page code.
     *
     * @return string
     */
    private function getPage(): string
    {
        return self::LANDING_STATE_CODE;
    }
}
