<?php

namespace Front\AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppExceptionListener
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof NotFoundHttpException) {
            $args = [
                    'title' => 'Bubble is lost',
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                    ];
        } else {
            $args = [
                    'title' => 'Bubble stumbled',
                    'code' => '500',
                    'message' => 'Sorry mate, seems there is an issue here.',
                    ];
        }
        $response = new Response($this->twig->render('front/error.html.twig', $args));
        $event->setResponse($response);
    }
}
