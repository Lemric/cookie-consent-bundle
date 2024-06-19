<?php

namespace huppys\CookieConsentBundle\tests\Services;

use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class CookieConsentBundleServicesTest extends KernelTestCase
{

    public function setUp(): void
    {
        self::bootKernel();

        $this->containerBuilder = new ContainerBuilder();
        $arr = [];
        $this->containerConfigurator = new ContainerConfigurator($this->containerBuilder, self::createStub(PhpFileLoader::class), $arr, '', '');
    }

    #[Test]
    public function shouldProvideController(): void
    {
        $bundle = static::$kernel->getBundle('CookieConsentBundle');
        static::$kernel->getContainer()->get('getCookieConsentControllerService');
    }
}
