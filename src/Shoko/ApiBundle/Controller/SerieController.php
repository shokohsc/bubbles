<?php

namespace Shoko\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/series")
 */
class SerieController extends Controller
{
  /**
   *  Get comics belonging to the serie matching id
   *
   * @Route("/{id}/comics/{page}", name="api_serie_comics", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"}, options={"expose"=true})
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function comicsAction($id, $page)
  {
      $collection = $this->get('shoko.serie.repository')->findAllComicsById($id, $page);
      $comics = json_decode($this->get('marvel.tojson')->encode($collection));

      return new JsonResponse([
          'comics' => $comics,
      ], 200);
  }

}
