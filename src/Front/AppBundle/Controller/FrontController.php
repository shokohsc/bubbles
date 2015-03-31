<?php

namespace Front\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Carbon\Carbon;

class FrontController extends Controller
{
    /**
     * Index
     *
     * @Route("/", name="homepage")
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function homeAction()
    {
        $date = Carbon::now();
        $title = 'This week';
        $collection = $this->get('app.comic_repository')->findAllByReleaseDate($date);

        return $this->render('front/comics.html.twig',
            [
                'title'       => $title,
                'collection'  => $collection,
                'date'        => $date,
            ]);
    }

    /**
     * Index for that week
     *
     * @Route("/released/{day}/{month}/{year}", name="released", requirements={"day" = "\d+", "month" = "\d+", "year" = "\d+"})
     *
     * @param string $day   day 00-31
     * @param string $month month 00-12
     * @param string $year  year 1939-YYYY
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function thatWeekAction($day, $month, $year)
    {
        $date = Carbon::createFromDate($year, $month, $day);
        $title = $date->diffForHumans(Carbon::now());
        $collection = $this->get('app.comic_repository')->findAllByReleaseDate($date);

        return $this->render('front/comics.html.twig',
            [
                'title'       => $title,
                'collection'  => $collection,
                'date'        => $date,
            ]);
    }

    /**
     * Comic
     *
     * @Route("/comic/{id}", name="comic", requirements={"id" = "\d+"})
     *
     * @param string $id  comic id
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function comicAction($id)
    {
        $comic = $this->get('app.comic_repository')->findOneById($id);

        return $this->render('front/comic.html.twig',
            [
                'comic' => $comic,
            ]);
    }

    /**
     * Comics from Serie
     *
     * @Route("/serie/{id}/{page}", name="serie", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"})
     *
     * @param string $id  serie id
     * @param string $page  pagination
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function serieAction($id, $page)
    {
        $collection = $this->get('app.serie_repository')->findAllComicsById($id, $page);
        $serie = $this->get('app.serie_repository')->findOneById($id);

        return $this->render('front/serie.html.twig',
            [
                'serie'       => $serie,
                'collection'  => $collection,
                'page'        => $page,
            ]);
    }

    /**
     * Comics from Creator
     *
     * @Route("/creator/{id}/{page}", name="creator", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"})
     *
     * @param string $id  creator id
     * @param string $page  pagination
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function creatorAction($id, $page)
    {
        $collection = $this->get('app.creator_repository')->findAllComicsById($id, $page);
        $creator = $this->get('app.creator_repository')->findOneById($id);

        return $this->render('front/creator.html.twig',
            [
                'creator'     => $creator,
                'collection'  => $collection,
                'page'        => $page,
            ]);
    }

    /**
     * Search Serie
     *
     * @Route("/search", name="search")
     *
     * @param Request $request input from user
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request)
    {
        $query = filter_var($request->get('q'), FILTER_SANITIZE_STRING);
        $collection = $this->get('app.serie_repository')->findAllByQuery($query);

        return $this->render('front/search.html.twig',
            [
                'query'       => $query,
                'collection'  => $collection,
            ]);
    }

    /**
     * About
     *
     * @Route("/about", name="about")
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function aboutAction()
    {
      return $this->render('front/about.html.twig');
    }

}
