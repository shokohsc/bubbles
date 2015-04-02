<?php

namespace Front\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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
