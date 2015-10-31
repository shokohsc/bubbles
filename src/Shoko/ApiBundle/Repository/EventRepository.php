<?php

namespace Shoko\ApiBundle\Repository;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Octante\MarvelAPIBundle\Repositories\ComicsRepository;
use Octante\MarvelAPIBundle\Model\Query\ComicQuery;
use Octante\MarvelAPIBundle\Repositories\EventsRepository;
use Octante\MarvelAPIBundle\Model\Query\EventQuery;

/**
 * EventRepository class.
 */
class EventRepository
{
    /**
     * Number of comics displayed
     *
     * @var integer
     */
    private $comicsPerPage;

    /**
     * EventsRepository
     *
     * @var EventsRepository
     */
    private $repository;

    /**
     * EventQuery
     *
     * @var EventQuery
     */
    private $query;

    /**
     * ComicsRepository
     *
     * @var ComicsRepository
     */
    private $comicRepository;

    /**
     * ComicQuery
     *
     * @var ComicQuery
     */
    private $comicQuery;

    /**
     * EventRepository constructor
     *
     * @param EventsRepository $repository
     * @param EventQuery       $query
     * @param ComicsRepository $comicRepository
     * @param ComicQuery       $comicQuery
     * @param integer          $comicsPerPage
     */
    public function __construct(EventsRepository $repository, EventQuery $query, ComicsRepository $comicRepository, ComicQuery $comicQuery, $comicsPerPage)
    {
        $this->repository = $repository;
        $this->query = $query;
        $this->comicRepository = $comicRepository;
        $this->comicQuery = $comicQuery;
        $this->comicsPerPage  = $comicsPerPage;
    }

    /**
     * Find all comics matching event id
     *
     * @param string $id
     * @param string $page
     *
     * @return Octante\MarvelAPIBundle\Model\DataContainer\EventDataContainer
     */
    public function findAllComicsById($id, $page)
    {
        $comics_per_page = $this->comicsPerPage;
        $this->comicQuery->setEvents($id);
        $this->comicQuery->setFormat('comic');
        $this->comicQuery->setFormatType('comic');
        $this->comicQuery->setNoVariants(true);
        $this->comicQuery->setOrderBy('-onsaleDate');
        $this->comicQuery->setLimit($comics_per_page);
        $this->comicQuery->setOffset(($page * $comics_per_page) - $comics_per_page);

        return $this->comicRepository
            ->getComics($this->comicQuery)
            ->getData();
    }

    /**
     * Find one event matching id
     *
     * @param string $id
     *
     * @return Octante\MarvelAPIBundle\Model\DataContainer\EventDataContainer
     */
    public function findOneById($id)
    {
        return $this->repository
            ->getEventById(intval($id))
            ->getData();
    }

    /**
     * Find all events matching query
     *
     * @param string $query input from search form
     * 
     * @return Octante\MarvelAPIBundle\Model\DataContainer\EventDataContainer
     */
     public function findAllByQuery($query, $page)
     {
          $comics_per_page = $this->comicsPerPage;
          $this->query->setNameStartsWith($query);
          $this->query->setOrderBy('-startDate');
          $this->query->setLimit($comics_per_page);
          $this->query->setOffset(($page * $comics_per_page) - $comics_per_page);

          return $this->repository
              ->getEvents($this->query)
              ->getData();
    }
}
