<?php

namespace Shoko\ApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\TranslatorInterface;
use Octante\MarvelAPIBundle\Exceptions\CurlErrorCodeException;

/**
 * ApiExceptionListener class.
 */
class ApiExceptionListener
{
  /**
   * Translator
   *
   * @var Translator
   */
  private $translator;

    /**
     * ApiExceptionListener constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * onKernelException.
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof NotFoundHttpException || $exception instanceof CurlErrorCodeException) {
            $args = [
                    'code' => 404,
                    'message' => $this->translator->trans("error.404.message"),
                    ];
        } else {
            $args = [
                    'code' => 500,
                    'message' => $this->translator->trans("error.500.message"),
                    ];
        }
        $response = new JsonResponse(['error' => $args['message']], $args['code']);
        $event->setResponse($response);
    }
}
