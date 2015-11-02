<?php

namespace Shoko\ApiBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Shoko\ApiBundle\Repository\CreatorRepository;
use Octante\MarvelAPIBundle\Repositories\CreatorsRepository;
use Octante\MarvelAPIBundle\Repositories\ComicsRepository;
use Prophecy\Prophet;
use Prophecy\Argument;

/**
 * CreatorRepositoryTest class.
 */
class CreatorRepositoryTest extends \PHPUnit_Framework_TestCase
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
       * Octante\MarvelAPIBundle\Model\Query\CreatorQuery $queryMock.
       *
       * @var Octante\MarvelAPIBundle\Model\Query\CreatorQuery
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
        $this->queryMock = $this->prophet->prophesize('Octante\MarvelAPIBundle\Model\Query\CreatorQuery');
        $this->comicRepositoryMock = $this->prophet->prophesize('Octante\MarvelAPIBundle\Repositories\ComicsRepository');
        $this->comicQueryMock = $this->prophet->prophesize('Octante\MarvelAPIBundle\Model\Query\ComicQuery');
        $this->comicsPerPage = 20;
      }

      /**
       * testFindAllComicsById method
       */
      public function testFindAllComicsById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Creator/getCreatorComics.json');
        $this->client->send(Argument::any())->willReturn($jsonResponse);
        $sut = new CreatorsRepository($this->client->reveal());
        $stubComics = $sut->getComicsFromCreator(12339, $this->queryMock->reveal());// Kevin P. Wada
        $sutComicsRepository = new ComicsRepository($this->client->reveal());
        $repository = new CreatorRepository($sut, $this->queryMock->reveal(), $sutComicsRepository, $this->comicQueryMock->reveal(), $this->comicsPerPage);
        $comics = $repository->findAllComicsById(12339, 1);
        $this->assertEquals($stubComics->getData()->getResults(), $comics->getResults());
      }

      /**
       * testFindOneById method
       */
      public function testFindOneById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Creator/getCreator.json');
        $this->client->send(Argument::any())->willReturn($jsonResponse);
        $sut = new CreatorsRepository($this->client->reveal());
        $stubCreator = $sut->getCreatorById(2997);// Fred Hembeck
        $repository = new CreatorRepository($sut, $this->queryMock->reveal(), $this->comicRepositoryMock->reveal(), $this->comicQueryMock->reveal(), $this->comicsPerPage);
        $creator = $repository->findOneById(2997);
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\DataContainer\DataContainer', $creator);
        $this->assertEquals($stubCreator->getData()->getResults()[0], $creator->getResults()[0]);
      }

      /**
       * testFindAllByQuery method
       */
      public function testFindAllByQuery()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Creator/getCreators.json');
        $this->client->send(Argument::any())->willReturn($jsonResponse);
        $sut = new CreatorsRepository($this->client->reveal());
        $stubCreators = $sut->getCreators($this->queryMock->reveal());// stan
        $repository = new CreatorRepository($sut, $this->queryMock->reveal(), $this->comicRepositoryMock->reveal(), $this->comicQueryMock->reveal(), $this->comicsPerPage);
        $creators = $repository->findAllByQuery('stan', 1);
        $this->assertEquals($stubCreators->getData()->getResults(), $creators->getResults());
      }

      /**
       * {@inheritdoc}
       */
      public function tearDown()
      {
        $this->prophet->checkPredictions();
      }
}
