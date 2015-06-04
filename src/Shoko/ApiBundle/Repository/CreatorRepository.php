<?php

namespace Shoko\ApiBundle\Repository;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Octante\MarvelAPIBundle\Repositories\ComicsRepository;
use Octante\MarvelAPIBundle\Model\Query\ComicQuery;
use Octante\MarvelAPIBundle\Repositories\CreatorsRepository;
use Octante\MarvelAPIBundle\Model\Query\CreatorQuery;

class CreatorRepository
{
    /**
     * Number of comics displayed
     *
     * @var integer
     */
    private $comicsPerPage;

    /**
     * CreatorsRepository
     *
     * @var CreatorsRepository
     */
    private $repository;

    /**
     * CreatorQuery
     *
     * @var CreatorQuery
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
     * CreatorRepository constructor
     *
     * @param CreatorsRepository $repository
     * @param CreatorQuery       $query
     * @param ComicsRepository $comicRepository
     * @param ComicQuery       $comicQuery
     * @param integer          $comicsPerPage
     */
    public function __construct(CreatorsRepository $repository, CreatorQuery $query, ComicsRepository $comicRepository, ComicQuery $comicQuery, $comicsPerPage)
    {
        $this->repository = $repository;
        $this->query = $query;
        $this->comicRepository = $comicRepository;
        $this->comicQuery = $comicQuery;
        $this->comicsPerPage  = $comicsPerPage;
    }

    /**
     * Find all comics matching creator id
     *
     * @param string $id
     * @param string $page
     * @return array
     */
    public function findAllComicsById($id, $page)
    {
        $comics_per_page = $this->comicsPerPage;
        $this->comicQuery->setCreators($id);
        $this->comicQuery->setFormat('comic');
        $this->comicQuery->setFormatType('comic');
        $this->comicQuery->setNoVariants(true);
        $this->comicQuery->setOrderBy('-onsaleDate');
        $this->comicQuery->setLimit($comics_per_page);
        $this->comicQuery->setOffset(($page * $comics_per_page) - $comics_per_page);

        return $this->comicRepository
            ->getComics($this->comicQuery)
            ->getData()
            ->getResults();
    }

    /**
     * Find one creator matching id
     *
     * @param string $id
     * @return Octante\MarvelAPIBundle\Model\Entities\Creator
     */
    public function findOneById($id)
    {
        return $this->repository
            ->getCreatorById(intval($id))
            ->getData()
            ->getResults()[0];
    }

    /**
     * Find all characters matching query
     *
     * @param string $query input from search form
     * @return array
     */
     public function findAllByQuery($query, $page)
     {
          $comics_per_page = $this->comicsPerPage;
          $this->query->setNameStartsWith($query);
          $this->query->setOrderBy('firstName');
          $this->query->setLimit($comics_per_page);
          $this->query->setOffset(($page * $comics_per_page) - $comics_per_page);

          return $this->repository
              ->getCreators($this->query)
              ->getData()
              ->getResults();
    }
}
