<?php

namespace Front\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class CharacterController extends Controller
{

  /**
   * Comics from Character
   *
   * @Route("/character/{id}/{page}", name="character", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"})
   *
   * @param string $id  character id
   * @param string $page  pagination
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function characterAction($id, $page)
  {
      return $this->render('front/character/character.html.twig',
          [
              'id'          => $id,
              'page'        => $page,
          ]);
  }

  /**
   * Get Character entity
   *
   * @Route("/get_character/{id}", name="get_character", requirements={"id" = "\d+"}, options={"expose"=true})
   *
   * @param string $id character id
   * @return JsonResponse
   */
  public function getCharacterAction($id)
  {
      $character = $this->get('app.character_repository')->findOneById($id);
      $data = [
          'name' => $character->getName(),
      ];
      $response = new JsonResponse();
      $response->setData(array(
        'data' => $data
      ));

      return $response;
  }

  /**
   *  Get comics belonging to the character matching id
   *
   * @Route("/get_character_comics/{id}/{page}", name="get_character_comics", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"}, options={"expose"=true})
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function comicsAction($id, $page)
  {
      $collection = $this->get('app.character_repository')->findAllComicsById($id, $page);

      return $this->render('front/comic/list.html.twig',
          [
              'collection'  => $collection,
          ]);
  }

}
