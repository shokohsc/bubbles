<?php

namespace Shoko\ApiBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Shoko\ApiBundle\Repository\ComicRepository;

use Octante\MarvelAPIBundle\Repositories\ComicsRepository;
use Carbon\Carbon;

class ComicRepositoryTest extends \PHPUnit_Framework_TestCase
{
      private $client;
      private $repositoryMock;
      private $queryMock;

      public function setUp()
      {
        $this->client = $this->getMockBuilder('Octante\MarvelAPIBundle\Lib\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $this->repositoryMock = $this->getMockBuilder('Octante\MarvelAPIBundle\Repositories\ComicsRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $this->queryMock = $this->getMockBuilder('Octante\MarvelAPIBundle\Model\Query\ComicQuery')
            ->disableOriginalConstructor()
            ->getMock();
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
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($jsonResponse));
        $sut = new ComicsRepository($this->client);
        $comics = $sut->getComics($this->queryMock);// Apr 27, 2015
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Collections\ComicsCollection', $comics);
      }

      public function testFindOneById()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Comic/getComic.json');
        $this->client
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($jsonResponse));
        $sut = new ComicsRepository($this->client);
        $comic = $sut->getComicById(52237);// Moon Knight (2014) #15
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Collections\ComicsCollection', $comic);
      }

      public function testFindAllByQuery()
      {
        $jsonResponse = file_get_contents(__DIR__ . '/../Fixtures/Comic/getComics.json');
        $this->client
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($jsonResponse));
        $sut = new ComicsRepository($this->client);
        $comics = $sut->getComics($this->queryMock);// wolverine
        $this->assertInstanceOf('Octante\MarvelAPIBundle\Model\Collections\ComicsCollection', $comics);
      }

}
