<?php

namespace Shoko\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ComicController extends Controller
{

  /**
   * Comic
   *
   * @Route("/comic/{id}", name="comic", requirements={"id" = "\d+"})
   *
   * @param string $id  comic id
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function comicAction($id)
  {
      $comic = $this->get('shoko.comic.repository')->findOneById($id);
      return $this->render('front/comic/comic.html.twig',
          [
              'comic' => $comic,
          ]
      );
  }

}
