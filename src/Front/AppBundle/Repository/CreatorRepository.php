<?php

namespace Front\AppBundle\Repository;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * CreatorRepository constructor
     *
     * @param CreatorsRepository $repository
     * @param CreatorQuery       $query
     * @param integer          $comicsPerPage
     */
    public function __construct(CreatorsRepository $repository, CreatorQuery $query, $comicsPerPage)
    {
        $this->repository = $repository;
        $this->query = $query;
        $this->comicsPerPage  = $comicsPerPage;
    }

    /**
     * Find all comics matching creator id
     *
     * @param string $id
     * @param string $page
     * @return Octante\MarvelAPIBundle\Model\Collections\CreatorsCollection|array
     */
    public function findAllComicsById($id, $page)
    {
        $comics_per_page = $this->comicsPerPage;
        try {
            $this->query->setCreators($id);
            $this->query->setFormat('comic');
            $this->query->setFormatType('comic');
            $this->query->setNoVariants(true);
            $this->query->setOrderBy('-onsaleDate');
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
     * Find one creator matching id
     *
     * @param string $id
     * @return Octante\MarvelAPIBundle\Model\Collections\CreatorsCollection|array
     */
    public function findOneById($id)
    {
        try {
            return $this->repository
                ->getCreatorById(intval($id))
                ->getData()
                ->getResults();
        } catch (Exception $e) {
            return array();
        }
    }
}
