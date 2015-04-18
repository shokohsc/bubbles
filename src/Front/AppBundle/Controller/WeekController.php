<?php

namespace Front\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class WeekController extends Controller
{
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
        $locale = $this->get('request')->getLocale();
        Carbon::setLocale($locale);
        setlocale(LC_TIME, $locale.'_'.strtoupper($locale));

        $date = Carbon::createFromDate($year, $month, $day);
        $title = $date->diffForHumans(Carbon::now());

        return $this->render('front/week/week.html.twig',
            [
                'title'       => $title,
                'date'        => $date,
            ]
        );
    }

    /**
     * Get comics matching the date
     *
     * @Route("api/week/{date}", name="get_week_comics", options={"expose"=true})
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
     public function weekComicsAction($date = null)
     {
         $date = null === $date ? Carbon::now() : Carbon::parse($date);

         $collection = $this->get('app.comic_repository')->findAllByReleaseDate($date);
         $collection = $this->get('marvel.tojson')->encode($collection);

         $response = new Response(
             $collection,
             Response::HTTP_OK,
             array('content-type' => 'text/json')
         );

         return $response;
     }

}
