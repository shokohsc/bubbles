<?php

namespace Shoko\ApiBundle\Repository;

use Octante\MarvelAPIBundle\Repositories\ComicsRepository;
use Octante\MarvelAPIBundle\Model\Query\ComicQuery;
use Octante\MarvelAPIBundle\Repositories\CreatorsRepository;
use Octante\MarvelAPIBundle\Model\Query\CreatorQuery;

/**
 * CreatorRepository class.
 */
class CreatorRepository
{
    /**
     * Number of comics displayed.
     *
     * @var int
     */
    private $comicsPerPage;

    /**
     * CreatorsRepository.
     *
     * @var CreatorsRepository
     */
    private $repository;

    /**
     * CreatorQuery.
     *
     * @var CreatorQuery
     */
    private $query;

    /**
     * ComicsRepository.
     *
     * @var ComicsRepository
     */
    private $comicRepository;

    /**
     * ComicQuery.
     *
     * @var ComicQuery
     */
    private $comicQuery;

    /**
     * CreatorRepository constructor.
     *
     * @param CreatorsRepository $repository
     * @param CreatorQuery       $query
     * @param ComicsRepository   $comicRepository
     * @param ComicQuery         $comicQuery
     * @param int                $comicsPerPage
     */
    public function __construct(CreatorsRepository $repository, CreatorQuery $query, ComicsRepository $comicRepository, ComicQuery $comicQuery, $comicsPerPage)
    {
        $this->repository = $repository;
        $this->query = $query;
        $this->comicRepository = $comicRepository;
        $this->comicQuery = $comicQuery;
        $this->comicsPerPage = $comicsPerPage;
    }

    /**
     * Find all comics matching creator id.
     *
     * @param string $id
     * @param string $page
     *
     * @return Octante\MarvelAPIBundle\Model\DataContainer\CreatorDataContainer
     */
    public function findAllComicsById($id, $page)
    {
        $comicsPerPage = $this->comicsPerPage;
        $this->comicQuery->setCreators($id);
        $this->comicQuery->setFormat('comic');
        $this->comicQuery->setFormatType('comic');
        $this->comicQuery->setNoVariants(true);
        $this->comicQuery->setOrderBy('-onsaleDate');
        $this->comicQuery->setLimit($comicsPerPage);
        $this->comicQuery->setOffset(($page * $comicsPerPage) - $comicsPerPage);

        return $this->comicRepository
            ->getComics($this->comicQuery)
            ->getData();
    }

    /**
     * Find one creator matching id.
     *
     * @param string $id
     *
     * @return Octante\MarvelAPIBundle\Model\DataContainer\CreatorDataContainer
     */
    public function findOneById($id)
    {
        return $this->repository
            ->getCreatorById(intval($id))
            ->getData();
    }

     /**
      * Find all characters matching query.
      *
      * @param string $query input from search form
      * @param string $page
      * 
      * @return Octante\MarvelAPIBundle\Model\DataContainer\CreatorDataContainer
      */
     public function findAllByQuery($query, $page)
     {
         $comicsPerPage = $this->comicsPerPage;
         $this->query->setNameStartsWith($query);
         $this->query->setOrderBy('firstName');
         $this->query->setLimit($comicsPerPage);
         $this->query->setOffset(($page * $comicsPerPage) - $comicsPerPage);

         return $this->repository
              ->getCreators($this->query)
              ->getData();
     }
}
