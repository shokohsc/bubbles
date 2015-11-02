<?php

namespace Shoko\ApiBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Shoko\ApiBundle\Repository\ComicRepository;
use Octante\MarvelAPIBundle\Repositories\ComicsRepository;
use Carbon\Carbon;
use Prophecy\Prophet;
use Prophecy\Argument;

/**
 * ComicRepositoryTest class.
 */
class ComicRepositoryTest extends \PHPUnit_Framework_TestCase
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
       * Octante\MarvelAPIBundle\Model\Query\ComicQuery $queryMock.
       *
       * @var Octante\MarvelAPIBundle\Model\Query\ComicQuery
       */
      private $queryMock;

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
        $this->queryMock = $this->prophet->prophesize('Octante\MarvelAPIBundle\Model\Query\ComicQuery');
        $this->comicsPerPage = 20;
      }

      /**
       * testGetReleaseDateRange method
       */
      public function testGetReleaseDateRange()
      {
        $fakeDate = Carbon::now();
        $end = $fakeDate->toDateString();
        $start = $fakeDate->copy()->subDays(6)->toDateString();
        $sut = new ComicsRepository($this->client->reveal());
        $repository = new ComicRepository($sut, $this->queryMock->reveal(), $this->comicsPerPage);
        $this->assertEquals($repository->getReleaseDateRange($fakeDate), $start.','.$end);
      }

      /**
       * testFindAllByReleaseDate method
       */
      public function testFindAllByReleaseDate()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Comic/getComicsDate.json');
        $this->client->send(Argument::any())->willReturn($jsonResponse);
        $sut = new ComicsRepository($this->client->reveal());
        $stubComics = $sut->getComics($this->queryMock->reveal());// Apr 27, 2015
        $sutComicsRepository = new ComicsRepository($this->client->reveal());
        $repository = new ComicRepository($sut, $this->queryMock->reveal(), $this->comicsPerPage);
        $comics = $repository->findAllByReleaseDate(Carbon::parse('Apr 27, 2015'), 1);
        $this->assertEquals($stubComics->getData()->getResults(), $comics->getResults());


        // $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Comic/getComicsDate.json');
        // $this->client
        //     ->expects($this->any())
        //     ->method('send')
        //     ->will($this->returnValue($jsonResponse));
        // $sut = new ComicsRepository($this->client);
        // $stubComics = $sut->getComics($this->queryMock);// Apr 27, 2015
        //
        // $sutComicsRepository = new ComicsRepository($this->client);
        // $repository = new ComicRepository($sut, $this->queryMock, $this->comicsPerPage);
        // $comics = $repository->findAllByReleaseDate(Carbon::parse('Apr 27, 2015'), 1);
        // $this->assertEquals($stubComics->getData()->getResults(), $comics->getResults());
      }

      /**
       * testFindOneById method
       */
      public function testFindOneById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Comic/getComic.json');
        $this->client->send(Argument::any())->willReturn($jsonResponse);
        $sut = new ComicsRepository($this->client->reveal());
        $stubComic = $sut->getComicById(52237);// Moon Knight (2014) #15
        $repository = new ComicRepository($sut, $this->queryMock->reveal(), $this->comicsPerPage);
        $comic = $repository->findOneById(52237);
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\DataContainer\DataContainer', $comic);
        $this->assertEquals($stubComic->getData()->getResults()[0], $comic->getResults()[0]);
      }

      /**
       * testFindAllByQuery method
       */
      public function testFindAllByQuery()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Comic/getComics.json');
        $this->client->send(Argument::any())->willReturn($jsonResponse);
        $sut = new ComicsRepository($this->client->reveal());
        $stubComics = $sut->getComics($this->queryMock->reveal());// wolverine
        $repository = new ComicRepository($sut, $this->queryMock->reveal(), $this->comicsPerPage);
        $comics = $repository->findAllByQuery('wolverine', 1);
        $this->assertEquals($stubComics->getData()->getResults(), $comics->getResults());
      }

      /**
       * {@inheritdoc}
       */
      public function tearDown()
      {
        $this->prophet->checkPredictions();
      }
}
