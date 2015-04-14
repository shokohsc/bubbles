<?php

namespace Front\AppBundle\Repository;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Octante\MarvelAPIBundle\Repositories\ComicsRepository;
use Octante\MarvelAPIBundle\Model\Query\ComicQuery;
use Carbon\Carbon;

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
     * @return Octante\MarvelAPIBundle\Model\Collections\ComicsCollection|array
     */
    public function findAllByReleaseDate(Carbon $date)
    {
        try {
            $this->query->setFormat('comic');
            $this->query->setFormatType('comic');
            $this->query->setNoVariants(true);
            $this->query->setDateRange($this->getReleaseDateRange($date));
            $this->query->setOrderBy('title');
            $this->query->setLimit(100);

            return $this->repository
                ->getComics($this->query)
                ->getData()
                ->getResults();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Find all comics from the beginning of the serie
     *
     * @param string $id matching serie id
     * @return Octante\MarvelAPIBundle\Model\Collections\ComicsCollection|array
     */
    public function findAllByStartUntilNow($id)
    {
        try {
            $this->query->setFormat('comic');
            $this->query->setFormatType('comic');
            $this->query->setNoVariants(true);
            $this->query->setSeries($id);
            $this->query->setDateRange(Carbon::create(1939, 01, 01)->toDateString().','.Carbon::now()->toDateString());
            $this->query->setOrderBy('-issueNumber');

            return $this->repository
                ->getComics($this->query)
                ->getData()
                ->getResults();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Find one comic matching id
     *
     * @param string $id comic id
     * @return Octante\MarvelAPIBundle\Model\Collections\ComicsCollection|array
     */
    public function findOneById($id)
    {
        try {
            return $this->repository
                ->getComicById(intval($id))
                ->getData()
                ->getResults()[0];
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Find all comics matching query
     *
     * @param string $query input from search form
     * @return Octante\MarvelAPIBundle\Model\Collections\SeriesCollection|array
     */
     public function findAllByQuery($query, $page)
     {
         $comics_per_page = $this->comicsPerPage;
        try {
            $this->query->setTitleStartsWith($query);
            $this->query->setOrderBy('-onsaleDate');
            $this->query->setFormat('comic');
            $this->query->setFormatType('comic');
            $this->query->setNoVariants(true);
            $this->query->setLimit($comics_per_page);
            $this->query->setOffset(($page * $comics_per_page) - $comics_per_page);

            return $this->repository
                ->getComics($this->query)
                ->getData()
                ->getResults();
        } catch (Exception $e) {
            return [];
        }
    }
}
