<?php

namespace Shoko\ApiBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Shoko\ApiBundle\Repository\CharacterRepository;
use Octante\MarvelAPIBundle\Repositories\CharactersRepository;
use Octante\MarvelAPIBundle\Repositories\ComicsRepository;

class CharacterRepositoryTest extends \PHPUnit_Framework_TestCase
{
      private $client;
      private $queryMock;
      private $comicRepositoryMock;
      private $comicQueryMock;
      private $comicsPerPage;

      public function setUp()
      {
        $this->client = $this->getMockBuilder('Octante\MarvelAPIBundle\Lib\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $this->queryMock = $this->getMockBuilder('Octante\MarvelAPIBundle\Model\Query\CharacterQuery')
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
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Character/getCharacterComics.json');
        $this->client
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue($jsonResponse));

        $sut = new CharactersRepository($this->client);
        $stubComics = $sut->getComicsFromCharacter(1009663, $this->queryMock);// Venom (Flash Thompson)

        $sutComicsRepository = new ComicsRepository($this->client);
        $repository = new CharacterRepository($sut, $this->queryMock, $sutComicsRepository, $this->comicQueryMock, $this->comicsPerPage);
        $comics = $repository->findAllComicsById(1009663, 1);
        $this->assertEquals($stubComics->getData()->getResults(), $comics);
      }

      public function testFindOneById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Character/getCharacter.json');
        $this->client
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue($jsonResponse));

        $sut = new CharactersRepository($this->client);
        $stubCharacter = $sut->getCharacterById(1010338);// Captain Marvel (Carol Danvers)

        $repository = new CharacterRepository($sut, $this->queryMock, $this->comicRepositoryMock, $this->comicQueryMock, $this->comicsPerPage);
        $character = $repository->findOneById(1010338);
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Entities\Character', $character);
        $this->assertEquals($stubCharacter->getData()->getResults()[0], $character);
      }

      public function testFindAllByQuery()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Character/getCharacters.json');
        $this->client
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue($jsonResponse));

        $sut = new CharactersRepository($this->client);
        $stubCharacters = $sut->getCharacters($this->queryMock);// spider

        $repository = new CharacterRepository($sut, $this->queryMock, $this->comicRepositoryMock, $this->comicQueryMock, $this->comicsPerPage);
        $characters = $repository->findAllByQuery('spider', 1);
        $this->assertEquals($stubCharacters->getData()->getResults(), $characters);
      }

}
