<?php

namespace Shoko\ApiBundle\Controller;

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
   * Get Comic entity
   *
   * @Route("/{id}", name="api_comic", requirements={"id" = "\d+"}, options={"expose"=true})
   *
   * @param string $id comic id
   *
   * @return JsonResponse
   */
  public function getAction($id)
  {
      $comic = $this->get('shoko.comic.repository')->findOneById($id);
      $comic = json_decode($this->get('marvel.tojson')->encode($comic));

      return new JsonResponse([
          'comic' => $comic,
      ], 200);
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
      $date = null === $date || 'undefined' === $date ? Carbon::now() : Carbon::parse($date);
      $collection = $this->get('shoko.comic.repository')->findAllByReleaseDate($date);
      $comics = json_decode($this->get('marvel.tojson')->encode($collection));

      $locale = $this->get('request')->getLocale();
      setlocale(LC_TIME, $locale.'_'.strtoupper($locale));

      $title = $this->get('translator')->trans('comics.release') .' '. $date->formatLocalized('%b %e, %Y');

      return new JsonResponse([
          'title' => $title,
          'comics' => $comics,
          'locale' => $locale,
          'date' => $date,
      ], 200);
  }

}
