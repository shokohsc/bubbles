<?php

namespace Front\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Carbon\Carbon;

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
          ]);
  }

  /**
   * Get Serie entity
   *
   * @Route("/get/serie/{id}", name="get_serie", requirements={"id" = "\d+"}, options={"expose"=true})
   *
   * @param string $id serie id
   * @return JsonResponse
   */
  public function getSerieAction($id)
  {
      $response = new JsonResponse();
      $response->setData(array(
        'data' => $this->get('app.serie_repository')->findOneById($id)
      ));
      return $response;
  }

  /**
   *  Get comics belonging to the serie matching id
   *
   * @param string $id   serie id
   * @param string $page pagination
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function comicsAction($id, $page)
  {
      $collection = $this->get('app.serie_repository')->findAllComicsById($id, $page);

      return $this->render('front/comic/list.html.twig',
          [
              'collection'  => $collection,
          ]);
  }

}
