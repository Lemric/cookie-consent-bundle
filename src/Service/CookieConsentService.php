<?php

namespace huppys\CookieConsentBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use huppys\CookieConsentBundle\Entity\CookieConsentLog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CookieConsentService
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function rejectAllCookies(Request $request, Response $response)
    {
        $cookieLog = new CookieConsentLog();

        $cookieLog->setCookieName();
        $cookieLog->setCookieValue();
        $cookieLog->setConsentKey();
        $cookieLog->setIpAddress();
        $cookieLog->setTimestamp();

        $this->entityManager->persist($cookieLog);

        $this->entityManager->flush();

        $response->headers->setCookie();
    }
}