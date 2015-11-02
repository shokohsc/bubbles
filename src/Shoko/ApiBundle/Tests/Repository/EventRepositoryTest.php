<?php

namespace Shoko\ApiBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Shoko\ApiBundle\Repository\EventRepository;
use Octante\MarvelAPIBundle\Repositories\EventsRepository;
use Octante\MarvelAPIBundle\Repositories\ComicsRepository;
use Prophecy\Prophet;
use Prophecy\Argument;

/**
 * EventRepositoryTest class.
 */
class EventRepositoryTest extends \PHPUnit_Framework_TestCase
{
      /**
       * Prophet $prophet.
       *
       * @var Prophet
       */
      private $prophet;

      /**
       * Octante\MarvelAPIBundle\Lib\Client $client.
       *
       * @var Octante\MarvelAPIBundle\Lib\Client
       */
      private $client;

      /**
       * Octante\MarvelAPIBundle\Model\Query\EventQuery $queryMock.
       *
       * @var Octante\MarvelAPIBundle\Model\Query\EventQuery
       */
      private $queryMock;

      /**
       * Octante\MarvelAPIBundle\Repositories\ComicsRepository $comicRepositoryMock.
       *
       * @var Octante\MarvelAPIBundle\Repositories\ComicsRepository
       */
      private $comicRepositoryMock;

      /**
      * Octante\MarvelAPIBundle\Model\Query\ComicQuery $comicQueryMock.
      *
      * @var Octante\MarvelAPIBundle\Model\Query\ComicQuery
       */
      private $comicQueryMock;

      /**
       * int $comicsPerPage.
       *
       * @var int
       */
      private $comicsPerPage;

      /**
       * {@inheritdoc}
       */
      public function setUp()
      {
        $this->prophet = new Prophet();
        $this->client = $this->prophet->prophesize('Octante\MarvelAPIBundle\Lib\Client');
        $this->queryMock = $this->prophet->prophesize('Octante\MarvelAPIBundle\Model\Query\EventQuery');
        $this->comicRepositoryMock = $this->prophet->prophesize('Octante\MarvelAPIBundle\Repositories\ComicsRepository');
        $this->comicQueryMock = $this->prophet->prophesize('Octante\MarvelAPIBundle\Model\Query\ComicQuery');
        $this->comicsPerPage = 20;
      }

      /**
       * testFindAllComicsById method
       */
      public function testFindAllComicsById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Event/getEventComics.json');
        $this->client->send(Argument::any())->willReturn($jsonResponse);
        $sut = new EventsRepository($this->client->reveal());
        $stubComics = $sut->getComicsFromEvent(271, $this->queryMock->reveal());// Secret Wars II
        $sutComicsRepository = new ComicsRepository($this->client->reveal());
        $repository = new EventRepository($sut, $this->queryMock->reveal(), $sutComicsRepository, $this->comicQueryMock->reveal(), $this->comicsPerPage);
        $comics = $repository->findAllComicsById(271, 1);
        $this->assertEquals($stubComics->getData()->getResults(), $comics->getResults());
      }

      /**
       * testFindOneById method
       */
      public function testFindOneById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Event/getEvent.json');
        $this->client->send(Argument::any())->willReturn($jsonResponse);
        $sut = new EventsRepository($this->client->reveal());
        $stubEvent = $sut->getEventById(270);// Secret Wars
        $repository = new EventRepository($sut, $this->queryMock->reveal(), $this->comicRepositoryMock->reveal(), $this->comicQueryMock->reveal(), $this->comicsPerPage);
        $event = $repository->findOneById(270);
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\DataContainer\DataContainer', $event);
        $this->assertEquals($stubEvent->getData()->getResults()[0], $event->getResults()[0]);
      }

      /**
       * testFindAllByQuery method
       */
      public function testFindAllByQuery()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Event/getEvents.json');
        $this->client->send(Argument::any())->willReturn($jsonResponse);
        $sut = new EventsRepository($this->client->reveal());
        $stubEvents = $sut->getEvents($this->queryMock->reveal());// secret
        $repository = new EventRepository($sut, $this->queryMock->reveal(), $this->comicRepositoryMock->reveal(), $this->comicQueryMock->reveal(), $this->comicsPerPage);
        $events = $repository->findAllByQuery('secret', 1);
        $this->assertEquals($stubEvents->getData()->getResults(), $events->getResults());
      }

      /**
       * {@inheritdoc}
       */
      public function tearDown()
      {
        $this->prophet->checkPredictions();
      }
}
