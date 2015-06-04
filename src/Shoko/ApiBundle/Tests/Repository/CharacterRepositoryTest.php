<?php

namespace Shoko\ApiBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Shoko\ApiBundle\Repository\CharacterRepository;

use Octante\MarvelAPIBundle\Repositories\CharactersRepository;

class CharacterRepositoryTest extends \PHPUnit_Framework_TestCase
{
      private $client;
      private $repositoryMock;
      private $queryMock;

      public function setUp()
      {
        $this->client = $this->getMockBuilder('Octante\MarvelAPIBundle\Lib\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $this->repositoryMock = $this->getMockBuilder('Octante\MarvelAPIBundle\Repositories\CharactersRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $this->queryMock = $this->getMockBuilder('Octante\MarvelAPIBundle\Model\Query\CharacterQuery')
            ->disableOriginalConstructor()
            ->getMock();
      }

      public function testFindAllComicsById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Character/getCharacterComics.json');
        $this->client
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($jsonResponse));
        $sut = new CharactersRepository($this->client);
        $comics = $sut->getComicsFromCharacter(1009663, $this->queryMock);// Venom (Flash Thompson)
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Collections\ComicsCollection', $comics);
      }

      public function testFindOneById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Character/getCharacter.json');
        $this->client
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($jsonResponse));
        $sut = new CharactersRepository($this->client);
        $character = $sut->getCharacterById(1010338);// Captain Marvel (Carol Danvers)
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Collections\CharactersCollection', $character);
      }

      public function testFindAllByQuery()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Character/getCharacters.json');
        $this->client
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($jsonResponse));
        $sut = new CharactersRepository($this->client);
        $characters = $sut->getCharacters($this->queryMock);// spider
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Collections\CharactersCollection', $characters);
      }

}
