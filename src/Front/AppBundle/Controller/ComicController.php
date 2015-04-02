<?php

namespace Front\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Carbon\Carbon;

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
      $comic = $this->get('app.comic_repository')->findOneById($id);

      return $this->render('front/comic/comic.html.twig',
          [
              'comic' => $comic,
          ]);
  }

}