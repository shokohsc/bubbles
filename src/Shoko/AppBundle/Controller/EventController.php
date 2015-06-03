<?php

namespace Shoko\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/events")
 */
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

}
