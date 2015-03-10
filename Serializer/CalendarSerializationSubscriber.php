<?php

namespace Baikal\RestBundle\Serializer;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface,
    JMS\Serializer\EventDispatcher\ObjectEvent;

use Sabre\VObject;

use Baikal\DavServicesBundle\Service\Helper\DavTimeZoneHelper;

class CalendarSerializationSubscriber implements EventSubscriberInterface {
    
    protected $timezonehelper;

    public function __construct(DavTimeZoneHelper $timezonehelper) {
        $this->timezonehelper = $timezonehelper;
    }

    public static function getSubscribedEvents() {
        return array(
            array(
                'event' => 'serializer.post_serialize', 
                'class' => 'Baikal\ModelBundle\Entity\Calendar',
                'method' => 'onPostSerialize_Calendar'
            )
        );
    }

    public function onPostSerialize_Calendar(ObjectEvent $event) {
        
        $calendar = $event->getObject();
        $timezone = $this->timezonehelper->extractTimeZoneFromDavString($calendar->getTimezone());

        $event->getVisitor()->addData(
            'timezone',
            $timezone->getName()
        );
    }
}