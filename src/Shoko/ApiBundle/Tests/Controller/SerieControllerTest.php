<?php

namespace Shoko\ApiBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class SerieControllerTest extends \PHPUnit_Framework_TestCase
{
  private $client;

  public function setUp()
  {
    $this->client = $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Client')
        ->disableOriginalConstructor()
        ->getMock();
  }


  public function testGetActionSuccess()
  {
    $this->client
        ->expects($this->once())
        ->method('request', ['GET', '/api/series/18468'])
        ->will($this->returnValue(null));
    $this->client
        ->expects($this->once())
        ->method('getResponse')
        ->will($this->returnValue(new JsonResponse([
            'data' => [
              'code' => '200',
              'title' => 'Ms. Marvel (2014 - Present)'
            ]
          ], 200)
        ));

    $this->client->request('GET', '/api/series/18468');
    $response = $this->client->getResponse();

    $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
    $this->assertEquals(200, $response->getStatusCode(), $response->getContent());
    $this->assertEquals('{"data":{"code":"200","title":"Ms. Marvel (2014 - Present)"}}', $response->getContent());
  }

  public function testGetActionError()
  {
    $this->client
        ->expects($this->once())
        ->method('request', ['GET', '/api/series/1'])
        ->will($this->returnValue(null));
    $this->client
        ->expects($this->once())
        ->method('getResponse')
        ->will($this->returnValue(new JsonResponse([
            'error' => [
              'code' => '404',
              'message' => 'deadpool says you lost your way.'
            ]
          ], 404)
        ));

    $this->client->request('GET', '/api/series/1');
    $response = $this->client->getResponse();

    $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
    $this->assertEquals(404, $response->getStatusCode(), $response->getContent());
    $this->assertEquals('{"error":{"code":"404","message":"deadpool says you lost your way."}}', $response->getContent());
  }

}
