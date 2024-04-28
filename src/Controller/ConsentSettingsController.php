<?php

namespace huppys\CookieConsentBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ConsentSettingsController
{

    public function __construct()
    {
    }

    public function update(Request $request): Response
    {
        return new JsonResponse('ok');
    }
}