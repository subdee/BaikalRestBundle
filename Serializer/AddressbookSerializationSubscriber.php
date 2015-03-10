<?php

namespace Baikal\RestBundle\Serializer;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface,
    JMS\Serializer\EventDispatcher\ObjectEvent,
    JMS\Serializer\EventDispatcher\Events as SerializerEvents;

use Sabre\VObject;

class AddressbookSerializationSubscriber implements EventSubscriberInterface {

    public function __construct() { }

    public static function getSubscribedEvents() {
        return array(
            array(
                'event' => SerializerEvents::POST_SERIALIZE,
                'class' => 'Baikal\ModelBundle\Entity\Addressbook',
                'method' => 'onPostSerialize_Addressbook'
            ),
        );
    }

    public function onPostSerialize_Addressbook(ObjectEvent $event) {
        /*
        $addressbook = $event->getObject();

        $event->getVisitor()->addData(
            'links',
            array(
                'contacts' => '/addressbooks/' . $addressbook->getId() . '/contacts'
            )
        );
        */
    }
}