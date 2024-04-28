<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle\tests\Controller;

use PHPUnit\Framework\TestCase;

class CookieConsentControllerTest extends TestCase
{
//    private MockObject $templating;
//
//    private MockObject $formFactory;
//
//    private MockObject $cookieChecker;
//
//    private MockObject $translator;
//
//    private MockObject $router;
//
//    private CookieConsentController $cookieConsentController;
//
//    public function setUp(): void
//    {
//        $this->templating              = $this->createMock(Environment::class);
//        $this->formFactory             = $this->createMock(FormFactoryInterface::class);
//        $this->cookieChecker           = $this->createMock(CookieChecker::class);
//        $this->translator              = $this->createMock(Translator::class);
//        $this->router                  = $this->createMock(RouterInterface::class);
//        $this->cookieConsentController = new CookieConsentController(
//            $this->templating,
//            $this->formFactory,
//            $this->cookieChecker,
//            $this->router,
//            'top',
//            $this->translator,
//            null,
//            null
//        );
//    }
//
//    public function testShow(): void
//    {
//        $this->formFactory
//            ->expects($this->once())
//            ->method('createBuilder')
//            ->with(ConsentCookieType::class)
//            ->willReturn($this->createMock(FormBuilderInterface::class));
//
//        $this->templating
//            ->expects($this->once())
//            ->method('render')
//            ->willReturn('test');
//
//        $response = $this->cookieConsentController->show(new Request());
//
//        $this->assertInstanceOf(Response::class, $response);
//    }
//
//    public function testShowIfCookieConsentNotSet(): void
//    {
//        $this->cookieChecker
//            ->expects($this->once())
//            ->method('isCookieConsentOptionSetByUser')
//            ->willReturn(false);
//
//        $this->formFactory
//            ->expects($this->once())
//            ->method('createBuilder')
//            ->with(ConsentCookieType::class)
//            ->willReturn($this->createMock(FormBuilderInterface::class));
//
//        $this->templating
//            ->expects($this->once())
//            ->method('render')
//            ->willReturn('test');
//
//        $response = $this->cookieConsentController->showIfCookieConsentNotSet(new Request());
//
//        $this->assertInstanceOf(Response::class, $response);
//    }
//
//    public function testShowIfCookieConsentNotSetWithLocale(): void
//    {
//        $this->cookieChecker
//            ->expects($this->once())
//            ->method('isCookieConsentOptionSetByUser')
//            ->willReturn(false);
//
//        $this->formFactory
//            ->expects($this->once())
//            ->method('createBuilder')
//            ->with(ConsentCookieType::class)
//            ->willReturn($this->createMock(FormBuilderInterface::class));
//
//        $this->templating
//            ->expects($this->once())
//            ->method('render')
//            ->willReturn('test');
//
//        $request = $this->createMock(Request::class);
//        $locale  = 'en';
//
//        $request
//            ->expects($this->once())
//            ->method('get')
//            ->with('locale')
//            ->willReturn($locale);
//
//        $this->translator
//            ->expects($this->once())
//            ->method('setLocale')
//            ->with($locale);
//
//        $request
//            ->expects($this->once())
//            ->method('setLocale')
//            ->with($locale);
//
//        $response = $this->cookieConsentController->showIfCookieConsentNotSet($request);
//
//        $this->assertInstanceOf(Response::class, $response);
//    }
//
//    public function testShowIfCookieConsentNotSetWithCookieConsentSet(): void
//    {
//        $this->cookieChecker
//            ->expects($this->once())
//            ->method('isCookieConsentOptionSetByUser')
//            ->willReturn(true);
//
//        $this->formFactory
//            ->expects($this->never())
//            ->method('create')
//            ->with(ConsentCookieType::class);
//
//        $this->templating
//            ->expects($this->never())
//            ->method('render');
//
//        $response = $this->cookieConsentController->showIfCookieConsentNotSet(new Request());
//
//        $this->assertInstanceOf(Response::class, $response);
//    }
}
