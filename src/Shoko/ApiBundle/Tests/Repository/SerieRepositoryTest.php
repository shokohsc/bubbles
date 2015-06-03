<?php

namespace Shoko\ApiBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Shoko\ApiBundle\Repository\SerieRepository;

use Octante\MarvelAPIBundle\Repositories\SeriesRepository;

class SerieRepositoryTest extends \PHPUnit_Framework_TestCase
{
      private $client;
      private $repositoryMock;
      private $queryMock;

      public function setUp()
      {
        $this->client = $this->getMockBuilder('Octante\MarvelAPIBundle\Lib\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $this->repositoryMock = $this->getMockBuilder('Octante\MarvelAPIBundle\Repositories\SeriesRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $this->queryMock = $this->getMockBuilder('Octante\MarvelAPIBundle\Model\Query\SerieQuery')
            ->disableOriginalConstructor()
            ->getMock();
      }

      public function testFindAllComicsById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Serie/getSerieComics.json');
        $this->client
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($jsonResponse));
        $sut = new SeriesRepository($this->client);
        $comics = $sut->getComicsFromSerie(17959, $this->queryMock);// The Superior Foes of Spider-Man (2013 - Present)
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Collections\ComicsCollection', $comics);
      }

      public function testFindOneById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Serie/getSerie.json');
        $this->client
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($jsonResponse));
        $sut = new SeriesRepository($this->client);
        $serie = $sut->getSerieById(18468);// Ms. Marvel (2014 - Present)
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Collections\SeriesCollection', $serie);
      }

      public function testFindAllByQuery()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Serie/getSeries.json');
        $this->client
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($jsonResponse));
        $sut = new SeriesRepository($this->client);
        $series = $sut->getSeries($this->queryMock);// avengers
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Collections\SeriesCollection', $series);
      }

}
