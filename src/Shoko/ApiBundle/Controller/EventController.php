<?php

namespace Shoko\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/events")
 */
class EventController extends Controller
{
    /**
     *  Get comics belonging to the event matching id.
     *
     * @Route("/{id}/comics/{page}", name="api_event_comics", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"}, options={"expose"=true})
     *
     * @param Request     $request
     * @param string      $id
     * @param null|string $page
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function comicsAction(Request $request, $id, $page)
    {
        $collection = $this->get('shoko.event.repository')->findAllComicsById($id, $page);
        $comics = json_decode($this->get('marvel.tojson')->encode($collection));

        return new JsonResponse([
            'comics' => $comics,
            'eventId' => $id,
        ], 200);
    }
}
