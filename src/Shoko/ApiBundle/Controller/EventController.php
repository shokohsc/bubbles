<?php

namespace Shoko\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/events")
 */
class EventController extends Controller
{

  /**
   * Get Event entity
   *
   * @Route("/{id}", name="api_event", requirements={"id" = "\d+"}, options={"expose"=true})
   *
   * @param string $id event id
   * @return JsonResponse
   */
  public function getAction($id)
  {
      $event = $this->get('shoko.event.repository')->findOneById($id);
      $data = [
        'code' => 200,
        'title' => $event->getTitle(),
      ];
      $response = new JsonResponse();
      $response->setData([$data]);

      return $response;
  }

  /**
   *  Get comics belonging to the event matching id
   *
   * @Route("/{id}/comics/{page}", name="api_event_comics", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"}, options={"expose"=true})
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function comicsAction($id, $page)
  {
      $collection = $this->get('shoko.event.repository')->findAllComicsById($id, $page);

      return $this->render('front/comic/list.html.twig',
          [
              'collection'  => $collection,
          ]
      );
  }

}
