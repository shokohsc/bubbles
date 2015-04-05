<?php

namespace Front\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class CreatorController extends Controller
{

  /**
   * Comics from Creator
   *
   * @Route("/creator/{id}/{page}", name="creator", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"})
   *
   * @param string $id  creator id
   * @param string $page  pagination
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function creatorAction($id, $page)
  {
      return $this->render('front/creator/creator.html.twig',
          [
              'id'          => $id,
              'page'        => $page,
          ]);
  }

  /**
   * Get Creator entity
   *
   * @Route("/get_creator/{id}", name="get_creator", requirements={"id" = "\d+"}, options={"expose"=true})
   *
   * @param string $id creator id
   * @return JsonResponse
   */
  public function getCreatorAction($id)
  {
      $response = new JsonResponse();
      $response->setData(array(
        'data' => $this->get('app.creator_repository')->findOneById($id)
      ));

      return $response;
  }

  /**
   *  Get comics belonging to the creator matching id
   *
   * @Route("/get_creator_comics/{id}/{page}", name="get_creator_comics", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"}, options={"expose"=true})
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function comicsAction($id, $page)
  {
      $collection = $this->get('app.creator_repository')->findAllComicsById($id, $page);

      return $this->render('front/comic/list.html.twig',
          [
              'collection'  => $collection,
          ]);
  }

}
