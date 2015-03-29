<?php

namespace Front\AppBundle\Repository;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Chadicus\Marvel\Api\Client;
use Front\MarvelApiBundle\Collection;

class CreatorRepository
{
    /**
     * Marvel Api Client
     * @var Client
     */
    private $client;

    /**
     * Number of comics displayed
     * @var integer
     */
    private $comicsPerPage;

    /**
     * Creator repository constructor
     * @param Client $client
     * @param insteger $comicsPerPage
     */
    public function __construct(Client $client, $comicsPerPage)
    {
        $this->client         = $client;
        $this->comicsPerPage  = $comicsPerPage;
    }

    /**
     * Find all comics matching creator id
     * @param string $id
     * @param string $page
     * @return Collection|array
     */
    public function findAllComicsById($id, $page)
    {
        $comics_per_page = $this->comicsPerPage;
        try {
            $filters = [
                'creators'    => $id,
                'format'      => 'comic',
                'formatType'  => 'comic',
                'noVariants'  => true,
                'orderBy'     => '-onsaleDate',
                'limit'       => $comics_per_page,
                'offset'      => ($page * $comics_per_page) - $comics_per_page
                ];

            return new Collection($this->client, 'comics', $filters);
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Find one creator matching id
     * @param string $id
     * @return Chadicus\Marvel\Api\Response|array
     */
    public function findOneById($id)
    {
        try {
            $response = $this->client->get('creators', intval($id));
            if ($response->getBody()['code'] !== 200) {
                throw new NotFoundHttpException('Sorry mate ! Can\'t find that creator.', new NotFoundHttpException(), 404);
            }

            return $response->getBody()['data']['results'][0];
        } catch (Exception $e) {
            return array();
        }
    }
}
