<?php

namespace Shoko\ApiBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Shoko\ApiBundle\Repository\ComicRepository;
use Octante\MarvelAPIBundle\Repositories\ComicsRepository;
use Carbon\Carbon;

class ComicRepositoryTest extends \PHPUnit_Framework_TestCase
{
      private $client;
      private $queryMock;
      private $comicsPerPage;

      public function setUp()
      {
        $this->client = $this->getMockBuilder('Octante\MarvelAPIBundle\Lib\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $this->queryMock = $this->getMockBuilder('Octante\MarvelAPIBundle\Model\Query\ComicQuery')
            ->disableOriginalConstructor()
            ->getMock();
        $this->comicsPerPage = 20;
      }

      /**
       * Call protected/private method of a class.
       *
       * @param object &$object    Instantiated object that we will run method on.
       * @param string $methodName Method name to call
       * @param array  $parameters Array of parameters to pass into method.
       *
       * @return mixed Method return.
       */
      public function invokeMethod(&$object, $methodName, array $parameters = array())
      {
          $reflection = new \ReflectionClass(get_class($object));
          $method = $reflection->getMethod($methodName);
          $method->setAccessible(true);

          return $method->invokeArgs($object, $parameters);
      }

      public function testGetReleaseDateRange()
      {
        $stub = $this->getMockBuilder('Shoko\ApiBundle\Repository\ComicRepository')
                    ->disableOriginalConstructor()
                    ->getMock();

        $fakeDate = Carbon::now();
        $end = $fakeDate->toDateString();
        $start = $fakeDate->copy()->subDays(6)->toDateString();
        $this->assertEquals($this->invokeMethod($stub, 'getReleaseDateRange', [$fakeDate]), $start.','.$end);
      }

      public function testFindAllByReleaseDate()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Comic/getComicsDate.json');
        $this->client
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue($jsonResponse));
        $sut = new ComicsRepository($this->client);
        $stubComics = $sut->getComics($this->queryMock);// Apr 27, 2015

        $sutComicsRepository = new ComicsRepository($this->client);
        $repository = new ComicRepository($sut, $this->queryMock, $this->comicsPerPage);
        $comics = $repository->findAllByReleaseDate(Carbon::parse('Apr 27, 2015'), 1);
        $this->assertEquals($stubComics->getData()->getResults(), $comics);
      }

      public function testFindOneById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Comic/getComic.json');
        $this->client
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue($jsonResponse));

        $sut = new ComicsRepository($this->client);
        $stubComic = $sut->getComicById(52237);// Moon Knight (2014) #15

        $repository = new ComicRepository($sut, $this->queryMock, $this->comicsPerPage);
        $comic = $repository->findOneById(18468);
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Entities\Comic', $comic);
        $this->assertEquals($stubComic->getData()->getResults()[0], $comic);
      }

      public function testFindAllByQuery()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Comic/getComics.json');
        $this->client
            ->expects($this->any())
            ->method('send')
            ->will($this->returnValue($jsonResponse));

        $sut = new ComicsRepository($this->client);
        $stubComics = $sut->getComics($this->queryMock);// wolverine

        $repository = new ComicRepository($sut, $this->queryMock, $this->comicsPerPage);
        $comics = $repository->findAllByQuery('wolverine', 1);
        $this->assertEquals($stubComics->getData()->getResults(), $comics);
      }

}
