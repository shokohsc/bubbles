<?php

namespace Shoko\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/search")
 */
class SearchController extends Controller
{

  /**
   *  Get series matching the search query
   *
   * @Route("/series/{q}/{page}", name="api_search_series", defaults={"page" = 1}, requirements={"q", "page" = "\d+"}, options={"expose"=true})
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function seriesAction($q, $page)
  {
      $collection = $this->get('shoko.serie.repository')->findAllByQuery(urlencode($q), $page);

      return $this->render('front/serie/list.html.twig',
          [
              'collection'  => $collection,
          ]
      );
  }

  /**
   *  Get characters matching the search query
   *
   * @Route("/characters/{q}/{page}", name="api_search_characters", defaults={"page" = 1}, requirements={"q", "page" = "\d+"}, options={"expose"=true})
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function charactersAction($q, $page)
  {
      $collection = $this->get('shoko.character.repository')->findAllByQuery(urlencode($q), $page);

      return $this->render('front/character/list.html.twig',
          [
              'collection'  => $collection,
          ]
      );
  }

  /**
   *  Get comics matching the search query
   *
   * @Route("/comics/{q}/{page}", name="api_search_comics", defaults={"page" = 1}, requirements={"q", "page" = "\d+"}, options={"expose"=true})
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function comicsAction($q, $page)
  {
      $collection = $this->get('shoko.comic.repository')->findAllByQuery(urlencode($q), $page);

      return $this->render('front/comic/list.html.twig',
          [
              'collection'  => $collection,
          ]
      );
  }

  /**
   *  Get creators matching the search query
   *
   * @Route("/creators/{q}/{page}", name="api_search_creators", defaults={"page" = 1}, requirements={"q", "page" = "\d+"}, options={"expose"=true})
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function creatorsAction($q, $page)
  {
      $collection = $this->get('shoko.creator.repository')->findAllByQuery(urlencode($q), $page);

      return $this->render('front/creator/list.html.twig',
          [
              'collection'  => $collection,
          ]
      );
  }

  /**
   *  Get events matching the search query
   *
   * @Route("/events/{q}/{page}", name="api_search_events", defaults={"page" = 1}, requirements={"q", "page" = "\d+"}, options={"expose"=true})
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function eventsAction($q, $page)
  {
      $collection = $this->get('shoko.event.repository')->findAllByQuery(urlencode($q), $page);

      return $this->render('front/event/list.html.twig',
          [
              'collection'  => $collection,
          ]
      );
  }


}
