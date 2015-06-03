<?php

namespace Shoko\AppBundle\Controller;

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
        $locale = $this->get('request')->getLocale();
        setlocale(LC_TIME, $locale.'_'.strtoupper($locale));

        $date = Carbon::now();
        $title = $this->get('translator')->trans('comics.this_week');

        return $this->render('front/week/week.html.twig',
            [
                'title'       => $title,
                'date'        => $date,
            ]
        );
    }

    /**
     * Comics for that week
     *
     * @Route("/released/{day}/{month}/{year}", name="released", requirements={"day" = "\d+", "month" = "\d+", "year" = "\d+"})
     *
     * @param string $day   day 00-31
     * @param string $month month 00-12
     * @param string $year  year 1939-YYYY
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function weekAction($day, $month, $year)
    {
        $locale = $this->get('request')->getLocale();
        Carbon::setLocale($locale);
        setlocale(LC_TIME, $locale.'_'.strtoupper($locale));

        $date = Carbon::createFromDate($year, $month, $day);
        $title = $date->diffForHumans();

        return $this->render('front/week/week.html.twig',
            [
                'title'       => $title,
                'date'        => $date,
            ]
        );
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
        $q        = filter_var($request->get('q'), FILTER_SANITIZE_STRING);
        $entity   = filter_var($request->get('entity'), FILTER_SANITIZE_STRING);
        $page     = filter_var($request->get('page'), FILTER_SANITIZE_STRING);

        return $this->render('front/search/search.html.twig',
            [
                'q'       => $q,
                'entity'  => $entity,
                'page'    => $page,
            ]
        );
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
