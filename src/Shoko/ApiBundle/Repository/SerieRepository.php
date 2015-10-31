<?php

namespace Shoko\ApiBundle\Repository;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Octante\MarvelAPIBundle\Repositories\ComicsRepository;
use Octante\MarvelAPIBundle\Model\Query\ComicQuery;
use Octante\MarvelAPIBundle\Repositories\SeriesRepository;
use Octante\MarvelAPIBundle\Model\Query\SerieQuery;

/**
 * SerieRepository class.
 */
class SerieRepository
{
    /**
     * Number of comics displayed
     *
     * @var integer
     */
    private $comicsPerPage;

    /**
     * SeriesRepository
     *
     * @var SeriesRepository
     */
    private $repository;

    /**
     * SerieQuery
     *
     * @var SerieQuery
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
     * SerieRepository constructor
     *
     * @param SeriesRepository $repository
     * @param SerieQuery       $query
     * @param ComicsRepository $comicRepository
     * @param ComicQuery       $comicQuery
     * @param integer          $comicsPerPage
     */
    public function __construct(SeriesRepository $repository, SerieQuery $query, ComicsRepository $comicRepository, ComicQuery $comicQuery, $comicsPerPage)
    {
        $this->repository = $repository;
        $this->query = $query;
        $this->comicRepository = $comicRepository;
        $this->comicQuery = $comicQuery;
        $this->comicsPerPage  = $comicsPerPage;
    }


    /**
     * Find all comics matching serie id
     *
     * @param string $id
     * @param string $page
     *
     * @return Octante\MarvelAPIBundle\Model\DataContainer\SerieDataContainer
     */
    public function findAllComicsById($id, $page)
    {
        $comics_per_page = $this->comicsPerPage;
        $this->comicQuery->setSeries($id);
        $this->comicQuery->setFormat('comic');
        $this->comicQuery->setFormatType('comic');
        $this->comicQuery->setNoVariants(true);
        $this->comicQuery->setOrderBy('-issueNumber');
        $this->comicQuery->setLimit($comics_per_page);
        $this->comicQuery->setOffset(($page * $comics_per_page) - $comics_per_page);

        return $this->comicRepository
            ->getComics($this->comicQuery)
            ->getData();
    }

    /**
     * Find one serie matching id
     *
     * @param string $id
     *
     * @return Octante\MarvelAPIBundle\Model\DataContainer\SerieDataContainer
     */
    public function findOneById($id)
    {
        return $this->repository
            ->getSerieById(intval($id))
            ->getData();
    }

    /**
     * Find all series matching query
     *
     * @param string $query input from search form
     * 
     * @return Octante\MarvelAPIBundle\Model\DataContainer\SerieDataContainer
     */
    public function findAllByQuery($query, $page)
    {
          $comics_per_page = $this->comicsPerPage;
          $this->query->setTitleStartsWith($query);
          $this->query->setOrderBy('-startYear');
          $this->query->setContains('comic');
          $this->query->setLimit($comics_per_page);
          $this->query->setOffset(($page * $comics_per_page) - $comics_per_page);

          return $this->repository
              ->getSeries($this->query)
              ->getData();
    }
}
