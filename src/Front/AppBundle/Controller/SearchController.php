<?php

namespace Front\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Carbon\Carbon;

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
      $query = filter_var($request->get('q'), FILTER_SANITIZE_STRING);

      return $this->render('front/search/search.html.twig',
          [
              'query'       => $query,
          ]);
  }

  /**
   *  Get series matching the search query
   *
   * @param string $query search query
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function seriesAction($query)
  {
      $collection = $this->get('app.serie_repository')->findAllByQuery($query);

      return $this->render('front/search/list.html.twig',
          [
              'collection'  => $collection,
          ]);
  }

}
