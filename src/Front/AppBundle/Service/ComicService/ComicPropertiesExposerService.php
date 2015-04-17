<?php

namespace Front\AppBundle\Service\ComicService;

use Octante\MarvelAPIBundle\Model\Entities\Comic;

class ComicPropertiesExposerService
{
    public function __construct()
    {

    }

    public function exposeProperties(Comic $comic)
    {
        $exposedComic = new \stdClass();
        $exposedComicId = new \stdClass();
        $exposedComicThumbnail = new \stdClass();
        $exposedComicUrls = new \stdClass();

        $exposedComicId->comicId = $comic->getId()->getComicId();

        $exposedComicThumbnail->path = $comic->getThumbnail()->getPath();
        $exposedComicThumbnail->extension = $comic->getThumbnail()->getExtension();

        // $exposedComicUrls-> = $comic->getUrls();


        $exposedComic->id = $exposedComicId;
        $exposedComic->thumbnail = $exposedComicThumbnail;
        // $exposedComic->urls = $exposedComicUrls;

        // $exposedComic->resourceURI = $comic->getResourceURI();
        $exposedComic->title = $comic->getTitle();

        return $exposedComic;
    }
}
