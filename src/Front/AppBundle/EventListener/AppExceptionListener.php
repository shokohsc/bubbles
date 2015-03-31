<?php

namespace Front\AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppExceptionListener
{
    /**
     * Twig Environment
     * 
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * AppExceptionListener constructor
     * @param Twig_Environment $twig injected via service definition
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * onKernelException
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof NotFoundHttpException) {
            $args = [
                    'title' => 'Bubble is lost',
                    'code' => '404',
                    'message' => 'Deadpool says you lost your way.',
                    ];
        } else {
            $args = [
                    'title' => 'Bubble stumbled',
                    'code' => '500',
                    'message' => 'Deadpool says we lost our way.',
                    ];
        }
        $response = new Response($this->twig->render('front/error.html.twig', $args));
        $event->setResponse($response);
    }
}
