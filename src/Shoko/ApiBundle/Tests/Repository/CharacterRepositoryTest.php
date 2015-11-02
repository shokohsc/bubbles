<?php

namespace Shoko\ApiBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Shoko\ApiBundle\Repository\CharacterRepository;
use Octante\MarvelAPIBundle\Repositories\CharactersRepository;
use Octante\MarvelAPIBundle\Repositories\ComicsRepository;
use Prophecy\Prophet;
use Prophecy\Argument;

/**
 * CharacterRepositoryTest class.
 */
class CharacterRepositoryTest extends \PHPUnit_Framework_TestCase
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
       * Octante\MarvelAPIBundle\Model\Query\CharacterQuery $queryMock.
       *
       * @var Octante\MarvelAPIBundle\Model\Query\CharacterQuery
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
        $this->queryMock = $this->prophet->prophesize('Octante\MarvelAPIBundle\Model\Query\CharacterQuery');
        $this->comicRepositoryMock = $this->prophet->prophesize('Octante\MarvelAPIBundle\Repositories\ComicsRepository');
        $this->comicQueryMock = $this->prophet->prophesize('Octante\MarvelAPIBundle\Model\Query\ComicQuery');
        $this->comicsPerPage = 20;
      }

      /**
       * testFindAllComicsById method
       */
      public function testFindAllComicsById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Character/getCharacterComics.json');
        $this->client->send(Argument::any())->willReturn($jsonResponse);
        $sut = new CharactersRepository($this->client->reveal());
        $stubComics = $sut->getComicsFromCharacter(1009663, $this->queryMock->reveal());// Venom (Flash Thompson)
        $sutComicsRepository = new ComicsRepository($this->client->reveal());
        $repository = new CharacterRepository($sut, $this->queryMock->reveal(), $sutComicsRepository, $this->comicQueryMock->reveal(), $this->comicsPerPage);
        $comics = $repository->findAllComicsById(1009663, 1);
        $this->assertEquals($stubComics->getData()->getResults(), $comics->getResults());
      }

      /**
       * testFindOneById method
       */
      public function testFindOneById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Character/getCharacter.json');
        $this->client->send(Argument::any())->willReturn($jsonResponse);
        $sut = new CharactersRepository($this->client->reveal());
        $stubCharacter = $sut->getCharacterById(1010338);// Captain Marvel (Carol Danvers)
        $repository = new CharacterRepository($sut, $this->queryMock->reveal(), $this->comicRepositoryMock->reveal(), $this->comicQueryMock->reveal(), $this->comicsPerPage);
        $character = $repository->findOneById(1010338);
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\DataContainer\DataContainer', $character);
        $this->assertEquals($stubCharacter->getData()->getResults()[0], $character->getResults()[0]);
      }

      /**
       * testFindAllByQuery method
       */
      public function testFindAllByQuery()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Character/getCharacters.json');
        $this->client->send(Argument::any())->willReturn($jsonResponse);
        $sut = new CharactersRepository($this->client->reveal());
        $stubCharacters = $sut->getCharacters($this->queryMock->reveal());// spider
        $repository = new CharacterRepository($sut, $this->queryMock->reveal(), $this->comicRepositoryMock->reveal(), $this->comicQueryMock->reveal(), $this->comicsPerPage);
        $characters = $repository->findAllByQuery('spider', 1);
        $this->assertEquals($stubCharacters->getData()->getResults(), $characters->getResults());
      }

      /**
       * {@inheritdoc}
       */
      public function tearDown()
      {
        $this->prophet->checkPredictions();
      }
}
