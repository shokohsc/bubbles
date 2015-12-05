<?php

namespace Shoko\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * FrontController class.
 */
class FrontController extends Controller
{
  /**
   *  Index method.
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function indexAction(Request $request)
  {
      return $this->render('base.html.twig');
  }

}
