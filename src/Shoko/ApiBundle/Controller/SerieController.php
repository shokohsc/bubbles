<?php

namespace Shoko\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/series")
 */
class SerieController extends Controller
{
    /**
     *  Get comics belonging to the serie matching id.
     *
     * @Route("/{id}/comics/{page}", name="api_serie_comics", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"}, options={"expose"=true})
     *
     * @param Request     $request
     * @param string      $id
     * @param null|string $page
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function comicsAction(Request $request, $id, $page)
    {
        $collection = apcu_fetch('serie-'.$id.'-'.$page);
        if (!$collection) {
          $collection = $this->get('shoko.serie.repository')->findAllComicsById($id, $page);
          apcu_add('serie-'.$id.'-'.$page, $collection, 600);
        }
        $comics = json_decode($this->get('marvel.tojson')->encode($collection));

        return new JsonResponse([
            'comics' => $comics,
            'serieId' => $id,
        ], 200);
    }
}
