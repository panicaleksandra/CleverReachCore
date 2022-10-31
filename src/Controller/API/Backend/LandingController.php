<?php

namespace CleverReachCore\Controller\API\Backend;

use CleverReachCore\Business\Config;
use Shopware\Core\Framework\Api\Response\JsonApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;

/**
 * Class LandingController
 *
 * @package CleverReachCore\Controller\API\Backend
 */
class LandingController extends AbstractController
{
    /**
     * Returns url.
     * @RouteScope(scopes={"api"})
     * @Route(path="/api/cleverreach/geturl",
     *        name="api.cleverreach.geturl",
     *        methods={"GET", "POST"})
     *
     * @return JsonApiResponse
     */
    public function handle(): JsonApiResponse
    {
        $url = Config::HOSTNAME . Config::AUTHORIZE_URI . $this->getUri();

        return new JsonApiResponse(['returnUrl' => $url]);
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        return $this->generateUrl(
            'storefront.authorization',
            ['type' => 'param'],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
