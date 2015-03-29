<?php

namespace Front\AppBundle\Twig;

class AppTwigExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('resourceId', array($this, 'resourceIdFilter')),
            new \Twig_SimpleFilter('htmlDecode', array($this, 'htmlDecodeFilter')),
            new \Twig_SimpleFilter('previousWeek', array($this, 'previousWeekFilter')),
            new \Twig_SimpleFilter('nextWeek', array($this, 'nextWeekFilter')),
            new \Twig_SimpleFilter('previousMonth', array($this, 'previousMonthFilter')),
            new \Twig_SimpleFilter('nextMonth', array($this, 'nextMonthFilter')),
        );
    }

    public function resourceIdFilter($resourceURI)
    {
        $arrayResourceURI = explode('/', $resourceURI);
        $arrayResourceURI = array_reverse($arrayResourceURI);

        return $arrayResourceURI[0];
    }

    public function htmlDecodeFilter($string)
    {
        return html_entity_decode($string, ENT_QUOTES);
    }

    public function previousWeekFilter($date, $partOfDate)
    {
        switch ($partOfDate) {
            case 'day':
                return $date->copy()->subWeek()->day;
            case 'month':
                return $date->copy()->subWeek()->month;
            case 'year':
                return $date->copy()->subWeek()->year;
        }
    }

    public function nextWeekFilter($date, $partOfDate)
    {
        switch ($partOfDate) {
            case 'day':
                return $date->copy()->addWeek()->day;
            case 'month':
                return $date->copy()->addWeek()->month;
            case 'year':
                return $date->copy()->addWeek()->year;
        }
    }

    public function previousMonthFilter($date, $partOfDate)
    {
        switch ($partOfDate) {
            case 'day':
                return $date->copy()->subMonth()->day;
            case 'month':
                return $date->copy()->subMonth()->month;
            case 'year':
                return $date->copy()->subMonth()->year;
        }
    }

    public function nextMonthFilter($date, $partOfDate)
    {
        switch ($partOfDate) {
            case 'day':
                return $date->copy()->addMonth()->day;
            case 'month':
                return $date->copy()->addMonth()->month;
            case 'year':
                return $date->copy()->addMonth()->year;
        }
    }

    public function getName()
    {
        return 'app_twig_extension';
    }
}
