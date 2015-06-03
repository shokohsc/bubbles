<?php

namespace Shoko\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/series")
 */
class SerieController extends Controller
{

  /**
   * Comics from Serie
   *
   * @Route("/serie/{id}/{page}", name="serie", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"})
   *
   * @param string $id  serie id
   * @param string $page  pagination
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function serieAction($id, $page)
  {
      return $this->render('front/serie/serie.html.twig',
          [
              'id'          => $id,
              'page'        => $page,
          ]
      );
  }

}
