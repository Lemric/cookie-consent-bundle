<?php

namespace huppys\CookieConsentBundle\Entity;

class ConsentCookie
{
    private string $name;
    private bool $value;
    private string $descriptionKey;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function isValue(): bool
    {
        return $this->value;
    }

    public function setValue(bool $value): void
    {
        $this->value = $value;
    }

    public function getDescriptionKey(): string
    {
        return $this->descriptionKey;
    }

    public function setDescriptionKey(string $descriptionKey): void
    {
        $this->descriptionKey = $descriptionKey;
    }
}