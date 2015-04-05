<?php

namespace Front\AppBundle\Repository;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Octante\MarvelAPIBundle\Repositories\SeriesRepository;
use Octante\MarvelAPIBundle\Model\Query\SerieQuery;

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
     * SerieRepository constructor
     *
     * @param SeriesRepository $repository
     * @param SerieQuery       $query
     * @param integer          $comicsPerPage
     */
    public function __construct(SeriesRepository $repository, SerieQuery $query, $comicsPerPage)
    {
        $this->repository = $repository;
        $this->query = $query;
        $this->comicsPerPage  = $comicsPerPage;
    }


    /**
     * Find all comics matching serie id
     *
     * @param string $id
     * @param string $page
     * @return Octante\MarvelAPIBundle\Model\Collections\SeriesCollection|array
     */
    public function findAllComicsById($id, $page)
    {
        $comics_per_page = $this->comicsPerPage;
        try {
            $this->query->setSeries($id);
            $this->query->setFormat('comic');
            $this->query->setFormatType('comic');
            $this->query->setNoVariants(true);
            $this->query->setOrderBy('-issueNumber');
            $this->query->setLimit($comics_per_page);
            $this->query->setOffset(($page * $comics_per_page) - $comics_per_page);

            return $this->repository
                ->getComics($this->query)
                ->getData()
                ->getResults();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Find one serie matching id
     *
     * @param string $id
     * @return Octante\MarvelAPIBundle\Model\Collections\SeriesCollection|array
     */
    public function findOneById($id)
    {
        try {
            return $this->repository
                ->getSerieById(intval($id))
                ->getData()
                ->getResults();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Find all series matching query
     *
     * @param string $query input from search form
     * @return Octante\MarvelAPIBundle\Model\Collections\SeriesCollection|array
     */
    public function findAllByQuery($query)
    {
        try {
            $filters = [
                'titleStartsWith' => $query,
                'orderBy' => '-startYear',
                'contains' => 'comic',
                'limit' => 100,
                ];

            $this->query->setTitleStartsWith($query);
            $this->query->setOrderBy('-startYear');
            $this->query->setContains('comic');
            $this->query->setLimit(100);

            return $this->repository
                ->getSeries($this->query)
                ->getData()
                ->getResults();
        } catch (Exception $e) {
            return array();
        }
    }
}
