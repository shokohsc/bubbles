<?php

namespace Shoko\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * FrontController class.
 */
class FrontController extends Controller
{
    /**
     *  Index method.
     *
     * @param Request     $request
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $gaId = $this->getParameter('ga_id');

        return $this->render('base.html.twig', array(
          'ga_id' => $gaId,
        ));
    }
}
