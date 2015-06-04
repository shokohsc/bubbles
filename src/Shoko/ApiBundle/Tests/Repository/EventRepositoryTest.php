<?php

namespace Shoko\ApiBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Shoko\ApiBundle\Repository\EventRepository;
use Octante\MarvelAPIBundle\Repositories\EventsRepository;
use Octante\MarvelAPIBundle\Repositories\ComicsRepository;

class EventRepositoryTest extends \PHPUnit_Framework_TestCase
{
      private $client;
      private $comicRepositoryMock;
      private $comicQueryMock;
      private $comicsPerPage;

      public function setUp()
      {
        $this->client = $this->getMockBuilder('Octante\MarvelAPIBundle\Lib\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $this->queryMock = $this->getMockBuilder('Octante\MarvelAPIBundle\Model\Query\EventQuery')
            ->disableOriginalConstructor()
            ->getMock();
        $this->comicRepositoryMock = $this->getMockBuilder('Octante\MarvelAPIBundle\Repositories\ComicsRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $this->comicQueryMock = $this->getMockBuilder('Octante\MarvelAPIBundle\Model\Query\ComicQuery')
            ->disableOriginalConstructor()
            ->getMock();
        $this->comicsPerPage = 20;
      }

      public function testFindAllComicsById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Event/getEventComics.json');
        $this->client
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue($jsonResponse));

        $sut = new EventsRepository($this->client);
        $stubComics = $sut->getComicsFromEvent(271, $this->queryMock);// Secret Wars II

        $sutComicsRepository = new ComicsRepository($this->client);
        $repository = new EventRepository($sut, $this->queryMock, $sutComicsRepository, $this->comicQueryMock, $this->comicsPerPage);
        $comics = $repository->findAllComicsById(12339, 1);
        $this->assertEquals($stubComics->getData()->getResults(), $comics);
      }

      public function testFindOneById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Event/getEvent.json');
        $this->client
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue($jsonResponse));

        $sut = new EventsRepository($this->client);
        $stubEvent = $sut->getEventById(270);// Secret Wars

        $repository = new EventRepository($sut, $this->queryMock, $this->comicRepositoryMock, $this->comicQueryMock, $this->comicsPerPage);
        $event = $repository->findOneById(270);
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Entities\Event', $event);
        $this->assertEquals($stubEvent->getData()->getResults()[0], $event);
      }

      public function testFindAllByQuery()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Event/getEvents.json');
        $this->client
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue($jsonResponse));

        $sut = new EventsRepository($this->client);
        $stubEvents = $sut->getEvents($this->queryMock);// secret

        $repository = new EventRepository($sut, $this->queryMock, $this->comicRepositoryMock, $this->comicQueryMock, $this->comicsPerPage);
        $events = $repository->findAllByQuery('secret', 1);
        $this->assertEquals($stubEvents->getData()->getResults(), $events);
      }

}
