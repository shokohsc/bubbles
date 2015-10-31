<?php

namespace Shoko\ApiBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Shoko\ApiBundle\Repository\SerieRepository;
use Octante\MarvelAPIBundle\Repositories\SeriesRepository;
use Octante\MarvelAPIBundle\Repositories\ComicsRepository;

class SerieRepositoryTest extends \PHPUnit_Framework_TestCase
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
        $this->queryMock = $this->getMockBuilder('Octante\MarvelAPIBundle\Model\Query\SerieQuery')
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
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Serie/getSerieComics.json');
        $this->client
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue($jsonResponse));
        $sut = new SeriesRepository($this->client);
        $stubComics = $sut->getComicsFromSerie(17959, $this->queryMock);// The Superior Foes of Spider-Man (2013 - Present)

        $sutComicsRepository = new ComicsRepository($this->client);
        $repository = new SerieRepository($sut, $this->queryMock, $sutComicsRepository, $this->comicQueryMock, $this->comicsPerPage);
        $comics = $repository->findAllComicsById(12339, 1)->getResults();
        $this->assertEquals($stubComics->getData()->getResults(), $comics);
      }

      public function testFindOneById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Serie/getSerie.json');
        $this->client
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue($jsonResponse));

        $sut = new SeriesRepository($this->client);
        $stubSerie = $sut->getSerieById(18468);// Ms. Marvel (2014 - Present)

        $repository = new SerieRepository($sut, $this->queryMock, $this->comicRepositoryMock, $this->comicQueryMock, $this->comicsPerPage);
        $serie = $repository->findOneById(18468)->getResults()[0];
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Entities\Serie', $serie);
        $this->assertEquals($stubSerie->getData()->getResults()[0], $serie);
      }

      public function testFindAllByQuery()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Serie/getSeries.json');
        $this->client
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue($jsonResponse));

        $sut = new SeriesRepository($this->client);
        $stubSeries = $sut->getSeries($this->queryMock);// avengers

        $repository = new SerieRepository($sut, $this->queryMock, $this->comicRepositoryMock, $this->comicQueryMock, $this->comicsPerPage);
        $series = $repository->findAllByQuery('avengers', 1)->getResults();
        $this->assertEquals($stubSeries->getData()->getResults(), $series);
      }

}
