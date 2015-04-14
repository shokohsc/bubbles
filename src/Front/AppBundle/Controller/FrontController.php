<?php

namespace Front\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
