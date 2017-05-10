<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MeetupController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $cache = $this->get('cache.app');
        $cachedCalendar = $cache->getItem('meetup.calendar');
        if ($cachedCalendar->isHit()) {
            $calendar = $cachedCalendar->get();
        } else {
            $calendar = $this->get('meetup_client')->getSelfCalendar(['page' => 25])->getData();
            $cachedCalendar->set($calendar);
            $cache->save($cachedCalendar);
        }

        return [
            'calendar' => $calendar
        ];
    }
}
