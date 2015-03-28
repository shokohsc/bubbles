<?php

namespace Front\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Front\AppBundle\Form\Type\ContactType;
use Carbon\Carbon;

class FrontController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function homeAction()
    {
        $date = Carbon::now();
        $collection = $this->get('app.comic_repository')->findAllByReleaseDate($date);

        return $this->render('front/comics.html.twig',
            [
                'title' => 'This week',
                'header' => 'Released as of '.$date->toFormattedDateString(),
                'collection' => $collection,
                'date' => $date,
                'pagination' => true,
                ]);
    }

    /**
     * @Route("/released/{day}/{month}/{year}", name="released", requirements={"day" = "\d+", "month" = "\d+", "year" = "\d+"})
     */
    public function thatWeekAction($day, $month, $year)
    {
        $date = Carbon::createFromDate($year, $month, $day);
        $title = $date->diffForHumans(Carbon::now());
        $collection = $this->get('app.comic_repository')->findAllByReleaseDate($date);

        return $this->render('front/comics.html.twig',
            [
                'title' => $title,
                'header' => 'Released as of '.$date->toFormattedDateString(),
                'collection' => $collection,
                'date' => $date,
                'pagination' => true,
                ]);
    }

    /**
     * @Route("/comic/{id}", name="comic", requirements={"id" = "\d+"})
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
     * @Route("/serie/{id}/{page}", name="serie", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"})
     */
    public function serieAction($id, $page)
    {
        $collection = $this->get('app.serie_repository')->findAllComicsById($id, $page);
        $serie = $this->get('app.serie_repository')->findOneById($id);

        return $this->render('front/comics.html.twig',
            [
                'title' => $serie['title'],
                'header' => $serie['title'],
                'serie' => $serie,
                'collection' => $collection,
                'page' => $page,
                ]);
    }

    /**
     * @Route("/creator/{id}/{page}", name="creator", defaults={"page" = 1}, requirements={"id" = "\d+", "page" = "\d+"})
     */
    public function creatorAction($id, $page)
    {
        $collection = $this->get('app.creator_repository')->findAllComicsById($id, $page);
        $creator = $this->get('app.creator_repository')->findOneById($id);

        return $this->render('front/comics.html.twig',
            [
                'title' => $creator['fullName'],
                'header' => $creator['fullName'],
                'creator' => $creator,
                'collection' => $collection,
                'page' => $page,
                ]);
    }

    /**
     * @Route("/search/", name="search", methods={"POST"})
     */
    public function searchAction(Request $request)
    {
        $collection = $this->get('app.serie_repository')->findAllByQuery($request->request->get('query'));

        return $this->render('front/comics.html.twig',
            [
                'title' => strip_tags($request->request->get('query')),
                'header' => 'Results for '.strip_tags($request->request->get('query')),
                'collection' => $collection,
                'serie' => true,
                'search' => true,
                ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction()
    {
      return $this->render('front/contact.html.twig',
          [
              'title' => 'Contact Bubbles',
              ]);

    }


    /**
     * @Route("/about", name="about")
     */
    public function aboutAction()
    {
      return $this->render('front/about.html.twig',
          [
              'title' => 'Bubbles in a few words',
              ]);
    }

}
