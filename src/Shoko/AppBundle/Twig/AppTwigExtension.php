<?php

namespace Shoko\AppBundle\Twig;

class AppTwigExtension extends \Twig_Extension
{
    /**
     * Get filters defined
     *
     * @return array
     */
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

    /**
     * Filter the resourceId from the resourceURI
     *
     * @param string $resourceURI
     * @return string
     */
    public function resourceIdFilter($resourceURI)
    {
        $arrayResourceURI = explode('/', $resourceURI);
        $arrayResourceURI = array_reverse($arrayResourceURI);

        return $arrayResourceURI[0];
    }

    /**
     * Decode html elements leftovers
     *
     * @param string $string comic description
     * @return string
     */
    public function htmlDecodeFilter($string)
    {
        return html_entity_decode($string, ENT_QUOTES);
    }

    /**
     * Previous week
     *
     * @param Carbon\Carbon $date
     * @param string $partOfDate
     * @return Carbon\Carbon
     */
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

    /**
     * Next week
     *
     * @param Carbon\Carbon $date
     * @param string $partOfDate
     * @return Carbon\Carbon
     */
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

    /**
     * Previous month
     *
     * @param Carbon\Carbon $date
     * @param string $partOfDate
     * @return Carbon\Carbon
     */
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

    /**
     * Next month
     *
     * @param Carbon\Carbon $date
     * @param string $partOfDate
     * @return Carbon\Carbon
     */
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

    /**
     * Get Twig_Extension name
     *
     * @return string
     */
    public function getName()
    {
        return 'app_twig_extension';
    }
}
