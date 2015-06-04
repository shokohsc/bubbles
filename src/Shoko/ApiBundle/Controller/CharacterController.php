<?php

namespace Shoko\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/characters")
 */
class CharacterController extends Controller
{

  /**
   * Get Character entity
   *
   * @Route("/{id}", name="api_character", requirements={"id" = "\d+"}, options={"expose"=true})
   *
   * @param string $id character id
   * @return JsonResponse
   */
  public function getAction($id)
  {
      $character = $this->get('shoko.character.repository')->findOneById($id);
      $data = [
        'code' => 200,
        'name' => $character->getName()
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
   *  Get comics belonging to the character matching id
   *
   * @Route("/{id}/comics/{page}", name="api_character_comics", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"}, options={"expose"=true})
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function comicsAction($id, $page)
  {
      $collection = $this->get('shoko.character.repository')->findAllComicsById($id, $page);

      return $this->render('front/comic/list.html.twig',
          [
              'collection'  => $collection,
          ]
      );
  }

}
