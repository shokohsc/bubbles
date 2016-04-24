<?php

namespace Shoko\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Carbon\Carbon;

/**
 * @Route("/comics")
 */
class ComicController extends Controller
{
    /**
     * Get Comic entity.
     *
     * @Route("/{id}", name="api_comic", requirements={"id" = "\d+"}, options={"expose"=true})
     *
     * @param Request $request
     * @param string  $id      comic id
     *
     * @return JsonResponse
     */
    public function getAction(Request $request, $id)
    {
        $comic = apcu_fetch('comic-'.$id);
        if (!$comic) {
          $comic = $this->get('shoko.comic.repository')->findOneById($id);
          apcu_add('comic-'.$id, $comic, 600);
        }
        $comic = json_decode($this->get('marvel.tojson')->encode($comic));

        return new JsonResponse([
            'comic' => $comic,
        ], 200);
    }

    /**
     * Get comics matching the date.
     *
     * @Route("/week/{date}", name="api_week_comics", options={"expose"=true})
     *
     * @param Request     $request
     * @param null|string $date
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function weekAction(Request $request, $date = null)
    {
        $dateTarget = null === $date || 'undefined' === $date ? Carbon::now() : Carbon::parse($date);
        $collection = apcu_fetch('comic-week-'.$date);
        if (!$collection) {
          $collection = $this->get('shoko.comic.repository')->findAllByReleaseDate($dateTarget);
          apcu_add('comic-week-'.$date, $collection, 600);
        }
        $comics = json_decode($this->get('marvel.tojson')->encode($collection));

        $locale = $request->getLocale();
        setlocale(LC_TIME, $locale.'_'.strtoupper($locale));

        $title = $this->get('translator')->trans('comics.release').' '.$dateTarget->formatLocalized('%b %e, %Y');
        if ($date === null || $date === 'undefined') {
            $title = $this->get('translator')->trans('comics.this_week');
        }

        return new JsonResponse([
            'title' => $title,
            'comics' => $comics,
            'date' => $dateTarget,
        ], 200);
    }
}
