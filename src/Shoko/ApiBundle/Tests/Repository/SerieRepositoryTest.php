<?php

namespace Shoko\ApiBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Shoko\ApiBundle\Repository\SerieRepository;
use Octante\MarvelAPIBundle\Repositories\SeriesRepository;
use Octante\MarvelAPIBundle\Repositories\ComicsRepository;
use Prophecy\Prophet;
use Prophecy\Argument;

/**
 * SerieRepositoryTest class.
 */
class SerieRepositoryTest extends \PHPUnit_Framework_TestCase
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
       * Octante\MarvelAPIBundle\Model\Query\SeriesQuery $queryMock.
       *
       * @var Octante\MarvelAPIBundle\Model\Query\SeriesQuery
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
        $this->queryMock = $this->prophet->prophesize('Octante\MarvelAPIBundle\Model\Query\SerieQuery');
        $this->comicRepositoryMock = $this->prophet->prophesize('Octante\MarvelAPIBundle\Repositories\ComicsRepository');
        $this->comicQueryMock = $this->prophet->prophesize('Octante\MarvelAPIBundle\Model\Query\ComicQuery');
        $this->comicsPerPage = 20;
      }

      /**
       * testFindAllComicsById method
       */
      public function testFindAllComicsById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Serie/getSerieComics.json');
        $this->client->send(Argument::any())->willReturn($jsonResponse);
        $sut = new SeriesRepository($this->client->reveal());
        $stubComics = $sut->getComicsFromSerie(17959, $this->queryMock->reveal());// The Superior Foes of Spider-Man (2013 - Present)
        $sutComicsRepository = new ComicsRepository($this->client->reveal());
        $repository = new SerieRepository($sut, $this->queryMock->reveal(), $sutComicsRepository, $this->comicQueryMock->reveal(), $this->comicsPerPage);
        $comics = $repository->findAllComicsById(17959, 1);
        $this->assertEquals($stubComics->getData()->getResults(), $comics->getResults());
      }

      /**
       * testFindOneById method
       */
      public function testFindOneById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Serie/getSerie.json');
        $this->client->send(Argument::any())->willReturn($jsonResponse);
        $sut = new SeriesRepository($this->client->reveal());
        $stubSeries = $sut->getSerieById(18468);// Ms. Marvel (2014 - Present)
        $repository = new SerieRepository($sut, $this->queryMock->reveal(), $this->comicRepositoryMock->reveal(), $this->comicQueryMock->reveal(), $this->comicsPerPage);
        $series = $repository->findOneById(18468);
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\DataContainer\DataContainer', $series);
        $this->assertEquals($stubSeries->getData()->getResults()[0], $series->getResults()[0]);
      }

      /**
       * testFindAllByQuery method
       */
      public function testFindAllByQuery()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Serie/getSeries.json');
        $this->client->send(Argument::any())->willReturn($jsonResponse);
        $sut = new SeriesRepository($this->client->reveal());
        $stubSeries = $sut->getSeries($this->queryMock->reveal());// avengers
        $repository = new SerieRepository($sut, $this->queryMock->reveal(), $this->comicRepositoryMock->reveal(), $this->comicQueryMock->reveal(), $this->comicsPerPage);
        $series = $repository->findAllByQuery('avengers', 1);
        $this->assertEquals($stubSeries->getData()->getResults(), $series->getResults());
      }

      /**
       * {@inheritdoc}
       */
      public function tearDown()
      {
        $this->prophet->checkPredictions();
      }
}
