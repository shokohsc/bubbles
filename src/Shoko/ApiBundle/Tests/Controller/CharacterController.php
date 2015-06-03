<?php

namespace Shoko\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Shoko\ApiBundle\Controller\CharacterController;

class CharacterControllerTest extends \PHPUnit_Framework_TestCase
{

  public function testGetAction()
  {
    $client = $this->getClient(true);

    $client->request('GET', '/api/characters/1010338');
    $response = $client->getResponse();

    $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);

    $this->assertEquals(200, $response->getStatusCode(), $response->getContent());
    $this->assertEquals('{"data":{"name":"Captain Marvel (Carol Danvers)"}}', $response->getContent());

    // $this->assertEquals(404, $response->getStatusCode(), $response->getContent());
    // $this->assertEquals('{"code":404,"message":"Note does not exist."}', $response->getContent());
  }

}
