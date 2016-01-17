<?php

namespace Shoko\ApiBundle\Repository;

use Octante\MarvelAPIBundle\Repositories\ComicsRepository;
use Octante\MarvelAPIBundle\Model\Query\ComicQuery;
use Octante\MarvelAPIBundle\Repositories\CharactersRepository;
use Octante\MarvelAPIBundle\Model\Query\CharacterQuery;

/**
 * CharacterRepository class.
 */
class CharacterRepository
{
    /**
     * Number of comics displayed.
     *
     * @var int
     */
    private $comicsPerPage;

    /**
     * CharactersRepository.
     *
     * @var CharactersRepository
     */
    private $repository;

    /**
     * CharacterQuery.
     *
     * @var CharacterQuery
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
     * CharacterRepository constructor.
     *
     * @param CharactersRepository $repository
     * @param CharacterQuery       $query
     * @param ComicsRepository     $comicRepository
     * @param ComicQuery           $comicQuery
     * @param int                  $comicsPerPage
     */
    public function __construct(CharactersRepository $repository, CharacterQuery $query, ComicsRepository $comicRepository, ComicQuery $comicQuery, $comicsPerPage)
    {
        $this->repository = $repository;
        $this->query = $query;
        $this->comicRepository = $comicRepository;
        $this->comicQuery = $comicQuery;
        $this->comicsPerPage = $comicsPerPage;
    }

    /**
     * Find all comics matching character id.
     *
     * @param string $id
     * @param string $page
     *
     * @return Octante\MarvelAPIBundle\Model\DataContainer\CharacterDataContainer
     */
    public function findAllComicsById($id, $page)
    {
        $comicsPerPage = $this->comicsPerPage;
        $this->comicQuery->setCharacters($id);
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
     * Find one character matching id.
     *
     * @param string $id
     *
     * @return Octante\MarvelAPIBundle\Model\DataContainer\CharacterDataContainer
     */
    public function findOneById($id)
    {
        return $this->repository
            ->getCharacterById(intval($id))
            ->getData();
    }

     /**
      * Find all characters matching query.
      *
      * @param string $query input from search form
      * @param string $page
      * 
      * @return Octante\MarvelAPIBundle\Model\DataContainer\CharacterDataContainer
      */
     public function findAllByQuery($query, $page)
     {
         $comicsPerPage = $this->comicsPerPage;
         $this->query->setNameStartsWith($query);
         $this->query->setOrderBy('name');
         $this->query->setLimit($comicsPerPage);
         $this->query->setOffset(($page * $comicsPerPage) - $comicsPerPage);

         return $this->repository
            ->getCharacters($this->query)
            ->getData();
     }
}
