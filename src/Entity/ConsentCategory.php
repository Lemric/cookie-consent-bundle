<?php

namespace huppys\CookieConsentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ConsentCategory
{
    private string $name;
    private Collection $cookies;

    public function __construct()
    {
        $this->cookies = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCookies(): Collection
    {
        return $this->cookies;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}