<?php

namespace Shoko\ApiBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class CreatorControllerTest extends \PHPUnit_Framework_TestCase
{
  private $client;

  public function setUp()
  {
    $this->client = $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Client')
        ->disableOriginalConstructor()
        ->getMock();

    $this->client
        ->expects($this->any())
        ->method('request', ['GET', '/api/creators/2997'])
        ->will($this->returnValue(null));
    $this->client
        ->expects($this->any())
        ->method('getResponse')
        ->will($this->returnValue(new JsonResponse([
            'data' => [
              'code' => '200',
              'fullName' => 'Fred Hembeck'
            ]
          ], 200)
        ));

    $this->client
        ->expects($this->any())
        ->method('request', ['GET', '/api/creators/3'])
        ->will($this->returnValue(null));
    $this->client
        ->expects($this->any())
        ->method('getResponse')
        ->will($this->returnValue(new JsonResponse([
            'error' => [
              'code' => '404',
              'message' => 'deadpool says you lost your way.'
            ]
          ], 404)
        ));
  }


  public function testGetAction()
  {
    $this->client->request('GET', '/api/creators/2997');
    $response = $this->client->getResponse();

    $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
    $this->assertEquals(200, $response->getStatusCode(), $response->getContent());
    $this->assertEquals('{"data":{"code":"200","fullName":"Fred Hembeck"}}', $response->getContent());

    $this->client->request('GET', '/api/creators/3');
    $response = $this->client->getResponse();

    $this->assertEquals(404, $response->getStatusCode(), $response->getContent());
    $this->assertEquals('{"error":{"code":"404","message":"deadpool says you lost your way."}}', $response->getContent());
  }

}
