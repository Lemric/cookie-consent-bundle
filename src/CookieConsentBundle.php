<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle;

use huppys\CookieConsentBundle\Entity\CookieSettings;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class CookieConsentBundle extends AbstractBundle
{
    protected string $extensionAlias = 'cookie_consent';

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../config/definition/definition.php', type: 'php');
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $services = $container->services();

        $services->load('huppys\\CookieConsentBundle\\', '../src/')
            ->exclude('../src/{DependencyInjection,Entity,Enum,Kernel/*.php}');

        $services->defaults()
            ->autoconfigure()
            ->autowire()
            ->private();

        // TODO: Challenge this
//        $services->set(CookieConsentController::class)->public();

        if (isset($config['cookie_settings'])) {
            $services->defaults()->bind(CookieSettings::class . '$cookieSettings', $config['cookie_settings']);
        }

        if (isset($config['persist_consent'])) {
            $services->defaults()->bind('bool $persistConsent', $config['persist_consent']);
        }

        if (isset($config['position'])) {
            $services->defaults()->bind('string $cookieConsentPosition', $config['position']);
        }

        if (isset($config['form_action'])) {
            $services->defaults()->bind('string $formAction', $config['form_action']);
        }

        if (isset($config['read_more_route'])) {
            $services->defaults()->bind('string|null $readMoreRoute', $config['read_more_route']);
        }

        if (isset($config['csrf_protection'])) {
            $services->defaults()->bind('bool $csrfProtection', $config['csrf_protection']);
        }

        //        Register pre-compile classes here?
        //        $this->addAnnotatedClassesToCompile([
        //            // you can define the fully qualified class names...
        //            'Acme\\BlogBundle\\Controller\\AuthorController',
        //            // ... but glob patterns are also supported:
        //            'Acme\\BlogBundle\\Form\\**',
        //
        //            // ...
        //        ]);
    }
}
