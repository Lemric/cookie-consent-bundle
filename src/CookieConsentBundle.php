<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle;

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
        // is this done in configure()?
        // $config = $this->processConfiguration($configuration, $configs);

        // load services.yaml config
        $container->import('../config/services.yaml');

        $services = $container->services();

        $services->defaults()
            ->autoconfigure()
            ->autowire()
            ->private();


        $parameters = $container->parameters();
        if (isset($config['cookie_settings'])) {
            $services->defaults()->bind('$cookieSettings', $config['cookie_settings']);

            $parameters->set($this->extensionAlias . '.cookie_settings', $config['cookie_settings']);

            if (isset($config['cookie_settings']['name_prefix'])) {
                $parameters->set($this->extensionAlias . '.name_prefix', $config['cookie_settings']['name_prefix']);
            }

            if (isset($config['cookie_settings']['cookies'])) {
                $parameters->set($this->extensionAlias . '.cookies', $config['cookie_settings']['cookies']);
            }
        }

        if (isset($config['consent_categories'])) {
            $parameters->set($this->extensionAlias . '.consent_categories', $config['consent_categories']);
        }

        if (isset($config['persist_consent'])) {
            $services->defaults()->bind('$persistConsent', $config['persist_consent']);

            $parameters->set($this->extensionAlias . '.persist_consent', $config['persist_consent']);
        }

        if (isset($config['position'])) {
            $services->defaults()->bind('$cookieConsentPosition', $config['position']);

            $parameters->set($this->extensionAlias . '.position', $config['position']);
        }

        if (isset($config['form_action'])) {
            $services->defaults()->bind('$formAction', $config['form_action']);

            $parameters->set($this->extensionAlias . '.form_action', $config['form_action']);
        }

        if (isset($config['read_more_route'])) {
            $services->defaults()->bind('$readMoreRoute', $config['read_more_route']);

            $parameters->set($this->extensionAlias . '.read_more_route', $config['read_more_route']);
        }

        if (isset($config['csrf_protection'])) {
            $parameters->set($this->extensionAlias . '.csrf_protection', $config['csrf_protection']);
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
