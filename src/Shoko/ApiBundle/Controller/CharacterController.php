<?php

namespace Shoko\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/characters")
 */
class CharacterController extends Controller
{
  /**
   *  Get comics belonging to the character matching id
   *
   * @Route("/{id}/comics/{page}", name="api_character_comics", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"}, options={"expose"=true})
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  public function comicsAction(Request $request, $id, $page)
  {
      $collection = $this->get('shoko.character.repository')->findAllComicsById($id, $page);
      $comics = json_decode($this->get('marvel.tojson')->encode($collection));

      return new JsonResponse([
          'comics' => $comics,
          'characterId' => $id
      ], 200);
  }

}
