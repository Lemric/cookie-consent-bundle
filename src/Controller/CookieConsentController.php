<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle\Controller;

use huppys\CookieConsentBundle\Cookie\CookieChecker;
use huppys\CookieConsentBundle\Enum\FormSubmitName;
use huppys\CookieConsentBundle\Form\ConsentCookieType;
use huppys\CookieConsentBundle\Form\ConsentSimpleType;
use huppys\CookieConsentBundle\Service\CookieConsentService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[AsController]
class CookieConsentController
{
    public function __construct(
        private readonly Environment          $twigEnvironment,
        private readonly FormFactoryInterface $formFactory,
        private readonly CookieChecker        $cookieChecker,
        private readonly RouterInterface      $router,
        private readonly string               $cookieConsentPosition,
        private readonly LocaleAwareInterface $translator,
        private readonly string|null          $formAction,
        private readonly string|null          $readMoreRoute,
        private readonly CookieConsentService $cookieConsentService
    )
    {
    }

    #[Route('/cookie-consent/update', name: 'cookie-consent.update')]
    public function update(Request $request, Response $response, LoggerInterface $logger): JsonResponse
    {
        $form = $this->createSimpleConsentForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($rejectAllButton = $form->get(FormSubmitName::REJECT_ALL)) {
                $rejectAll = $rejectAllButton->isClicked();

                if ($rejectAll) {
                    // tell consent manager service to set cookie values accordingly
                    $this->cookieConsentService->rejectAllCookies($request, $response);

                    return new JsonResponse('ok', Response::HTTP_OK);
                }
            }

            if ($acceptAllButton = $form->get(FormSubmitName::ACCEPT_ALL)) {
                $acceptAll = $acceptAllButton->isClicked();

                if ($acceptAll) {
                    // tell consent manager service to set cookie values accordingly
                    $this->handleFormSubmit($form->getData(), $request, $response, $rejectAllCookies);

                    return new JsonResponse('ok', Response::HTTP_OK);
                }
            }

        } else if ($form->isSubmitted() && $form->getClickedButton() == null) {
            $logger->error('Invalid form passed to consent manager update');
            return new JsonResponse('error', status: Response::HTTP_BAD_REQUEST);
        }

        $logger->error('Error while updating cookies via consent manager');
        return new JsonResponse('error', status: Response::HTTP_BAD_REQUEST);
    }

    /**
     * Show cookie consent.
     */
    #[Route('/cookie-consent/show', name: 'cookie_consent.show')]
    public function show(Request $request): Response
    {
        $this->setLocale($request);

        try {
            $response = new Response(
                $this->twigEnvironment->render('@CookieConsent/cookie_consent.html.twig', [
                    'simple_form' => $this->createSimpleConsentForm()->createView(),
                    'form' => $this->createCookieConsentForm()->createView(),
                    'position' => $this->cookieConsentPosition,
                    'read_more_route' => $this->readMoreRoute,
                ])
            );

            // Cache in ESI should not be shared
            $response->setPrivate();
            $response->setMaxAge(0);

            return $response;
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show cookie consent.
     */
    #[Route('/cookie-consent/show-if-not-set', name: 'cookie_consent.show_if_cookie_consent_not_set')]
    public function showIfCookieConsentNotSet(Request $request): Response
    {
        if ($this->cookieChecker->isCookieConsentOptionSetByUser() === false) {
            return $this->show($request);
        }

        return new Response();
    }

    /**
     * Create cookie consent form.
     */
    private function createSimpleConsentForm(): FormInterface
    {
        $formBuilder = $this->formFactory->createBuilder(ConsentSimpleType::class);

        if ($this->formAction != null) {
            $formBuilder->setAction($this->router->generate($this->formAction));
        }

        return $formBuilder->getForm();
    }

    /**
     * Create cookie consent form.
     */
    private function createCookieConsentForm(): FormInterface
    {
        $formBuilder = $this->formFactory->createBuilder(ConsentCookieType::class);

        if ($this->formAction != null) {
            $formBuilder->setAction($this->router->generate($this->formAction));
        }

        return $formBuilder->getForm();
    }

    /**
     * Set locale if available as GET parameter.
     */
    private function setLocale(Request $request): void
    {
        $locale = $request->get('locale');
        if (empty($locale) === false) {
            $this->translator->setLocale($locale);
            $request->setLocale($locale);
        }
    }
}
