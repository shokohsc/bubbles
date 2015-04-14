<?php

namespace Front\AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\TranslatorInterface;
use Octante\MarvelAPIBundle\Exceptions\CurlErrorCodeException;

class AppExceptionListener
{
  /**
   * Twig Environment
   *
   * @var \Twig_Environment
   */
  private $twig;

  /**
   * Translator
   *
   * @var Translator
   */
  private $translator;

    /**
     * AppExceptionListener constructor
     * @param \Twig_Environment $twig
     * @param TranslatorInterface $translator
     */
    public function __construct(\Twig_Environment $twig, TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->translator = $translator;
    }

    /**
     * onKernelException
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof NotFoundHttpException || $exception instanceof CurlErrorCodeException) {
            $args = [
                    'title' => $this->translator->trans("error.404.title"),
                    'code' => '404',
                    'message' => $this->translator->trans("error.404.message"),
                    ];
        } else {
            $args = [
                    'title' => $this->translator->trans("error.500.title"),
                    'code' => '500',
                    'message' => $this->translator->trans("error.500.message"),
                    ];
        }
        $response = new Response($this->twig->render('front/error.html.twig', $args));
        $event->setResponse($response);
    }
}
