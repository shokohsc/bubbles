<?php

namespace Shoko\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/creators")
 */
class CreatorController extends Controller
{

  /**
   * Get Creator entity
   *
   * @Route("/{id}", name="api_creator", requirements={"id" = "\d+"}, options={"expose"=true})
   *
   * @param string $id creator id
   * @return JsonResponse
   */
  public function getAction($id)
  {
      $creator = $this->get('shoko.creator.repository')->findOneById($id);
      $data = [
        'code' => 200,
        'fullName' => $creator->getFullName(),
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
   *  Get comics belonging to the creator matching id
   *
   * @Route("/{id}/comics/{page}", name="api_creator_comics", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"}, options={"expose"=true})
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function comicsAction($id, $page)
  {
      $collection = $this->get('shoko.creator.repository')->findAllComicsById($id, $page);

      return $this->render('front/comic/list.html.twig',
          [
              'collection'  => $collection,
          ]
      );
  }

}
