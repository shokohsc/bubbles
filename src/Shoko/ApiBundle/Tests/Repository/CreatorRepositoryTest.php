<?php

namespace Shoko\ApiBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Shoko\ApiBundle\Repository\CreatorRepository;
use Octante\MarvelAPIBundle\Repositories\CreatorsRepository;
use Octante\MarvelAPIBundle\Repositories\ComicsRepository;

class CreatorRepositoryTest extends \PHPUnit_Framework_TestCase
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
        $this->queryMock = $this->getMockBuilder('Octante\MarvelAPIBundle\Model\Query\CreatorQuery')
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
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Creator/getCreatorComics.json');
        $this->client
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue($jsonResponse));
        $sut = new CreatorsRepository($this->client);
        $stubComics = $sut->getComicsFromCreator(12339, $this->queryMock);// Kevin P. Wada

        $sutComicsRepository = new ComicsRepository($this->client);
        $repository = new CreatorRepository($sut, $this->queryMock, $sutComicsRepository, $this->comicQueryMock, $this->comicsPerPage);
        $comics = $repository->findAllComicsById(12339, 1);
        $this->assertEquals($stubComics->getData()->getResults(), $comics);
      }

      public function testFindOneById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Creator/getCreator.json');
        $this->client
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue($jsonResponse));

        $sut = new CreatorsRepository($this->client);
        $stubCreator = $sut->getCreatorById(2997);// Fred Hembeck

        $repository = new CreatorRepository($sut, $this->queryMock, $this->comicRepositoryMock, $this->comicQueryMock, $this->comicsPerPage);
        $creator = $repository->findOneById(2997);
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Entities\Creator', $creator);
        $this->assertEquals($stubCreator->getData()->getResults()[0], $creator);
      }

      public function testFindAllByQuery()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Creator/getCreators.json');
        $this->client
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue($jsonResponse));

        $sut = new CreatorsRepository($this->client);
        $stubCreators = $sut->getCreators($this->queryMock);// stan

        $repository = new CreatorRepository($sut, $this->queryMock, $this->comicRepositoryMock, $this->comicQueryMock, $this->comicsPerPage);
        $creators = $repository->findAllByQuery('stan', 1);
        $this->assertEquals($stubCreators->getData()->getResults(), $creators);
      }

}
