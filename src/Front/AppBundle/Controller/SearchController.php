<?php

namespace Front\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{

  /**
   * Search Serie
   *
   * @Route("/search", name="search")
   *
   * @param Request $request input from user
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function searchAction(Request $request)
  {
      $q = filter_var($request->get('q'), FILTER_SANITIZE_STRING);
      $entity = filter_var($request->get('entity'), FILTER_SANITIZE_STRING);
      $page = filter_var($request->get('page'), FILTER_SANITIZE_STRING);

      return $this->render('front/search/search.html.twig',
          [
              'q'       => $q,
              'entity'      => $entity,
              'page'      => $page,
          ]);
  }

  /**
   *  Get series matching the search query
   *
   * @Route("/get_search_series/{q}/{page}", name="get_search_series", defaults={"page" = 1}, requirements={"q", "page" = "\d+"}, options={"expose"=true})
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function seriesAction($q, $page)
  {
      $collection = $this->get('app.serie_repository')->findAllByQuery($q, $page);

      return $this->render('front/serie/list.html.twig',
          [
              'collection'  => $collection,
          ]);
  }

  /**
   *  Get comics matching the search query
   *
   * @Route("/get_search_comics/{q}/{page}", name="get_search_comics", defaults={"page" = 1}, requirements={"q", "page" = "\d+"}, options={"expose"=true})
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function comicsAction($q, $page)
  {
      $collection = $this->get('app.comic_repository')->findAllByQuery($q, $page);

      return $this->render('front/comic/list.html.twig',
          [
              'collection'  => $collection,
          ]);
  }

}
