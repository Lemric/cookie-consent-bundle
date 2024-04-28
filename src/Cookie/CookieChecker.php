<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle\Cookie;

use huppys\CookieConsentBundle\Enum\CookieName;
use Symfony\Component\HttpFoundation\Request;

class CookieChecker
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Check if cookie consent has already been saved.
     */
    public function isCookieConsentOptionSetByUser(): bool
    {
        return $this->request->cookies->has(CookieName::COOKIE_CONSENT_NAME);
    }

    /**
     * Check if given cookie category is permitted by user.
     */
    public function isCategoryAllowedByUser(string $category): bool
    {
        return $this->request->cookies->get(CookieName::getCookieCategoryName($category)) === 'true';
    }
}
