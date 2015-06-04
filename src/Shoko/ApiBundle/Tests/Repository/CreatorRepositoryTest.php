<?php

namespace Shoko\ApiBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Shoko\ApiBundle\Repository\CreatorRepository;

use Octante\MarvelAPIBundle\Repositories\CreatorsRepository;

class CreatorRepositoryTest extends \PHPUnit_Framework_TestCase
{
      private $client;
      private $repositoryMock;
      private $queryMock;

      public function setUp()
      {
        $this->client = $this->getMockBuilder('Octante\MarvelAPIBundle\Lib\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $this->repositoryMock = $this->getMockBuilder('Octante\MarvelAPIBundle\Repositories\CreatorsRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $this->queryMock = $this->getMockBuilder('Octante\MarvelAPIBundle\Model\Query\CreatorQuery')
            ->disableOriginalConstructor()
            ->getMock();
      }

      public function testFindAllComicsById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Creator/getCreatorComics.json');
        $this->client
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($jsonResponse));
        $sut = new CreatorsRepository($this->client);
        $comics = $sut->getComicsFromCreator(12339, $this->queryMock);// Kevin P. Wada
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Collections\ComicsCollection', $comics);
      }

      public function testFindOneById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Creator/getCreator.json');
        $this->client
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($jsonResponse));
        $sut = new CreatorsRepository($this->client);
        $creator = $sut->getCreatorById(2997);// Fred Hembeck
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Collections\CreatorsCollection', $creator);
      }

      public function testFindAllByQuery()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Creator/getCreators.json');
        $this->client
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($jsonResponse));
        $sut = new CreatorsRepository($this->client);
        $creators = $sut->getCreators($this->queryMock);// stan
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Collections\CreatorsCollection', $creators);
      }

}
