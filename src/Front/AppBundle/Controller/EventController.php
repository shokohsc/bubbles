<?php

namespace Front\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class EventController extends Controller
{

  /**
   * Comics from Event
   *
   * @Route("/event/{id}/{page}", name="event", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"})
   *
   * @param string $id  event id
   * @param string $page  pagination
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function eventAction($id, $page)
  {
      return $this->render('front/event/event.html.twig',
          [
              'id'          => $id,
              'page'        => $page,
          ]
      );
  }

  /**
   * Get Event entity
   *
   * @Route("/get_event/{id}", name="get_event", requirements={"id" = "\d+"}, options={"expose"=true})
   *
   * @param string $id event id
   * @return JsonResponse
   */
  public function getEventAction($id)
  {
      $event = $this->get('app.event_repository')->findOneById($id);
      $data = [
          'title' => $event->getTitle(),
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
   *  Get comics belonging to the event matching id
   *
   * @Route("/get_event_comics/{id}/{page}", name="get_event_comics", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"}, options={"expose"=true})
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function comicsAction($id, $page)
  {
      $collection = $this->get('app.event_repository')->findAllComicsById($id, $page);

      return $this->render('front/comic/list.html.twig',
          [
              'collection'  => $collection,
          ]
      );
  }

}
