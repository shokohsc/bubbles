<?php

namespace Shoko\ApiBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Shoko\ApiBundle\Repository\EventRepository;

use Octante\MarvelAPIBundle\Repositories\EventsRepository;

class EventRepositoryTest extends \PHPUnit_Framework_TestCase
{
      private $client;
      private $repositoryMock;
      private $queryMock;

      public function setUp()
      {
        $this->client = $this->getMockBuilder('Octante\MarvelAPIBundle\Lib\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $this->repositoryMock = $this->getMockBuilder('Octante\MarvelAPIBundle\Repositories\EventsRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $this->queryMock = $this->getMockBuilder('Octante\MarvelAPIBundle\Model\Query\EventQuery')
            ->disableOriginalConstructor()
            ->getMock();
      }

      public function testFindAllComicsById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Event/getEventComics.json');
        $this->client
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($jsonResponse));
        $sut = new EventsRepository($this->client);
        $comics = $sut->getComicsFromEvent(271, $this->queryMock);// Secret Wars II
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Collections\ComicsCollection', $comics);
      }

      public function testFindOneById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Event/getEvent.json');
        $this->client
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($jsonResponse));
        $sut = new EventsRepository($this->client);
        $event = $sut->getEventById(270);// Secret Wars
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Collections\EventsCollection', $event);
      }

      public function testFindAllByQuery()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Event/getEvents.json');
        $this->client
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($jsonResponse));
        $sut = new EventsRepository($this->client);
        $events = $sut->getEvents($this->queryMock);// secret
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Collections\EventsCollection', $events);
      }

}
