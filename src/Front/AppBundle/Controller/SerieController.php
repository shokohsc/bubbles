<?php

namespace Front\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

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

  /**
   * Get Serie entity
   *
   * @Route("/get_serie/{id}", name="get_serie", requirements={"id" = "\d+"}, options={"expose"=true})
   *
   * @param string $id serie id
   * @return JsonResponse
   */
  public function getSerieAction($id)
  {
      $serie = $this->get('app.serie_repository')->findOneById($id);
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
   * @Route("/get_serie_comics/{id}/{page}", name="get_serie_comics", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"}, options={"expose"=true})
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function comicsAction($id, $page)
  {
      $collection = $this->get('app.serie_repository')->findAllComicsById($id, $page);

      return $this->render('front/comic/list.html.twig',
          [
              'collection'  => $collection,
          ]
      );
  }

}
