<?php

namespace Shoko\ApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * RequestOriginListener class.
 */
class RequestOriginListener
{
    /**
     * {@inheritdoc}
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        $route = $request->get('_route');
        $isApiRoute = 0 === strrpos($route, 'api');
        $schemeAndHost = $request->getSchemeAndHttpHost();
        $referer = trim($request->headers->get('referer'), '/');
        if ($isApiRoute && $schemeAndHost !== $referer) {
            throw new AccessDeniedHttpException('Thou Art Not Allowed To Ride Through Valhalla!');
        }
    }
}
