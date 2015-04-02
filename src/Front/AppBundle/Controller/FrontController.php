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
        $title = $this->get('translator')->trans('comics.this_week');

        return $this->render('front/week/week.html.twig',
            [
                'title'       => $title,
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

        return $this->render('front/week/week.html.twig',
            [
                'title'       => $title,
                'date'        => $date,
            ]);
    }

    /**
     * Get comics matching the date
     *
     * @param string $date
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function weekComicsAction($date)
    {
        $collection = $this->get('app.comic_repository')->findAllByReleaseDate(Carbon::parse($date));

        return $this->render('front/comic/list.html.twig',
            [
                'collection'  => $collection
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

        return $this->render('front/comic/comic.html.twig',
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
        $serie = $this->get('app.serie_repository')->findOneById($id);

        return $this->render('front/serie/serie.html.twig',
            [
                'serie'       => $serie,
                'id'          => $id,
                'page'        => $page,
            ]);
    }

    /**
     *  Get comics belonging to the serie matching id
     *
     * @param string $id   serie id
     * @param string $page pagination
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function serieComicsAction($id, $page)
    {
        $collection = $this->get('app.serie_repository')->findAllComicsById($id, $page);

        return $this->render('front/comic/list.html.twig',
            [
                'collection'  => $collection,
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
        $creator = $this->get('app.creator_repository')->findOneById($id);

        return $this->render('front/creator/creator.html.twig',
            [
                'creator'     => $creator,
                'id'          => $id,
                'page'        => $page,
            ]);
    }

    /**
     *  Get comics belonging to the creator matching id
     *
     * @param string $id   creator Id
     * @param string $page pagination
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function creatorComicsAction($id, $page)
    {
        $collection = $this->get('app.creator_repository')->findAllComicsById($id, $page);

        return $this->render('front/comic/list.html.twig',
            [
                'collection'  => $collection,
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

        return $this->render('front/search/search.html.twig',
            [
                'query'       => $query,
            ]);
    }

    /**
     *  Get series matching the search query
     *
     * @param string $query search query
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function searchSeriesAction($query)
    {
        $collection = $this->get('app.serie_repository')->findAllByQuery($query);

        return $this->render('front/search/list.html.twig',
            [
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
