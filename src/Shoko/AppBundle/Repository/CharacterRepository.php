<?php

namespace Shoko\AppBundle\Repository;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Octante\MarvelAPIBundle\Repositories\ComicsRepository;
use Octante\MarvelAPIBundle\Model\Query\ComicQuery;
use Octante\MarvelAPIBundle\Repositories\CharactersRepository;
use Octante\MarvelAPIBundle\Model\Query\CharacterQuery;

class CharacterRepository
{
    /**
     * Number of comics displayed
     *
     * @var integer
     */
    private $comicsPerPage;

    /**
     * CharactersRepository
     *
     * @var CharactersRepository
     */
    private $repository;

    /**
     * CharacterQuery
     *
     * @var CharacterQuery
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
     * CharacterRepository constructor
     *
     * @param CharactersRepository $repository
     * @param CharacterQuery       $query
     * @param ComicsRepository $comicRepository
     * @param ComicQuery       $comicQuery
     * @param integer          $comicsPerPage
     */
    public function __construct(CharactersRepository $repository, CharacterQuery $query, ComicsRepository $comicRepository, ComicQuery $comicQuery, $comicsPerPage)
    {
        $this->repository = $repository;
        $this->query = $query;
        $this->comicRepository = $comicRepository;
        $this->comicQuery = $comicQuery;
        $this->comicsPerPage  = $comicsPerPage;
    }

    /**
     * Find all comics matching character id
     *
     * @param string $id
     * @param string $page
     * @return Octante\MarvelAPIBundle\Model\Collections\ComicsCollection|array
     */
    public function findAllComicsById($id, $page)
    {
        $comics_per_page = $this->comicsPerPage;
        try {
            $this->comicQuery->setCharacters($id);
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
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Find one character matching id
     *
     * @param string $id
     * @return Octante\MarvelAPIBundle\Model\Collections\CharactersCollection|array
     */
    public function findOneById($id)
    {
        try {
            return $this->repository
                ->getCharacterById(intval($id))
                ->getData()
                ->getResults()[0];
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Find all characters matching query
     *
     * @param string $query input from search form
     * @return Octante\MarvelAPIBundle\Model\Collections\SeriesCollection|array
     */
     public function findAllByQuery($query, $page)
     {
         $comics_per_page = $this->comicsPerPage;
         try {
            $this->query->setNameStartsWith($query);
            $this->query->setOrderBy('name');
            $this->query->setLimit($comics_per_page);
            $this->query->setOffset(($page * $comics_per_page) - $comics_per_page);

            return $this->repository
                ->getCharacters($this->query)
                ->getData()
                ->getResults();
        } catch (Exception $e) {
            return [];
        }
    }
}
