<?php

namespace Shoko\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/creators")
 */
class CreatorController extends Controller
{

  /**
   * Comics from Creator
   *
   * @Route("/creator/{id}/{page}", name="creator", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"})
   *
   * @param string $id  creator id
   * @param string $page  pagination
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function creatorAction($id, $page)
  {
      return $this->render('front/creator/creator.html.twig',
          [
              'id'          => $id,
              'page'        => $page,
          ]
      );
  }

}
