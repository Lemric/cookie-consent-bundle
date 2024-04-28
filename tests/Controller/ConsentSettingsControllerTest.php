<?php declare(strict_types=1);

namespace huppys\CookieConsentBundle\tests\Controller;

use huppys\CookieConsentBundle\Controller\ConsentSettingsController;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConsentSettingsControllerTest extends TestCase
{
    private ConsentSettingsController $consentSettingsController;

    protected function setUp(): void
    {
        $this->consentSettingsController = new ConsentSettingsController();
    }

    #[Test]
    public function shouldHaveConsentSettingsController(): void
    {
        $this->assertInstanceOf(ConsentSettingsController::class, $this->consentSettingsController);
    }

    #[Test]
    public function shouldAcceptPostRequestWithConsentSettings(): void
    {
        $request = new Request();


        $response = $this->consentSettingsController->update(new Request());
        $this->assertInstanceOf(Response::class, $response);
    }
}