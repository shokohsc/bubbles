<?php

namespace Front\AppBundle\Repository;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Chadicus\Marvel\Api\Client;
use Front\MarvelApiBundle\Collection;

class SerieRepository
{
    private $client;
    private $twig;

    public function __construct(Client $client, \Twig_Environment $twig)
    {
        $this->client = $client;
        $this->twig = $twig;
    }

    public function findAllComicsById($id, $page)
    {
        $comics_per_page = $this->twig->getGlobals()['comicsPerPage'];
        try {
            $filters = [
                'series' => $id,
                'format' => 'comic',
                'formatType' => 'comic',
                'noVariants' => true,
                'orderBy' => '-issueNumber',
                'limit' => $comics_per_page,
                'offset' => ($page * $comics_per_page) - $comics_per_page
                ];

            return new Collection($this->client, 'comics', $filters);
        } catch (Exception $e) {
            return array();
        }
    }

    public function findOneById($id)
    {
        try {
            $response = $this->client->get('series', intval($id));
            if ($response->getBody()['code'] !== 200) {
                throw new NotFoundHttpException('Sorry mate ! Can\'t find that serie.', new NotFoundHttpException(), 404);
            }

            return $response->getBody()['data']['results'][0];
        } catch (Exception $e) {
            return array();
        }
    }

    public function findAllByQuery($query)
    {
        try {
            $filters = [
                'titleStartsWith' => $query,
                'orderBy' => '-startYear',
                'contains' => 'comic',
                'limit' => 100,
                ];

            return new Collection($this->client, 'series', $filters);
        } catch (Exception $e) {
            return array();
        }
    }
}
