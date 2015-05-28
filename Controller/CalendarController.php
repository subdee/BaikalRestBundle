<?php

namespace Baikal\RestBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sabre\DAV\UUIDUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContextInterface,
    Symfony\Component\HttpKernel\Exception\HttpException;

use FOS\RestBundle\View\View,
    FOS\RestBundle\View\ViewHandlerInterface;

use Sabre\VObject;

use Baikal\ModelBundle\Entity\Repository\CalendarRepository,
    Baikal\ModelBundle\Entity\Calendar;

use Sabre\CalDAV\Backend\BackendInterface as CalDAVBackendInterface;

class CalendarController {

    protected $securityContext;
    protected $calendarRepo;
    protected $em;

    public function __construct(
        ViewHandlerInterface $viewhandler,
        SecurityContextInterface $securityContext,
        CalendarRepository $calendarRepo,
        EntityManagerInterface $em,
        CalDAVBackendInterface $davbackend
    ) {
        $this->viewhandler = $viewhandler;
        $this->securityContext = $securityContext;
        $this->calendarRepo = $calendarRepo;
        $this->em = $em;
        $this->davbackend = $davbackend;
    }

    public function getCalendarsAction() {

        if(!$this->securityContext->isGranted('rest.api')) {
            throw new HttpException(401, 'Unauthorized access.');
        }

        $calendars = $this->calendarRepo->findByUser(
            $this->securityContext->getToken()->getUser()
        );

        return $this->viewhandler->handle(
            View::create([
                'calendar' => $calendars,
                'meta' => [
                    'total' => count($calendars),
                ]
            ], 200)
        );
    }

    public function getCalendarAction(Calendar $calendar) {

        if(!$this->securityContext->isGranted('dav.read', $calendar)) {
            throw new HttpException(401, 'Unauthorized access.');
        }

        return $this->viewhandler->handle(
            View::create([
                'calendar' => $calendar,
            ], 200)
        );
    }

    public function postCalendarAction(Request $request) {
        $data = json_decode($request->getContent(), TRUE);

        $calendar = new Calendar();
        $uid = strtoupper(UUIDUtil::getUUID());
        $calendar->setUri($uid);
        $calendar->setPrincipaluri('principals/admin');
        $calendar->setDisplayname($data['displayName']);
        $calendar->setDescription($data['description']);
        $this->em->persist($calendar);
        $this->em->flush();

        return $this->viewhandler->handle(
            View::create([
                'calendar' => $calendar,
            ], Response::HTTP_CREATED)
        );
    }
}
