<?php

namespace Shoko\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CharacterController extends Controller
{

  /**
   * Comics from Character
   *
   * @Route("/character/{id}/{page}", name="character", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"})
   *
   * @param string $id  character id
   * @param string $page  pagination
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function characterAction($id, $page)
  {
      return $this->render('front/character/comics.html.twig',
          [
              'id'          => $id,
              'page'        => $page,
          ]
      );
  }

}
