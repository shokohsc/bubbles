<?php

namespace Shoko\ApiBundle\Repository;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Octante\MarvelAPIBundle\Repositories\ComicsRepository;
use Octante\MarvelAPIBundle\Model\Query\ComicQuery;
use Carbon\Carbon;

/**
 * ComicRepository class.
 */
class ComicRepository
{
    /**
     * Number of comics displayed
     *
     * @var integer
     */
    private $comicsPerPage;

    /**
     * ComicsRepository
     *
     * @var ComicsRepository
     */
    private $repository;

    /**
     * ComicQuery
     *
     * @var ComicQuery
     */
    private $query;

    /**
     * ComicRepository constructor
     *
     * @param ComicsRepository $repository
     * @param ComicQuery       $query
     */
    public function __construct(ComicsRepository $repository, ComicQuery $query, $comicsPerPage)
    {
        $this->repository = $repository;
        $this->query = $query;
        $this->comicsPerPage  = $comicsPerPage;
    }

    /**
     * Get Release date range from the given date
     *
     * @param Carbon $date
     *
     * @return string
     */
    protected function getReleaseDateRange(Carbon $date)
    {
        $end = $date->toDateString();
        $start = $date->copy()->subDays(6)->toDateString();

        return $start.','.$end;
    }

    /**
     * Find all released comics the week containing the $date
     *
     * @param Carbon $date
     *
     * @return Octante\MarvelAPIBundle\Model\DataContainer\ComicDataContainer
     */
    public function findAllByReleaseDate(Carbon $date)
    {
        $this->query->setFormatType('comic');
        $this->query->setFormat('comic');
        $this->query->setNoVariants(true);
        $this->query->setDateRange($this->getReleaseDateRange($date));
        $this->query->setOrderBy('title');
        $this->query->setLimit(100);
        return $this->repository
            ->getComics($this->query)
            ->getData();
    }

    /**
     * Find one comic matching id
     *
     * @param string $id comic id
     *
     * @return Octante\MarvelAPIBundle\Model\DataContainer\ComicDataContainer
     */
    public function findOneById($id)
    {
        return $this->repository
            ->getComicById(intval($id))
            ->getData();
    }

    /**
     * Find all comics matching query
     *
     * @param string $query input from search form
     * 
     * @return Octante\MarvelAPIBundle\Model\DataContainer\ComicDataContainer
     */
     public function findAllByQuery($query, $page)
     {
        $comics_per_page = $this->comicsPerPage;
        $this->query->setTitleStartsWith($query);
        $this->query->setOrderBy('-onsaleDate');
        $this->query->setFormat('comic');
        $this->query->setFormatType('comic');
        $this->query->setNoVariants(true);
        $this->query->setLimit($comics_per_page);
        $this->query->setOffset(($page * $comics_per_page) - $comics_per_page);

        return $this->repository
            ->getComics($this->query)
            ->getData();
    }
}
