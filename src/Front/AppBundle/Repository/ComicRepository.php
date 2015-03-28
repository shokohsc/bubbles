<?php

namespace Front\AppBundle\Repository;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Chadicus\Marvel\Api\Client;
use Front\MarvelApiBundle\Collection;
use Carbon\Carbon;

class ComicRepository extends ContainerAware
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getReleaseDateRange($date)
    {
        $end = $date->toDateString();
        $start = $date->copy()->subDays(6)->toDateString();

        return $start.','.$end;
    }

    public function findAllByReleaseDate($date)
    {
        try {
            $filters = [
                'format' => 'comic',
                'formatType' => 'comic',
                'noVariants' => true,
                'dateRange' => $this->getReleaseDateRange($date),
                'orderBy' => 'title',
                ];

            return new Collection($this->client, 'comics', $filters);
        } catch (Exception $e) {
            return array();
        }
    }

    public function findAllByStartUntilNow($id)
    {
        try {
            $filters = [
                'format' => 'comic',
                'formatType' => 'comic',
                'noVariants' => true,
                'series' => $id,
                'dateRange' => Carbon::create(1939, 01, 01)->toDateString().','.Carbon::now()->toDateString(),
                'orderBy' => '-issueNumber',
                ];

            return new Collection($this->client, 'comics', $filters);
        } catch (Exception $e) {
            return array();
        }
    }

    public function findOneById($id)
    {
        try {
            $response = $this->client->get('comics', intval($id));
            if ($response->getBody()['code'] == 404) {
                throw new NotFoundHttpException('Sorry mate ! Can\'t find that comic.', new NotFoundHttpException(), 404);
            }
            return $response->getBody()['data']['results'][0];
        } catch (Exception $e) {
            return array();
        }
    }
}
