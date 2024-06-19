<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle\tests\Controller;

use huppys\CookieConsentBundle\Controller\CookieConsentController;
use huppys\CookieConsentBundle\Cookie\CookieChecker;
use huppys\CookieConsentBundle\Form\ConsentSimpleType;
use huppys\CookieConsentBundle\Service\CookieConsentService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class CookieConsentControllerTest extends TestCase
{
    private MockObject $templating;

    private MockObject $formFactory;

    private MockObject $cookieChecker;

    private MockObject $translator;

    private MockObject $router;

    private CookieConsentController $cookieConsentController;

    public function setUp(): void
    {
        $this->templating = $this->createMock(Environment::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->cookieChecker = $this->createMock(CookieChecker::class);
        $this->translator = $this->createMock(Translator::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->cookieConsentService = $this->createMock(CookieConsentService::class);

        $this->cookieConsentController = new CookieConsentController(
            $this->templating,
            $this->formFactory,
            $this->cookieChecker,
            $this->router,
            'top',
            $this->translator,
            null,
            null,
            $this->cookieConsentService
        );
    }

    /**
     * @throws Exception
     */
    #[Test]
    public function shouldReturnResponseAfterRender(): void
    {
        $this->templating
            ->expects($this->once())
            ->method('render')
            ->willReturn('test');

        $response = $this->cookieConsentController->show(new Request());

        $this->assertInstanceOf(Response::class, $response);
    }

    #[Test]
    public function shouldShowConsentFormIfCookieNotSet(): void
    {
        $this->cookieChecker
            ->expects($this->once())
            ->method('isCookieConsentOptionSetByUser')
            ->willReturn(false);

        $response = $this->cookieConsentController->showIfCookieConsentNotSet(new Request());

        $this->assertInstanceOf(Response::class, $response);
    }

    #[Test]
    public function shouldShowIfCookieConsentNotSetWithLocale(): void
    {
        $this->cookieChecker
            ->expects($this->once())
            ->method('isCookieConsentOptionSetByUser')
            ->willReturn(false);

        $this->templating
            ->expects($this->once())
            ->method('render')
            ->willReturn('test');

        $request = $this->createMock(Request::class);
        $locale = 'en';

        $request
            ->expects($this->once())
            ->method('get')
            ->with('locale')
            ->willReturn($locale);

        $this->translator
            ->expects($this->once())
            ->method('setLocale')
            ->with($locale);

        $request
            ->expects($this->once())
            ->method('setLocale')
            ->with($locale);

        $response = $this->cookieConsentController->showIfCookieConsentNotSet($request);

        $this->assertInstanceOf(Response::class, $response);
    }

    #[Test]
    public function shouldShowIfCookieConsentNotSetWithCookieConsentSet(): void
    {
        $this->cookieChecker
            ->expects($this->once())
            ->method('isCookieConsentOptionSetByUser')
            ->willReturn(true);

        $this->formFactory
            ->expects($this->never())
            ->method('create')
            ->with(ConsentSimpleType::class);

        $this->templating
            ->expects($this->never())
            ->method('render');

        $response = $this->cookieConsentController->showIfCookieConsentNotSet(new Request());

        $this->assertInstanceOf(Response::class, $response);
    }
}
