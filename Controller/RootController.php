<?php

namespace Baikal\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\JsonResponse;

class RootController extends Controller {

    public function indexAction() {
        return new JsonResponse(true);
    }
}
