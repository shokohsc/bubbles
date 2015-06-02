<?php

namespace Shoko\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Carbon\Carbon;

/**
 * @Route("/comics")
 */
class ComicController extends Controller
{

  /**
   * Get Comic entity
   *
   * @Route("/{id}", name="api_comic", requirements={"id" = "\d+"}, options={"expose"=true})
   *
   * @param string $id comic id
   * @return JsonResponse
   */
  public function getAction($id)
  {
      $comic = $this->get('shoko.comic.repository')->findOneById($id);
      $data = [
          'title' => $comic->getFullName(),
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
   * Get comics matching the date
   *
   * @Route("/week/{date}", name="api_week_comics", options={"expose"=true})
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function weekAction($date = null)
  {
      $date = null === $date ? Carbon::now() : Carbon::parse($date);
      $collection = $this->get('shoko.comic.repository')->findAllByReleaseDate($date);

      return $this->render('front/comic/list.html.twig',
          [
              'collection'  => $collection
          ]
      );
  }

}
