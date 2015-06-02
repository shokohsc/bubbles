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
   * Get Serie entity
   *
   * @Route("/{id}", name="api_serie", requirements={"id" = "\d+"}, options={"expose"=true})
   *
   * @param string $id serie id
   * @return JsonResponse
   */
  public function getAction($id)
  {
      $serie = $this->get('shoko.serie.repository')->findOneById($id);
      $data = [
          'title' => $serie->getTitle(),
      ];
      $response = new JsonResponse();
      $response->setData(
          [
              'data' => $data
          ]
      );

      return $response;
  }

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

      return $this->render('front/comic/list.html.twig',
          [
              'collection'  => $collection,
          ]
      );
  }

}
