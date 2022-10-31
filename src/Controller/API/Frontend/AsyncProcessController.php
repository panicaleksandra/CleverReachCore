<?php

namespace CleverReachCore\Controller\API\Frontend;

use CleverReachCore\Business\Bootstrap;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use CleverReachCore\Core\Infrastructure\TaskExecution\Interfaces\AsyncProcessService;
use CleverReachCore\Utility\Initializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;

/**
 * class AsyncProcessController
 *
 * @package CleverReachCore\Controller\API\Frontend
 */
class AsyncProcessController extends AbstractController
{
    private Initializer $initializer;

    /**
     * AsyncProcessController constructor.
     *
     * @param Initializer $initializer
     */
    public function __construct(Initializer $initializer)
    {
        $this->initializer = $initializer;
    }

    /**
     * Async process starter endpoint.
     * @RouteScope(scopes={"storefront"})
     * @Route(path="/storefront/async/{guid}",
     *        name="storefront.async",
     *        defaults={"csrf_protected"=false, "auth_required"=false},
     *        methods={"GET", "POST"})
     *
     * @param string $guid
     *
     * @return JsonResponse
     */
    public function run(string $guid): JsonResponse
    {
        Bootstrap::init();
        $this->initializer->registerServices();

        /** @var AsyncProcessService $asyncProcessService */
        $asyncProcessService = ServiceRegister::getService(AsyncProcessService::class);
        $asyncProcessService->runProcess($guid);

        return new JsonResponse([]);
    }
}
