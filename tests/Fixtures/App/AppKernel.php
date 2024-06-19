<?php

namespace huppys\CookieConsentBundle\tests\Fixtures\App;

use huppys\CookieConsentBundle\CookieConsentBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new CookieConsentBundle()
        ];
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->setParameter('kernel.project_dir', __DIR__);

        $container->setParameter('kernel.secret', 'thisIsASecret');
        $container->loadFromExtension('framework', [
            'test' => true,
        ]);

        $container->loadFromExtension('cookie_consent', [
            'cookie_settings' => []
        ]);
    }
}